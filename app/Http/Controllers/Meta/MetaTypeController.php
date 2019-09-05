<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Meta\MetaTypeRequest;
use App\Models\Meta\MetaType;
use App\Repositories\Meta\MetaTypeRepository;
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
use App\Component\Classes\AutoListorder;


class MetaTypeController extends Controller
{
    use ModelForm;

    /** @var  MetaTypeRepository */
    private $metaTypeRepository;

    public function __construct(MetaTypeRepository $metaTypeRepo)
    {
        $this->metaTypeRepository = $metaTypeRepo;
    }

    /**
     * Display a listing of the MetaType.
     *
     * @return Response
     */
    public function index($group)
    {
        return Admin::content(function (Content $content) use ($group) {

        if ($group == 'region') {
            $content->header('語言地區');
            $content->description('語言地區');
        } elseif ($group == 'POS') {
            $content->header('詞性');
            $content->description('詞性');
        }

          $content->body($this->grid($group));
    });

    }

    /**
     * Show the form for creating a new MetaType.
     *
     * @return Response
     */
    public function create($group)
    {
        return Admin::content(function (Content $content) use ($group) {

            $content->header(__('meta.meta_type.create_header'));
            $content->description(__('meta.meta_type.create_description'));

            $content->body($this->form('',$group));

        });
    }

    /**
     * Store a newly created MetaType in storage.
     *
     * @param MetaTypeRequest $request
     *
     * @return Response
     */
    public function store(MetaTypeRequest $request,$group)
    {
        $input = $request->all();
        $input['listorder'] = (new AutoListorder(new MetaType()))->setFieldValue(['pid'=>$input['pid']])->getListOrder();
        $input['group'] = $group;

        $metaType = $this->metaTypeRepository->create($input);

        Flash::success(__('meta.meta_type.saved_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('meta.meta_type.index'));

    }

    /**
     * Display the specified MetaType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $metaType = $this->metaTypeRepository->findWithoutFail($id);

        if (empty($metaType)) {
            Flash::error(__('meta.meta_type.not_found'));

            return redirect(route('meta.meta_type.index'));
        }

        return view('meta.meta_type.show')->with('metaType', $metaType);

    }

    /**
     * Show the form for editing the specified MetaType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($group,$id)
    {
        return Admin::content(function (Content $content) use ($id,$group) {

            $content->header(__('meta.meta_type.edit_header'));
            $content->description(__('meta.meta_type.edit_description'));

            $content->body($this->form($id,$group)->edit($id));
        });
    }

    /**
     * Update the specified MetaType in storage.
     *
     * @param  int              $id
     * @param MetaTypeRequest $request
     *
     * @return Response
     */
    public function update($group,$id, MetaTypeRequest $request)
    {
        $metaType = $this->metaTypeRepository->findWithoutFail($id);
        
        if (empty($metaType)) {
            Flash::error(__('meta.meta_type.not_found'));

            return redirect(route('meta.meta_type.index'));
        }
        $input = $request->all();
        $input['group'] = $group;

        $metaType = $this->metaTypeRepository->update($input, $id);

        Flash::success(__('meta.meta_type.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('meta.meta_type.index',['group'=>$group]));
    }

    /**
     * Remove the specified MetaType from storage.
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
            if ($flag = $this->metaTypeRepository->batchDelete('id', $id)) {
                return response()->json(['message' => __('meta.meta_type.deleted_success'), 'status' => $flag]);
            } else {
                return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
            }
        }

        $metaType = $this->metaTypeRepository->findWithoutFail($id);

        if (empty($metaType)) {
            Flash::error(__('meta.meta_type.not_found'));

            return redirect(route('meta.meta_type.index'));
        }

        if ($flag = $this->metaTypeRepository->delete($id)) {

            return response()->json(['message' => __('meta.meta_type.deleted_success'), 'status' => $flag]);
        } else {
            return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
        }
    }

    /**
     * [form description]
     * @return {[type]} [description]
     */
    protected function form($id='',$group)
    {
        return Admin::form(MetaType::class, function (Form $form) use($group){

            $form->text('title', '標題');
            $form->select('pid', '上级標題')->options(MetaType::where(['group'=>$group])->pluck('title','type_id'));
            $form->text('remark', '备注');
            $form->text('group', '分类');
            $form->text('listorder', '排序');

            $form->display('created_at', __('base.created_at'));
            $form->display('updated_at', __('base.updated_at'));
        });

    }

    /**
     * [grid description]
     * @return {[type]} [description]
     */
    protected function grid($group)
    {
        return Admin::grid(MetaType::class, function (Grid $grid) use ($group){
            // 考虑是否需要scope和排序
            $grid->model()->orderBy('listorder', 'desc');
            $grid->model()->where('group', '=', $group);
            // // 添加按钮
            // if (!\Gate::check('meta.meta_type.create')) {
            //     $grid->disableCreation();
            // }

            // // 编辑和删除按钮
            // $grid->actions(function ($actions) {
            //     // 编辑按钮
            //     if (!\Gate::check('meta.meta_type.edit')) {
            //         $actions->disableEdit();
            //     }
            //     // 删除按钮
            //     if (!\Gate::check('meta.meta_type.destroy')) {
            //         $actions->disableDelete();
            //     }
            // });

            // // 导出按钮
            // if (!\Gate::check('meta.meta_type.export')) {
            //     $grid->disableExport();
            // }

            // // 批量操作
            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         // 批量删除按钮
            //         if (!\Gate::check('meta.meta_type.batch_destroy')) {
            //             $batch->disableDelete();
            //         }
            //     });
            // });

            // 添加按钮
            if (Admin::user()->cannot('meta.meta_type.create')) {
                $grid->disableCreation();
            }

            // 编辑和删除按钮
            $grid->actions(function ($actions) {
                // 编辑按钮
                if (Admin::user()->cannot('meta.meta_type.edit')) {
                    $actions->disableEdit();
                }
                // 删除按钮
                $actions->disableDelete();
                if (Admin::user()->cannot('meta.meta_type.destroy')) {
                }

                // \App\Admin\Extensions\Actions\AutoListorder::render($actions);
            });

            // 导出按钮
            if (Admin::user()->cannot('meta.meta_type.export')) {
                $grid->disableExport();
            }

            // 批量操作
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    // 批量删除按钮
                    if (Admin::user()->cannot('meta.meta_type.batch_destroy')) {
                        $batch->disableDelete();
                    }
                });
            });

            $grid->column('type_id', 'ID')->sortable();
            $grid->column('title', '標題')->sortable();
            $grid->column('PIDself.title', '上级標題')->sortable();
            $grid->column('remark', '备注')->sortable();
            // $grid->column('group', '分类')->sortable();
            $grid->column('listorder', '排序')->sortable()/* ->editable() */;

            /**
             * 过滤处理
             */
            $grid->filter(function ($filter)  use ($group){
                $filter->disableIdFilter();
                $filter->column(1 / 2, function ($filter) use ($group){
                    $filter->equal('pid', '上级標題')->select(MetaType::where(['group' => $group])->pluck('title', 'type_id'));
                });
                $filter->column(1 / 2, function ($filter) {
                    $filter->like('title', '標題');
                });
            });
        });
    }
}
