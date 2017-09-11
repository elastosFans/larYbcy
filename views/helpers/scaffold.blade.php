<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">上传信息</h3>
    </div>


    <!-- /.box-header -->
    <div class="box-body">

        <form method="post" action="{{$action}}" id="scaffold" pjax-container>

            <div class="box-body">

                <div class="form-horizontal">


                <div class="form-group">

                    <label for="avatar" class="col-sm-2 control-label">上传文件</label>

                    {{--<div class="col-sm-8">--}}
                        {{--<input type="file" class="avatar" name="avatar"  />--}}
                    {{--</div>--}}


                    {{--<div tabindex="500" class="form-control file-caption  kv-fileinput-caption">--}}
                        {{--<div class="file-caption-name"></div>--}}
                    {{--</div>--}}

                    <div class="input-group-btn">

                        <button type="button" tabindex="500" title="Abort ongoing upload" class="btn btn-default hide fileinput-cancel fileinput-cancel-button">
                            <i class="glyphicon glyphicon-ban-circle"></i>  <span class="hidden-xs">Cancel</span>
                        </button>



                        <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;
                            <span class="hidden-xs">浏览</span><input type="file" class="avatar" name="avatar" id="1505131474017">
                        </div>


                    </div>

                </div>

                </div>
、
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">提交</button>
            </div>

            {{--{{ csrf_field() }}--}}

            <!-- /.box-footer -->
        </form>

    </div>

</div>


<script>

$(function () {

    $('#scaffold').on('submit', function (event) {

        //event.preventDefault();

        if ($('#inputTableName').val() == '') {
            $('#inputTableName').closest('.form-group').addClass('has-error');
            $('#table-name-help').removeClass('hide');

            return false;
        }

        return true;
    });
});

</script>