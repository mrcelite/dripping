@include('manager.header')
@include('manager.slidebar')

    <!-- page heading start-->
    <div class="page-heading">
        <ul class="breadcrumb">
            <li>专辑管理</li>
            <li class="active">添加专辑</li>
        </ul>
    </div>
    <!-- page heading end-->

    <!--body wrapper start-->
    <div class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <div class="panel-body">
                        <form class="form-horizontal js-create-form" method="post" enctype=”multipart/form-data”>
                            <div class="form-group">
                                <label class="col-md-3 control-label">封面名称</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="album_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">是否推荐</label>
                                <div class="col-sm-9">
                                    <input type="checkbox" class="js-switch-red" checked="" data-switchery="true" style="display: none;">
                                </div>
                                <input type="hidden" name="is_recommend" value="2" class="js-album-recommend">
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">封面寄语</label>
                                <div class="col-sm-9">
                                    <textarea rows="6" class="form-control" name="album_word"></textarea>
                                </div>
                            </div>
                            <div class="form-group last">
                                <label class="control-label col-md-3">上传封面</label>
                                <div class="col-md-9">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <img alt="" />
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i>选择图片</span>
                                                <span class="fileupload-exists"><i class="fa fa-undo"></i>修改</span>
                                                <input type="file" name="cover" class="default" />
                                            </span>
                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i>删除</a>
                                        </div>
                                    </div>
                                    <br/>
                                    <span class="label label-danger "></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-9">
                                    <button class="btn btn-success js-btn-submit" type="button">提交</button>
                                    <button class="btn btn-danger" type="button">取消</button>
                                </div>
                            </div>
                            <input type="hidden" class="js-csrf-token" name="_token" value="{{ $csrfToken }}">
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!--body wrapper end-->
@include('manager.slidebarend')
@include('manager.footer')
<script>
    $('.js-btn-submit').click(function () {

        if($('.js-switch-red').attr('checked') == "checked") {
            $('.js-album-recommend').val(1);
        }

        //var params = $(".js-create-form").serialize();
        $.ajax({
            url: '/manager/album/create',
            type: 'POST',
            cache: false,
            data: new FormData($(".js-create-form")[0]),
            processData: false,
            contentType: false
        }).done(function(res) {
            if (res.result == 'success') {
                alert('专辑添加成功');
                window.location = '';
            } else {
                alert(res.msg);
            }
        }).fail(function(res) {
            alert(res.msg);
        });
    });
</script>