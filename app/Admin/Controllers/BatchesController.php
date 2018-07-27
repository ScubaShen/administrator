<?php

namespace App\Admin\Controllers;

use App\Models\Batch;
use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use Illuminate\Support\Facades\Input;

class BatchesController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('批次管理');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('编辑批次');
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('创建批次');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Batch::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('批次名称');
            $grid->company()->name('所属公司');
            $grid->engineering()->name('所属工程');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Batch::class, function (Form $form) {

            $users = User::query()
                ->where('company_id', Batch::find(request()->route()->parameter('id'))->company_id)
                ->get();
            if ($users) {
                foreach ($users as $user) {
                    $users_array[$user->role_id][$user->id] = $user->realname;
                }
            }

            $form->text('name', '批次名称')->rules('required');
            $form->display('company.name', '所屬公司');
            $form->display('engineering.name', '所屬工程');
            $form->number('range', '爆破范围')->rules('required|numeric|min:0');
            $form->number('safe_distance', '安全距离')->rules('required|numeric|min:0');
            $form->number('longitude', '经度')->rules('required|numeric|between:-180,180');
            $form->number('latitude', '纬度')->rules('required|numeric|between:0,90');

            $form->multipleSelect('technicians', '工程技术员')->options(function () use ($users_array) {
                return $users_array[1];
            });

            $form->multipleSelect('custodians', '保管员')->options(function () use ($users_array) {
                return $users_array[2];
            });

            $form->multipleSelect('safety_officers', '安全员')->options(function () use ($users_array) {
                return $users_array[3];
            });

            $form->multipleSelect('powdermen', '爆破员')->options(function () use ($users_array) {
                return $users_array[4];
            });

            $form->select('manager', '负责人')->options(function () use ($users_array) {
                return $users_array[1];
            });

            $form->number('detonator', '雷管')->rules('required|numeric|min:0');
            $form->number('dynamite', '炸药')->rules('required|numeric|min:0');

            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
            $form->editor('description', '批次描述')->rules('required');
        });
    }

}
