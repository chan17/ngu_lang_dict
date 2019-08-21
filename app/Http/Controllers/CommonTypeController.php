<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommonTypeRequest;
use App\Models\CommonType;
use App\Repositories\CommonTypeRepository;
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


class CommonTypeController extends Controller
{
    use ModelForm;

    /** @var  CommonTypeRepository */
    private $commonTypeRepository;

    public function __construct(CommonTypeRepository $commonTypeRepo)
    {
        $this->commonTypeRepository = $commonTypeRepo;
    }

    /**
     * Display a listing of the CommonType.
     *
     * @return Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('common_type.index_header'));
            $content->description(__('common_type.index_description'));

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new CommonType.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('common_type.create_header'));
            $content->description(__('common_type.create_description'));

            $content->body($this->form());

        });
    }

    /**
     * Store a newly created CommonType in storage.
     *
     * @param CommonTypeRequest $request
     *
     * @return Response
     */
    public function store(CommonTypeRequest $request)
    {
        $input = $request->all();

        $commonType = $this->commonTypeRepository->create($input);

        Flash::success(__('common_type.saved_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('common_type.index'));

    }

    /**
     * Display the specified CommonType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $commonType = $this->commonTypeRepository->findWithoutFail($id);

        if (empty($commonType)) {
            Flash::error(__('common_type.not_found'));

            return redirect(route('common_type.index'));
        }

        return view('common_type.show')->with('commonType', $commonType);

    }

    /**
     * Show the form for editing the specified CommonType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(__('common_type.edit_header'));
            $content->description(__('common_type.edit_description'));

            $content->body($this->form($id)->edit($id));
        });
    }

    /**
     * Update the specified CommonType in storage.
     *
     * @param  int              $id
     * @param CommonTypeRequest $request
     *
     * @return Response
     */
    public function update($id, CommonTypeRequest $request)
    {
        $commonType = $this->commonTypeRepository->findWithoutFail($id);

        if (empty($commonType)) {
            Flash::error(__('common_type.not_found'));

            return redirect(route('common_type.index'));
        }

        $commonType = $this->commonTypeRepository->update($request->all(), $id);

        Flash::success(__('common_type.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('common_type.index'));
    }

    /**
     * Remove the specified CommonType from storage.
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
            if ($flag = $this->commonTypeRepository->batchDelete('id', $id)) {
                return response()->json(['message' => __('common_type.deleted_success'), 'status' => $flag]);
            } else {
                return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
            }
        }

        $commonType = $this->commonTypeRepository->findWithoutFail($id);

        if (empty($commonType)) {
            Flash::error(__('common_type.not_found'));

            return redirect(route('common_type.index'));
        }

        if ($flag = $this->commonTypeRepository->delete($id)) {

            return response()->json(['message' => __('common_type.deleted_success'), 'status' => $flag]);
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
        $form = new Form(new CommonType);
            //$form->display('id', 'ID');
            $form->text('title', '标题');
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

        return Admin::grid(CommonType::class, function (Grid $grid) {
            // 考虑是否需要scope和排序
            // $grid->model()->orderBy('listorder', 'asc');

            // // 添加按钮
            // if (!\Gate::check('common_type.create')) {
            //     $grid->disableCreation();
            // }

            // // 编辑和删除按钮
            // $grid->actions(function ($actions) {
            //     // 编辑按钮
            //     if (!\Gate::check('common_type.edit')) {
            //         $actions->disableEdit();
            //     }
            //     // 删除按钮
            //     if (!\Gate::check('common_type.destroy')) {
            //         $actions->disableDelete();
            //     }
            // });

            // // 导出按钮
            // if (!\Gate::check('common_type.export')) {
            //     $grid->disableExport();
            // }

            // // 批量操作
            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         // 批量删除按钮
            //         if (!\Gate::check('common_type.batch_destroy')) {
            //             $batch->disableDelete();
            //         }
            //     });
            // });

            // 添加按钮
            if (Admin::user()->cannot('common_type.create')) {
                $grid->disableCreation();
            }

            // 编辑和删除按钮
            $grid->actions(function ($actions) {
                // 编辑按钮
                if (Admin::user()->cannot('common_type.edit')) {
                    $actions->disableEdit();
                }
                // 删除按钮
                $actions->disableDelete();
                if (Admin::user()->cannot('common_type.destroy')) {
                }
            });

            // 导出按钮
            if (Admin::user()->cannot('common_type.export')) {
                $grid->disableExport();
            }

            // 批量操作
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    // 批量删除按钮
                    if (Admin::user()->cannot('common_type.batch_destroy')) {
                        $batch->disableDelete();
                    }
                });
            });

            $grid->column('title', '标题')->sortable();
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
