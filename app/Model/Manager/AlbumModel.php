<?php
namespace App\Model\Manager;

use App\Model\DBModel;
use Illuminate\Support\Facades\DB;
class AlbumModel extends DBModel
{
    private static $_albumTable             = 'app_album';
    private static $_wallpaperTable         = 'app_wallpaper';
    private static $_wallPaperUserTable     = 'app_wallpaper_view_user';
    private static $_albumViewTable         = 'app_album_view_user';
    private static $_categoryTable          = 'app_wallpaper_category';

    private static $_albumField             = [
        'id','album_name','album_word','album_cover','is_recommend',
        'is_show','view_count','favorite_count','collect_count','created_time','modified_time'
    ];
    private static $_categoryField          = ['id','category_name','parent_id','category_level','is_show',
        'is_recommend','created_time','modified_time'
    ];
    private static $_wallPagerField         = [
        'id','warming_word','wall_url','view_count','down_count','favorite_count','collect_count',
        'is_show','is_recommend','created_time','modified_time'
    ];


    /**
     * 添加影集
     * @param $data array
     * @return int
     */
    public function addAlbum($data)
    {
        
        if (is_array($data) && count($data) > 0) {
            $insertId   = DB::table(self::$_albumTable)->insertGetId($data);
            return $insertId;
        }

        return false;
    }

    /**
     * 修改影集信息
     * @param $albumId int
     * @param $data array
     * @return boolean
     */
    public function updateAlbum($albumId, $data=array())
    {
        if (empty($albumId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_albumTable)
            ->where( [ 'id' => $albumId ] )
            ->update($data);
    }

    /**
     * 获得一条影集信息
     * @param $albumId
     * @return array
     */
    public function getOneAlbum($albumId)
    {

        if(empty($albumId)) {
            return false;
        }

        $oAlbum          = DB::table(self::$_albumTable)
            ->select(self::$_albumField)
            ->where([ 'id' => $albumId ])
            ->first();
        return $oAlbum;
    }

    /**
     * @param       $where
     * @param int   $offset
     * @param int   $limit
     * @param array $aOrder
     * @return mixed
     */
    public function getAlbumLists($where, $offset = 0, $limit = 50, $aOrder = array())
    {
        $oQuery     = DB::table(self::$_albumTable)
            ->select(self::$_albumField);

        if($where) {
            $oQuery->where($where);
        }
        

        if ($aOrder) {
            foreach ($aOrder as $field=>$order) {
                $oQuery->orderBy($field,$order);
            }
        }
        $oAlbumLists = $oQuery->offset($offset)
            ->limit($limit)
            ->get();
        return $oAlbumLists;
    }

    /**
     * 获取总数
     * @param $where
     * @return int
     */
    public function getTotalAlbum($where)
    {
        $oQuery     = DB::table(self::$_albumTable)
            ->select(DB::raw('count(id) as total'));

        if($where) {
            $oQuery->where($where);
        }
        $aResult    = $oQuery->first();

        if ($aResult) {
            return $aResult->total;
        }
        return 0;
    }


    /**
     * @param $albumId
     * @return mixed
     */
    public function removeAlbum($albumId) {
        return DB::table(self::$_albumTable)->where( [ 'id' => $albumId ] )->delete();
    }

}