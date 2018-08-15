<?php

namespace App\Admin\Controllers;

use App\Models\Batch;
use App\Models\User;
use App\Models\Company;
use App\Models\Member;
use App\Models\Material;
use App\Models\Engineering;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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

            $grid->disableCreateButton();

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

            $form->text('name', '批次名称')->rules('required');

            $form->display('company.name', '所屬公司');

            $form->select('engineering_id', '所屬工程')->options(function () {
                return Engineering::all()->pluck('name', 'id');
            });
            $form->number('range', '爆破范围')->rules('required|numeric|min:0');
            $form->number('safe_distance', '安全距离')->rules('required|numeric|min:0');
            $form->number('longitude', '经度')->rules('required|numeric|between:-180,180');
            $form->number('latitude', '纬度')->rules('required|numeric|between:-90,90');

            $form->embeds('groups', '爆破人员', function ($form) {

                $users_array = [];

                if($id = request()->route()->parameter('id')) {
                    $members = Member::query()
                        ->where('company_id', Batch::find($id)->company_id)
                        ->get();
                    if ($members) {
                        foreach ($members as $member) {
                            $users_array[$member->role_id][$member->id] = $member->name;
                        }
                    }
                }

                $form->multipleSelect('technicians', '工程技术员')->options(function () use ($users_array) {
                    return $users_array[1] ?: [];
                });

                $form->multipleSelect('custodians', '保管员')->options(function () use ($users_array) {
                    return $users_array[2] ?: [];
                });

                $form->multipleSelect('safety_officers', '安全员')->options(function () use ($users_array) {
                    return $users_array[3] ?: [];
                });

                $form->multipleSelect('powdermen', '爆破员')->options(function () use ($users_array) {
                    return $users_array[4] ?: [];
                });

                $form->select('manager', '负责人')->options(function () use ($users_array) {
                    return $users_array[1] ?: [];
                });

            });

            $form->embeds('materials', '爆破材料', function ($form) {

                $materials = [];

                if($id = request()->route()->parameter('id')) {
                    $materials = Material::query()
                        ->where('company_id', Batch::find($id)->company_id)
                        ->pluck('name', 'id')
                        ->toArray();
                }

                foreach($materials as $material_id => $material_name) {
                    // 数据格式: {"262":"9","264":"6"}
                    $form->number($material_id, $material_name)->rules('required|numeric|min:0');
                }
            });

            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
            $form->editor('description', '批次描述')->rules('required');
        });
    }

}
