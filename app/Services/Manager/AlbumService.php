<?php
namespace App\Services\Manager;

use App\Model\Manager\AlbumModel;

class AlbumService {

    //权限model对象
    private $oAlbumModel;

    //私有化构造函数
    public function __construct ()
    {
        $this->oAlbumModel = new AlbumModel();
        return $this;
    }

    /**
     * @param       $where
     * @param array $order
     * @param int   $offset
     * @param int   $limit
     * @return mixed
     * @throws \Exception
     */
    public function getAlbumLists($where, $order = array(), $offset = 0, $limit = 50)
    {

        try {
            $where['is_show']   = 1;
            $oAlbumLists        = $this->oAlbumModel->getAlbumLists($where,$offset,$limit,$order);
            return $this->oAlbumModel->object2array($oAlbumLists);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $where
     * @return int
     * @throws \Exception
     */
    public function getTotalAlbum($where)
    {
        try {
            return $this->oAlbumModel->getTotalAlbum($where);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $albumId
     * @return array
     * @throws \Exception
     */
    public function getOneAlbum($albumId)
    {
        if (empty($albumId)) {
            throw new \Exception('缺少必要的参数album_id');
        }

        try {
            return $this->oAlbumModel->getOneAlbum($albumId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createAlbum($data)
    {
        return $this->oAlbumModel->addAlbum($data);
    }

    public function removeAlbum($albumId)
    {
        if (empty($albumId)) {
            throw new \Exception('缺少必要的参数album_id');
        }

        return $this->oAlbumModel->updateAlbum($albumId, ['is_show' => 2]);
    }

    /**
     * @param $albumId
     * @param $updateData
     * @return bool
     * @throws \Exception
     */
    public function updateAlbum($albumId, $updateData)
    {
        if (empty($albumId)) {
            throw new \Exception('缺少必要的参数album_id');
        }

        if (empty($updateData)) {
            throw new \Exception('请填写你要修改的信息');
        }

        try {
            return $this->oAlbumModel->updateAlbum($albumId, $updateData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}