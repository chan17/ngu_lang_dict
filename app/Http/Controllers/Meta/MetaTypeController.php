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
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('meta.meta_type.index_header'));
            $content->description(__('meta.meta_type.index_description'));

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new MetaType.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('meta.meta_type.create_header'));
            $content->description(__('meta.meta_type.create_description'));

            $content->body($this->form());

        });
    }

    /**
     * Store a newly created MetaType in storage.
     *
     * @param MetaTypeRequest $request
     *
     * @return Response
     */
    public function store(MetaTypeRequest $request)
    {
        $input = $request->all();

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
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(__('meta.meta_type.edit_header'));
            $content->description(__('meta.meta_type.edit_description'));

            $content->body($this->form($id)->edit($id));
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
    public function update($id, MetaTypeRequest $request)
    {
        $metaType = $this->metaTypeRepository->findWithoutFail($id);

        if (empty($metaType)) {
            Flash::error(__('meta.meta_type.not_found'));

            return redirect(route('meta.meta_type.index'));
        }

        $metaType = $this->metaTypeRepository->update($request->all(), $id);

        Flash::success(__('meta.meta_type.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('meta.meta_type.index'));
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
    protected function form($id='')
    {
        $form = new Form(new MetaType);
            //$form->display('id', 'ID');
            $form->text('title', '标题');
            $form->select('pid', '上级类型');
            $form->text('remark', '备注');
            $form->text('group', '分类');
            $form->text('listorder', '排序');
            $form->text('', '');

            //$form->display('created_at', __('base.created_at'));
            //$form->display('updated_at', __('base.updated_at'));
        return $form;

    }

    /**
     * [grid description]
     * @return {[type]} [description]
     */
    protected function grid()
    {

        return Admin::grid(MetaType::class, function (Grid $grid) {
            // 考虑是否需要scope和排序
            // $grid->model()->orderBy('listorder', 'asc');

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

            $grid->column('title', '标题')->sortable();
            $grid->column('pid', '上级类型')->sortable();
            $grid->column('remark', '备注')->sortable();
            $grid->column('group', '分类')->sortable();
            $grid->column('listorder', '排序')->sortable();

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
