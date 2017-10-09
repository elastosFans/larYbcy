<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class WebUserController extends Controller
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
            $content->header(trans('admin::lang.web_user'));
            $content->description(trans('admin::lang.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.web_user'));
            $content->description(trans('admin::lang.edit'));
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
            $content->header(trans('admin::lang.web_user'));
            $content->description(trans('admin::lang.create'));
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
        return Administrator::grid(function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->username(trans('admin::lang.username'));
            $grid->name(trans('admin::lang.name'));
            $grid->roles(trans('admin::lang.roles'))->pluck('name')->label();
            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->disableExport();


            if(Admin::user()->can("owner")){ //internal
                $grid->model()->where('type', '>', 0);
            }else{ //web
                $grid->model()->where('type', '=', Admin::user()->type);
            }

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Administrator::form(function (Form $form) {

            $form->display('id', 'ID');
            $form->text('username', trans('admin::lang.username'))->rules('required');
            $form->text('name', trans('admin::lang.name'))->rules('required');
            $form->image('avatar', trans('admin::lang.avatar'));
            $form->password('password', trans('admin::lang.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->ignore(['password_confirmation']);

            //xxl start roles
//            if(Admin::user()->can("owner")){ //internal
//                //todo and the web user logic
//                $result = Role::getRoles(5)->pluck('name', 'id');
//                $rols = Admin::user()->roles->first();
//                $result = Role::getRoles($rols->id)->pluck('name', 'id');
//            }else{ //web
//                $rols = Admin::user()->roles->first();
//                $result = Role::getRoles($rols->id)->pluck('name', 'id');
//            }
            $rols = Admin::user()->roles->first();
            $result = Role::getRoles($rols->id)->pluck('name', 'id');
            $form->multipleSelect('roles', trans('admin::lang.roles'))->options($result);
            //xxl end roles

            //xxl start permissions
            //$form->multipleSelect('permissions', trans('admin::lang.permissions'))->options(Permission::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('admin::lang.permissions'))->hidden();
            //$form->('permissions',trans('admin::lang.permissions'));
            //xxl end permissions

            //xxl start type
            $form->hidden('type','type');
            //xxl end type

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }

                //xxl start add user logic
                $form->permissions =array(2);

                if(Admin::user()->can("owner")){ //internal
                    //todo and the web user logic
                    $count = Admin::user()->hasName($form->name);
                    if($count == 0) {
                        $form->type = time() + rand(0, 9);
                    }else{
                        $form->type = Admin::user()->getTypeFromName($form->name);
                    }
                }else{ //web
                    $form->type = Admin::user()->type;
                }
                //xxl end user logic
            });
        });
    }



}
