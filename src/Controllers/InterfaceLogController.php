<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\InterfaceLog;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class InterfaceLogController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        die("index");

        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.upload_title'));
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
//    public function edit($id)
//    {
//        return Admin::content(function (Content $content) use ($id) {
//            $content->header(trans('admin::lang.administrator'));
//            $content->description(trans('admin::lang.edit'));
//            $content->body($this->form()->edit($id));
//        });
//    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.upload_title'));
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
        return InterfaceLog::grid(function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->file_name(trans('admin::lang.file_name'))->value(function($text) {
                $fileInfo = explode('/',$text);
                return $fileInfo[1];
            });

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
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return InterfaceLog::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->file('file_name', trans('admin::lang.file_name'));
            $form->hidden('show_name', trans('admin::lang.show_name'));
            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));

            $form->saving(function (Form $form) {
//                die($form->file_name);
//                $fileInfo = explode('/',$form->file_name);
//                $form->show_name = $fileInfo[1];
//                $form->user = "abc111";
            });
        });
    }
}
