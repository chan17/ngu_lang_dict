<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\EntryRequest;
use App\Models\Cms\Entry;
use App\Repositories\Cms\EntryRepository;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Response;
use Encore\Admin\Widgets\Table;

class EntryController extends Controller
{
    use ModelForm;

    /** @var  EntryRepository */
    private $entryRepository;

    public function __construct(EntryRepository $entryRepo)
    {
        $this->entryRepository = $entryRepo;
    }

    /**
     * Display a listing of the Entry.
     *
     * @return Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('cms.entries.index_header'));
            $content->description(__('cms.entries.index_description'));

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new Entry.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('cms.entries.create_header'));
            $content->description(__('cms.entries.create_description'));

            $content->body($this->form());

        });
    }

    /**
     * Store a newly created Entry in storage.
     *
     * @param EntryRequest $request
     *
     * @return Response
     */
    public function store(EntryRequest $request)
    {
        $input = $request->all();
// dd($input);
        $entry = $this->entryRepository->create($input);

        Flash::success(__('cms.entries.saved_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('cms.entries.index'));

    }

    /**
     * Display the specified Entry.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $entry = $this->entryRepository->findWithoutFail($id);

        if (empty($entry)) {
            Flash::error(__('cms.entries.not_found'));

            return redirect(route('cms.entries.index'));
        }

        return view('cms.entries.show')->with('entry', $entry);

    }

    /**
     * Show the form for editing the specified Entry.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(__('cms.entries.edit_header'));
            $content->description(__('cms.entries.edit_description'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Update the specified Entry in storage.
     *
     * @param  int              $id
     * @param EntryRequest $request
     *
     * @return Response
     */
    public function update($id, EntryRequest $request)
    {
        
        $entry = $this->entryRepository->findWithoutFail($id);

        if (empty($entry)) {
            Flash::error(__('cms.entries.not_found'));

            return redirect(route('cms.entries.index'));
        }
        
        $entry = $this->entryRepository->edit($request->all(), $id);
        if (!empty($entry)) {
            admin_warning('數據有錯哉', $entry, ['timeOut' => 5000]);
            return back()->withInput();
        }
        Flash::success(__('cms.entries.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('cms.entries.index'));
    }

    /**
     * Remove the specified Entry from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        // 根据 `,` 判断传递过来的是单个id还是多个id
        if (substr_count($id, ',') >= 1) {
            $id = explode(",", $id);
        }

        // 如果是数组则进行批量删除
        if (is_array($id)) {
            if ($flag = $this->entryRepository->batchDelete('entry_id', $id)) {
                return response()->json(['message' => __('cms.entries.deleted_success'), 'status' => $flag]);
            } else {
                return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
            }
        }

        $entry = $this->entryRepository->findWithoutFail($id);

        if (empty($entry)) {
            Flash::error(__('cms.entries.not_found'));

            return redirect(route('cms.entries.index'));
        }

        if ($flag = $this->entryRepository->delete($id)) {

            return response()->json(['message' => __('cms.entries.deleted_success'), 'status' => $flag]);
        } else {
            return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
        }
    }

    /**
     * [form description]
     * @return {[type]} [description]
     */
    protected function form()
    {
        return Admin::form(Entry::class, function (Form $form) {

            // $form->display('entry_id', '詞條ID');
            $form->text('title', '字/詞 方塊字');
            $form->table('explanation', '字詞解釋', function ($table) {
                $table->select('POS','詞性')->options(\App\Models\Meta\MetaType::where(['group'=>'POS'])->orderBy('listorder','desc')->pluck('title','type_id'));
                $table->text('value','解釋');
            });
            $form->table('example', '例句', function ($table) {
                $table->textarea('value','例句')->rows(2);
            });

            $form->hasMany('phonetics', '註音', function (Form\NestedForm $form) {
                $form->select('region_type', '地區')->options(\App\Models\Meta\MetaType::where(['group'=>'region'])->pluck('title','type_id'));
                $form->text('value', '音標');
            });
            // $form->display('created_at', __('base.created_at'));
            // $form->display('updated_at', __('base.updated_at'));

        });
    }

    /**
     * [grid description]
     * @return {[type]} [description]
     */
    protected function grid()
    {

        return Admin::grid(Entry::class, function (Grid $grid) {
            // 考虑是否需要scope和排序
            $grid->model()->orderBy('updated_at', 'desc');

            // // 添加按钮
            // if (!\Gate::check('cms.entries.create')) {
            //     $grid->disableCreation();
            // }

            // // 编辑和删除按钮
            // $grid->actions(function ($actions) {
            //     // 编辑按钮
            //     if (!\Gate::check('cms.entries.edit')) {
            //         $actions->disableEdit();
            //     }
            //     // 删除按钮
            //     if (!\Gate::check('cms.entries.destroy')) {
            //         $actions->disableDelete();
            //     }
            // });

            // // 导出按钮
            // if (!\Gate::check('cms.entries.export')) {
            //     $grid->disableExport();
            // }

            // // 批量操作
            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         // 批量删除按钮
            //         if (!\Gate::check('cms.entries.batch_destroy')) {
            //             $batch->disableDelete();
            //         }
            //     });
            // });

            // 添加按钮
            if (Admin::user()->cannot('cms.entries.create')) {
                $grid->disableCreation();
            }

            // 编辑和删除按钮
            $grid->actions(function ($actions) {
                // 编辑按钮
                if (Admin::user()->cannot('cms.entries.edit')) {
                    $actions->disableEdit();
                }
                // 删除按钮
                if (Admin::user()->cannot('cms.entries.destroy')) {
                    $actions->disableDelete();
                }
            });

            // 导出按钮
            if (Admin::user()->cannot('cms.entries.export')) {
                $grid->disableExport();
            }

            // 批量操作
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    // 批量删除按钮
                    if (Admin::user()->cannot('cms.entries.batch_destroy')) {
                        $batch->disableDelete();
                    }
                });
            });

            $grid->column('entry_id', '詞條ID')->sortable();
            $grid->column('title', '字/詞 方塊字')->sortable();
            $grid->column('explanation', '字詞解釋')->openView(function () {
                $headers = [/* '詞性', */'解釋'];
                $rows = $this->explanation;
                foreach ($rows as $key => $value) {
                    unset($rows[$key]['POS']);
                    unset($rows[$key]['_remove_']);
                }
                return new Table($headers, $rows);
            },'預覽部分');
            
            $grid->column('example', '例句')->openView(function (){
                $headers = ['例句'];
                $rows = $this->example;
                foreach ($rows as $key => $value) {
                    unset($rows[$key]['_remove_']);
                }
                return new Table($headers, $rows);
            },'預覽部分');

            /**
             * 过滤处理
             */
            $grid->filter(function ($filter) {
                // // 禁用id查询框
                // $filter->disableIdFilter();

            });
        });
    }
}
