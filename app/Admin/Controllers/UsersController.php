<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Role;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UsersController extends Controller
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

            $content->header('用户列表');
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

            $content->header('编辑用户');
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

            $content->header('创建用户');
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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('用户名');
            $grid->company()->name('所属公司');
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
        return Admin::form(User::class, function (Form $form) {
            $form->text('name', '用户名')->rules('required');
            $form->password('password', '密碼')->rules('nullable|min:8', [
                'min' => '密碼不能少于8个字符',
            ]);
            $form->text('realname', '真實姓名')->rules('required');
            $form->display('company.name', '所屬公司');
            $form->select('role_id', '角色')->options(function () {
                $roles = Role::all();
                foreach($roles as $role) {
                    $name[$role->id] = $role->name;
                }
                return $name;
            })->rules('required');
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');

            $form->saving(function ($form) {
                return $form->password = bcrypt($form->password);
            });
        });
    }
}
