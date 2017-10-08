<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Actions extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var array
     */
    protected $prepends = [];

    /**
     * @var bool
     */
    protected $allowEdit = true;

    /**
     * @var bool
     */
    protected $allowDelete = true;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Append a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function append($action)
    {
        array_push($this->appends, $action);

        return $this;
    }

    /**
     * Prepend a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function prepend($action)
    {
        array_unshift($this->prepends, $action);

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return void.
     */
    public function disableDelete()
    {
        $this->allowDelete = false;
    }

    /**
     * Disable edit.
     *
     * @return void.
     */
    public function disableEdit()
    {
        $this->allowEdit = false;
    }

    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource ?: parent::getResource();
    }

    /**
     * {@inheritdoc}
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($this);
            call_user_func($callback, $this);
        }

        $actions = $this->prepends;
        if ($this->allowEdit) {
            array_push($actions, $this->editAction());
        }

        //array_push($actions, $this->downloadAction());

        if ($this->allowDelete) {
            array_push($actions, $this->deleteAction());
        }




        $actions = array_merge($actions, $this->appends);

        return implode('', $actions);
    }

    /**
     * Built edit action.
     *
     * @return string
     */
    protected function editAction()
    {

//xxl delete edit start return
        return <<<EOT

       
<a href="{$this->getResource()}/{$this->getKey()}/edit">
    <i class="fa fa-edit"></i>
</a>

EOT;
//xxl delete edit end
    }


    /**
     * Get key of current row.
     * xxl getFileName
     * @return mixed
     */
    public function getFileName()
    {
        return $this->row->{'file_name'};
    }

    /**
     * Get resource of upload.
     * xxl getUploadUri
     * @return string
     */
    public function getUploadUri()
    {
        $curUri = parent::getResource();
        $curUriArray = explode("/",$curUri);
        $uploadUri = $curUriArray[0]."/"."upload/";

        return $uploadUri;

    }


//    function downloadFile(){
//        //文件下载
//        $fileinfo = pathinfo($this->getUploadUri());
//        header('Content-type: application/x-'.$fileinfo['extension']);
//        header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
//        header('Content-Length: '.filesize($this->getUploadUri());
//        readfile($this->getUploadUri());
//        exit();
//
//    }

    /**
     * Built edit action.
     *
     * @return string
     */
    protected function downloadAction()
    {

//xxl download start
        $script = <<<SCRIPT
$('.grid-row-download').unbind('click').click(function() {

//       alert($(this).data('id'));
        
       try{
            var elemIF = document.createElement("iframe");
            elemIF.src ='{$this->getUploadUri()}' + $(this).data('id');
            elemIF.style.display = "none";
            document.body.appendChild(elemIF);
        }catch(e){
            alert("error");
        }

});
SCRIPT;
        Admin::script($script);
        return <<<EOT

<a href="javascript:void(0);" data-id="{$this->getFileName()}" class="grid-row-download">
<i class="fa fa-download"></i> 
</a>

EOT;
//xxl download end

    }



    /**
     * Built delete action.
     *
     * @return string
     */
    protected function deleteAction()
    {
        $confirm = trans('admin::lang.delete_confirm');

        $script = <<<SCRIPT

$('.grid-row-delete').unbind('click').click(function() {



    if(confirm("{$confirm}")) {
        $.ajax({
            method: 'post',
            url: '{$this->getResource()}/' + $(this).data('id'),
            data: {
                _method:'delete',
                _token:LA.token,
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            }
        });
    }
});

SCRIPT;

        Admin::script($script);

        return <<<EOT
<a href="javascript:void(0);" data-id="{$this->getKey()}" class="grid-row-delete">
    <i class="fa fa-trash"></i>
</a>
EOT;
    }
}
