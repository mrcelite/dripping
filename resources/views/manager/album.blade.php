@include('manager.header')
@include('manager.slidebar')

    <!-- page heading start-->
    <div class="page-heading">
        <ul class="breadcrumb">
            <li>专辑管理</li>
            <li class="active">专辑列表</li>
        </ul>
    </div>
    <!-- page heading end-->

    <!--body wrapper start-->
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        专辑管理
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down">折叠/显示</a>
                         </span>
                    </header>
                    <div class="panel-body">

                        <ul id="filters" class="media-filter">
                            <li><a href="/manager/album" data-filter="*">所有</a></li>
                            <li><a href="/manager/album/recommend" data-filter=".recomment">推荐</a></li>
                            <li><a href="#" data-filter=".favorite">点赞</a></li>
                        </ul>

                        <div class="btn-group pull-right">
                            <a href="/manager/album/add" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-folder-open"></i>添加专辑</a>
                        </div>

                        <div id="gallery" class="media-gal">
                            @forelse ($albumLists as $aAlbum)
                                <div class="images item " >
                                    <a class="js-album-list" data-toggle="modal" data-album-id="{{ $aAlbum['id'] }}">
                                        <img src="{{ $imgHost.$aAlbum['album_cover'] }}" alt="" />
                                    </a>
                                    <p>{{ $aAlbum['album_word'] }}</p>
                                </div>
                            @empty
                                <div>
                                    <p>暂无数据</p>
                                </div>
                            @endforelse
                        </div>
                        @if( !empty($albumLists))
                            <div class="col-md-12 text-center clearfix">
                                {!! $oPaginator->render() !!}
                            </div>
                        @endif

                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close js-modal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">编辑专辑</h4>
                                    </div>

                                    <div class="modal-body row js-album-modal">

                                        <div class="col-md-5 img-modal">
                                            <img class="js-album-cover-path" alt="">
                                            {{--<a href="#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i></a>--}}
                                            <a target="_blank" class="btn btn-white btn-sm js-view-original"><i class="fa fa-eye"></i>查看原图</a>

                                            <p class="mtop10"><strong>图片名:</strong> img01.jpg</p>
                                            <p><strong>图片类型:</strong> jpg</p>
                                            <p><strong>图片属性:</strong> 300x200</p>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label>专辑名</label>
                                                <input value="img01.jpg" class="form-control js-album-name">
                                            </div>
                                            <div class="form-group">
                                                <label>专辑寄语</label>
                                                <input value="awesome image" class="form-control js-album-word">
                                            </div>
                                            <div class="form-group">
                                                <label>专辑描述</label>
                                                <textarea rows="2" class="form-control js-album-content" ></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>封面路径</label>
                                                <input id="link" class="form-control js-album-cover" disabled="disabled">
                                            </div>
                                            <div class="pull-right">
                                                <button class="btn btn-danger btn-sm js-remove-album" type="button">删除</button>
                                                <button class="btn btn-success btn-sm js-update-album" type="button">保存</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- modal -->
                    </div>
                    <input type="hidden" class="js-csrf-token" value="{{ $csrfToken }}">
                </section>
            </div>
        </div>
    </div>
    <!--body wrapper end-->
@include('manager.slidebarend')
@include('manager.footer')
<script>
    $('.js-album-list').click(function () {
        var params = {
            album_id:$(this).attr('data-album-id'),
            '_token':$('.js-csrf-token').val()
        };
        $.post('/manager/album/album', params, function (res) {

            if (res.result == 'success') {
                $("#myModal").show();
                $("#myModal").css('display','block').removeClass('fade');

                $('.js-album-modal').attr('data-album-id',res.data.id);
                $('.js-album-cover-path').attr('src',res.data.album_cover);
                $('.js-album-name').val(res.data.album_name);
                $('.js-album-word').val(res.data.album_word);
                $('.js-album-cover').val(res.data.album_cover);
                $('.js-view-original').attr('href',res.data.album_cover);
            } else {
                alert(res.msg);
            }
        });
    });

    $('.js-modal-close').click(function () {
        $("#myModal").hide();
        $("#myModal").css('display','none').addClass('fade');
    });

    $('.js-remove-album').click(function () {
        var params = {
            'album_id' : $('.js-album-modal').attr('data-album-id'),
            '_token' : $('.js-csrf-token').val()
        };
        $.post('/manager/album/remove', params, function (res) {

            if (res.result == 'success') {
                alert('数据删除成功');

                window.location = '';
            } else {
                alert(res.msg);
            }
        });
    });

    $('.js-update-album').click(function () {
        var params = {
            'album_id' : $('.js-album-modal').attr('data-album-id'),
            'album_name' : $('.js-album-name').val(),
            'album_word' : $('.js-album-word').val(),
            '_token':$('.js-csrf-token').val()
        };
        $.post('/manager/album/update', params, function (res) {

            if (res.result == 'success') {
                alert('数据更新成功');

                $("#myModal").hide();
                $("#myModal").css('display','none').addClass('fade');
            } else {
                alert(res.msg);
            }
        });
    });

</script>