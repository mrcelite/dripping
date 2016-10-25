<?php
namespace App\Http\Controllers\Manager;

use App\Services\Manager\AlbumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\LogicException;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;

class CategoryController extends InitController
{

    /*
     * 列表页
     */
    public function index (Request $request)
    {
        $oAlbumService      = new AlbumService();
        $where              = [];
        $aOrder             = [ 'created_time' => 'desc' ];

        $perPage            = 16;
        if ($request->has('page')) {
            $currentPage    = $request->input('page');
            $currentPage    = $currentPage <= 0 ? 1 :$currentPage;
        } else {
            $currentPage    = 1;
        }

        $offset             = ($currentPage - 1) * $perPage;
        $aAlbumLists        = $oAlbumService->getAlbumLists($where,$aOrder,$offset,$perPage);
        $total              = $oAlbumService->getTotalAlbum($where);

        $oPaginator         = new LengthAwarePaginator($aAlbumLists, $total, $perPage, $currentPage, [
            'path'          => Paginator::resolveCurrentPath(),
            'pageName'      => 'page',
        ]);

        $csrfToken  = $request->session()->token();

        return view('manager.album',
            [
                'title'         => '管理后台影集',
                'albumLists'    => $aAlbumLists,
                'oPaginator'    => $oPaginator,
                'csrfToken'     => $csrfToken
            ]
        );
    }

    /*
     * 展示具体影集
     */
    public function album(Request $request)
    {
        try {
            $albumId                = $request->input('album_id');
            $oAlbumService          = new AlbumService();
            $aAlbum                 = $oAlbumService->getOneAlbum($albumId);
            $aAlbum->album_cover    = $_ENV['IMG_HOST'].$aAlbum->album_cover;
            return response()->json(array('result' => 'success','data' => $aAlbum));
        } catch (\Exception $e) {
            return response()->json(array('result' => 'fail','msg' => $e->getMessage()));
        }

    }

    /*
     * 创建影集展示页
     */
    public function add(Request $request)
    {

        $csrfToken  = $request->session()->token();
        return view('manager.albumadd',
            [
                'title'         => '添加影集',
                'csrfToken'     => $csrfToken
            ]
        );
    }

    /*
     * 正式生成数据
     */
    public function create(Request $request)
    {
        try {

            $file = $request->file('cover');

            // 文件是否上传成功
            if ($file->isValid()) {

                /**
                 * 获取文件相关信息
                 */
                // 文件原名
                $originalName   = $file->getClientOriginalName();
                // 扩展名
                $ext            = $file->getClientOriginalExtension();
                //临时文件的绝对路径
                $realPath       = $file->getRealPath();
                // image/jpeg
                $type           = $file->getClientMimeType();
                // 上传文件
                $filename       = md5(date('Y-m-d-H-i-s') . '-' . uniqid()) . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $result         = Storage::disk('local')->put($filename, file_get_contents($realPath));
                if ($result) {

                    $albumName      = $request->input('album_name');
                    $albumWord      = $request->input('album_word');
                    $isRecommend    = $request->input('is_recommend');

                    $aInsertData    = [
                        'album_name'        => $albumName,
                        'album_word'        => $albumWord,
                        'is_recommend'      => $isRecommend,
                        'album_cover'       => $filename
                    ];

                    $oAlbumService  = new AlbumService();
                    $albumId        = $oAlbumService->createAlbum($aInsertData);
                }
            }

            return response()->json(array('result' => 'success','album_id'=> $albumId));
        } catch (\Exception $e) {
            return response()->json(array('result' => 'fail','msg' => $e->getMessage()));
        }
    }

    /*
     * 删除影集
     */
    public function remove(Request $request)
    {

        try {
            $albumId        = $request->input('album_id');
            $oAlbumService  = new AlbumService();
            $oAlbumService->removeAlbum($albumId);

            return response()->json(array('result' => 'success','album_id'=> $albumId));
        } catch (\Exception $e) {
            return response()->json(array('result' => 'fail','msg' => $e->getMessage()));
        }
    }

    /*
     * 更新标签
     */
    public function update(Request $request)
    {
        try {
            $albumId        = $request->input('album_id');
            $albumName      = $request->input('album_name');
            $albumWord      = $request->input('album_word');
            //$isRecommend    = $request->input('is_recommend');

            $aUpdateData    = [
                'album_name'    => $albumName,
                'album_word'    => $albumWord,
                //'is_recommend'  => $isRecommend,
            ];

            
            $oAlbumService  = new AlbumService();
            $oAlbumService->updateAlbum($albumId,$aUpdateData);

            return response()->json(array('result' => 'success','album_id'=> $albumId));
        } catch (\Exception $e) {
            return response()->json(array('result' => 'fail','msg' => $e->getMessage()));
        }
    }
}