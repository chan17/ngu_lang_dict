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
        $grid->column('explanation', '字詞解釋')->openView(function () use($typePOS) {
            $headers = ['詞性','解釋'];
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
                $filter->like('explanation', '詞性')->select(MetaType::where(['group' => 'POS'])->pluck('title', 'type_id'));
            });
            $filter->column(1 / 2, function ($filter) {
                $filter->like('title', '字/詞 方塊字');
            });
        });
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });
        
        return $grid;
    }

    protected function form()
    {
        $form = new Form(new Entry);

        // $form->display('entry_id', '詞條ID');
        $form->text('title', '字/詞 方塊字')->rules('required|max:32');
        $form->table('explanation', '字詞解釋', function ($table) {
            $table->select('POS','詞性')->options(\App\Models\Meta\MetaType::where(['group'=>'POS'])->orderBy('listorder','desc')->pluck('title','type_id'));
            $table->text('value','解釋');
        });
        $form->table('example', '例句', function ($table) {
            $table->textarea('value','例句')->rows(2);
        });

        $form->hasMany('phonetics', '注音', function (Form\NestedForm $form) {
            $form->select('region_type', '地區')->options(\App\Models\Meta\MetaType::where(['group'=>'region'])->pluck('title','type_id'))->rules('required');
            $form->text('value', '音標')->rules('required');
        });
        // $form->display('created_at', __('base.created_at'));
        // $form->display('updated_at', __('base.updated_at'));
        $form->saving(function (Form $form) {
            // dd($form->phonetics == null,$form->phonetics);
            if ($form->phonetics == null) {
                admin_warning('當心', '請填寫注音', ['timeOut' => 5000]);
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
