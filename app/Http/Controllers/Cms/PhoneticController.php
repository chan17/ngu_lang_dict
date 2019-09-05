<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\PhoneticRequest;
use App\Models\Cms\Phonetic;
use App\Repositories\Cms\PhoneticRepository;
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


class PhoneticController extends Controller
{
    use ModelForm;

    /** @var  PhoneticRepository */
    private $phoneticRepository;

    public function __construct(PhoneticRepository $phoneticRepo)
    {
        $this->phoneticRepository = $phoneticRepo;
    }

    /**
     * Display a listing of the Phonetic.
     *
     * @return Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('cms.phonetics.index_header'));
            $content->description(__('cms.phonetics.index_description'));

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new Phonetic.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('cms.phonetics.create_header'));
            $content->description(__('cms.phonetics.create_description'));

            $content->body($this->form());

        });
    }

    /**
     * Store a newly created Phonetic in storage.
     *
     * @param PhoneticRequest $request
     *
     * @return Response
     */
    public function store(PhoneticRequest $request)
    {
        $input = $request->all();

        $phonetic = $this->phoneticRepository->create($input);

        Flash::success(__('cms.phonetics.saved_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('cms.phonetics.index'));

    }

    /**
     * Display the specified Phonetic.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $phonetic = $this->phoneticRepository->findWithoutFail($id);

        if (empty($phonetic)) {
            Flash::error(__('cms.phonetics.not_found'));

            return redirect(route('cms.phonetics.index'));
        }

        return view('cms.phonetics.show')->with('phonetic', $phonetic);

    }

    /**
     * Show the form for editing the specified Phonetic.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(__('cms.phonetics.edit_header'));
            $content->description(__('cms.phonetics.edit_description'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Update the specified Phonetic in storage.
     *
     * @param  int              $id
     * @param PhoneticRequest $request
     *
     * @return Response
     */
    public function update($id, PhoneticRequest $request)
    {
        $phonetic = $this->phoneticRepository->findWithoutFail($id);

        if (empty($phonetic)) {
            Flash::error(__('cms.phonetics.not_found'));

            return redirect(route('cms.phonetics.index'));
        }

        $phonetic = $this->phoneticRepository->update($request->all(), $id);

        Flash::success(__('cms.phonetics.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('cms.phonetics.index'));
    }

    /**
     * Remove the specified Phonetic from storage.
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
            if ($flag = $this->phoneticRepository->batchDelete('id', $id)) {
                return response()->json(['message' => __('cms.phonetics.deleted_success'), 'status' => $flag]);
            } else {
                return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
            }
        }

        $phonetic = $this->phoneticRepository->findWithoutFail($id);

        if (empty($phonetic)) {
            Flash::error(__('cms.phonetics.not_found'));

            return redirect(route('cms.phonetics.index'));
        }

        if ($flag = $this->phoneticRepository->delete($id)) {

            return response()->json(['message' => __('cms.phonetics.deleted_success'), 'status' => $flag]);
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
        return Admin::form(Phonetic::class, function (Form $form) {

            // $form->display('phonetic_id', '音標ID');
            $form->select('region_type', '地區')->options(\App\Models\Meta\MetaType::where(['group'=>'region'])->pluck('title','type_id'));
            $form->display('entry_id', '對應詞條');
            $form->text('value', '音標');

            $form->display('created_at', __('base.created_at'));
            $form->display('updated_at', __('base.updated_at'));

        });
    }

    /**
     * [grid description]
     * @return {[type]} [description]
     */
    protected function grid()
    {

        return Admin::grid(Phonetic::class, function (Grid $grid) {
            // 考虑是否需要scope和排序
            // $grid->model()->orderBy('listorder', 'asc');

            // // 添加按钮
            // if (!\Gate::check('cms.phonetics.create')) {
            //     $grid->disableCreation();
            // }

            // // 编辑和删除按钮
            // $grid->actions(function ($actions) {
            //     // 编辑按钮
            //     if (!\Gate::check('cms.phonetics.edit')) {
            //         $actions->disableEdit();
            //     }
            //     // 删除按钮
            //     if (!\Gate::check('cms.phonetics.destroy')) {
            //         $actions->disableDelete();
            //     }
            // });

            // // 导出按钮
            // if (!\Gate::check('cms.phonetics.export')) {
            //     $grid->disableExport();
            // }

            // // 批量操作
            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         // 批量删除按钮
            //         if (!\Gate::check('cms.phonetics.batch_destroy')) {
            //             $batch->disableDelete();
            //         }
            //     });
            // });

            // 添加按钮
            if (Admin::user()->cannot('cms.phonetics.create')) {
                $grid->disableCreation();
            }

            // 编辑和删除按钮
            $grid->actions(function ($actions) {
                // 编辑按钮
                if (Admin::user()->cannot('cms.phonetics.edit')) {
                    $actions->disableEdit();
                }
                // 删除按钮
                if (Admin::user()->cannot('cms.phonetics.destroy')) {
                    $actions->disableDelete();
                }
            });

            // 导出按钮
            if (Admin::user()->cannot('cms.phonetics.export')) {
                $grid->disableExport();
            }

            // 批量操作
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    // 批量删除按钮
                    if (Admin::user()->cannot('cms.phonetics.batch_destroy')) {
                        $batch->disableDelete();
                    }
                });
            });

            $grid->column('phonetic_id', '音標ID')->sortable();
            $grid->column('region_type', '地區')->sortable();
            $grid->column('entry.title', '對應詞條')->sortable();
            $grid->column('value', '音標')->sortable()->editable();

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
