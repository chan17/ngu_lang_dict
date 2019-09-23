<?php

namespace App\Http\Controllers\Cms;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Cms\Entry;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Models\Meta\MetaType;

class EntryController extends AdminController
{
    protected $title = '詞條';
    public function index(Content $content)
    {
        return $content
            ->title(__('cms.entries.index_header'))
            ->description(__('cms.entries.index_description'))
            ->body($this->grid());
    }

    protected function grid()
    {
        $typePOS = MetaType::where(['group'=>'POS'])->pluck('title','type_id')->toArray();
        // dd($type);
        $grid = new Grid(new Entry);
        $grid->model()->orderBy('updated_at', 'desc');

        $grid->column('entry_id', '詞條ID')->sortable();
        $grid->column('title', '字/詞 方塊字')->sortable();
        $grid->column('explanation', '字詞釋義')->openView(function () use($typePOS) {
            $headers = ['詞性','釋義'];
                $rows = [];
                foreach ($this->explanation as $key => $value) {
                    // dd($value);
                    $value['POS'] = $typePOS[$value['POS']];
                    $rows[] = array_values($value);
                }
            return new Table($headers, $rows);
        }, '預覽部分');

        $grid->column('example', '例句')->openView( function () {
            $headers = ['例句'];
            $rows = [];
            foreach ($this->example as $key => $value) {
                $rows[] = array_values($value);
            }
            return new Table($headers, $rows);
        }, '預覽部分');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->column(1 / 2, function ($filter){
                $filter->like('entry_id', '詞條ID');
                $filter->like('explanation', '詞性')->select(MetaType::selectOptions(function ($query){
                    return $query->where(['group'=>'POS']);
                },''));
            });
            $filter->column(1 / 2, function ($filter) {
                $filter->like('title', '字/詞 方塊字');
            });
        });
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
            $url = route('cms.phonetics.index',['entry_id'=>$actions->row->entry_id]);
            $actions->append('<a href="' . $url .'" target="_blank" >&nbsp; 跳轉到註音</a>');
            $actions->append('&nbsp;&nbsp;<a href="/light_dict/page/search.html?entry_id=' . $actions->row->entry_id .'" target="_blank" > 打开词条</a>');
             
        });
        
        return $grid;
    }
    public function update($id)
    {
        return $this->form($id)->update($id);
    }
    protected function form()
    {
        $form = new Form(new Entry);

        // $form->display('entry_id', '詞條ID');
        $form->text('title', '字/詞 方塊字')->rules('required');
    
            // ->creationRules(['required','max:32', "unique:entries"])
            // ->updateRules(['required','max:32', "unique:entries,title,{{id}}"]);
            
        $form->table('explanation', '字詞釋義', function ($table) {
            $table->select('POS','詞性')->options(MetaType::selectOptions(function ($query){
                return $query->where(['group'=>'POS'])->orderBy('listorder','asc');
            },''));
            $table->text('value','釋義');
        });
        $form->table('example', '例句', function ($table) {
            $table->textarea('value','例句')->rows(2);
        });

        $form->hasMany('phonetics', '註音', function (Form\NestedForm $form) {
            $form->select('region_type', '地區')->options(MetaType::selectOptions(function ($query){
                return $query->where(['group'=>'region']);
            },''))->rules('required');
            $form->text('value', '吳拼')->rules('required');
        });
        // $form->display('created_at', __('base.created_at'));
        // $form->display('updated_at', __('base.updated_at'));
        $form->submitted(function (Form $form) {
            // dd($form);exit;
        });
        $form->saving(function (Form $form) {
            $where = [['title','=',$form->title]];
            if($form->model()->entry_id != null){
                $where[] = ['entry_id','<>',$form->model()->entry_id];
            }
            if(Entry::where($where)->exists()){
                admin_warning('當心', '當前字詞已存在, 可點擊右邊鏈接： <a target="_blank" href="/admin/cms/entries?title='.$form->title.'">__'.$form->title.'__</a>', ['timeOut' => 5000]);
                return back()->withInput();
            }
            // Entry::
            if ($form->phonetics == null) {
                admin_warning('當心', '請填寫註音', ['timeOut' => 5000]);
                return back()->withInput();
            }
        });
        $form->saved(function (Form $form) {
            // 跳转页面
            return redirect('admin/cms/entries');
        });
        return $form;
        
        
    }

}
