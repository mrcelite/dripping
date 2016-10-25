<?php
namespace App\Services\Manager;

use App\Model\Manager\Permission;
use Mockery\CountValidator\Exception;

class PermissionService
{
    //权限model对象
    private $oPermissionModel;

    //静态实例
    public static $_oPermissionInstance;

    //私有化构造函数
    private function __construct ()
    {
        $this->oPermissionModel = new Permission();
        return $this;
    }

    //私有化克隆方法
    private function __clone()
    {

    }

    //对外提供实例
    public static function getInstance()
    {
        if (self::$_oPermissionInstance == null) {
            self::$_oPermissionInstance = new self;
        }
        return self::$_oPermissionInstance;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function addUser($data)
    {

        $aUser      = $this->oPermissionModel->getOneUser($data['user_name']);
        if (!empty($aUser)) {
            throw new \Exception(sprintf("用户名'%s'已经存在",$data['user_name']));
        }

        $aUser      = $this->oPermissionModel->getOneUser($data['real_name'],null,true);
        if (!empty($aUser)) {
            throw new \Exception(sprintf("真实姓名'%s'已经存在",$data['real_name']));
        }

        return $this->oPermissionModel->addUser($data);
    }

    /**
     * @param $userId
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function updateUser($userId, $data)
    {
        if ($userId) {

            $aUser      = $this->oPermissionModel->getOneUser($data['user_name'],$userId);
            if (!empty($aUser)) {
                throw new \Exception(sprintf("用户名'%s'已经存在",$data['user_name']));
            }

            $aUser      = $this->oPermissionModel->getOneUser($data['real_name'],$userId,true);
            if (!empty($aUser)) {
                throw new \Exception(sprintf("真实姓名'%s'已经存在",$data['real_name']));
            }

            return $this->oPermissionModel->updateUser($userId, $data);
        }
    }

    /**
     * @param null $userId
     * @param null $userName
     * @param null $realName
     * @param int  $offset
     * @param int  $limit
     * @return mixed
     */
    public function getUserLists($userId = null, $userName = null, $realName = null, $offset = 0, $limit = 20)
    {
        $where = array();
        if ($userId)
            $where['user_id'] = $userId;

        if ($userName)
            $where['user_name'] = $userName;

        if ($realName)
            $where['real_name'] = $realName;

        return $this->oPermissionModel->getUserLists($where, $offset, $limit);
    }

    /**
     * @param $userId
     * @param $password
     * @param $rePassword
     * @return bool
     * @throws \Exception
     */
    public function updateUserPassword($userId, $password, $rePassword)
    {

        if (empty($userId) || empty($password) || empty($rePassword)) {
            throw new \Exception('缺少必要参数');
        }

        if ($password != $rePassword) {

            throw new \Exception('密码不一致');
        }

        $data['password'] = md5($password);

        return $this->oPermissionModel->updateUser($userId, $data);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function getOneUser($userId)
    {

        if (intval($userId)) {
            return $this->oPermissionModel->getOneUserInfoByUserId($userId);
        }

        return false;
    }

    /**
     * @param null $userId
     * @param null $userName
     * @param null $realName
     * @return mixed
     */
    public function getTotalUser($userId = null, $userName = null, $realName = null)
    {
        $where          = array();
        if ($userId) {
            $where      = [ 'user_id' => $userId ];
        }

        if ($userName){
            $where      = [ 'user_id' => $userId ];
        }

        if ($realName) {
            $where      = array_merge($where, [ 'real_name' => $realName]);
        }

        return $this->oPermissionModel->getTotalUser($where);
    }


    public function getUserByName ($userName, $password)
    {
        if (empty($userName) || empty($password)) {
            throw new \Exception('缺少必要的参数');
        }

        $password = md5($password);
        return $this->oPermissionModel->getOneUserByName($userName, $password);
    }

    /**
     * 获取用户组所拥有的权限
     * @param null $userId
     * @param null $groupId
     * @return bool
     */
    public function getGroupPermissionModule($userId = null, $groupId = null) {

        return $this->oPermissionModel->getGroupPermissionModule($userId,$groupId);
    }

    /**
     * @param $data
     * @return int
     * @throws \Exception
     */
    public function addModule($data)
    {

        if (!isset($data['parent_module_id'])) {
            throw new \Exception('请选择父模块');
        }

        $aModule    = $this->oPermissionModel->getOneModule( [ 'module_id' => $data['parent_module_id'] ] );
        if(empty($aModule) || ( $aModule && $aModule->module_level == 2 )) {
            throw new \Exception("不能给'%s'添加子模块",$aModule->module_name);
        }

        return $this->oPermissionModel->addModule($data);

    }

    /**
     * @param $moduleId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateModule ($moduleId, $data)
    {
        if(empty($moduleId) || empty($data)) {
            throw new \Exception('缺少必要的参数,不能修改');
        }
        return $this->oPermissionModel->updateModule( [ 'module_id' => $moduleId ], $data);
    }

    /**
     * @param $moduleId
     * @return mixed
     * @throws \Exception
     */
    public function removeModule ($moduleId)
    {
        //是否有子节点
        $aChildModule   = $this->oPermissionModel->getTotalChildModule($moduleId);
        if (!empty($aChildModule)) {
            throw new \Exception("本节点下有子节点,请先删除");
        }

        return $this->oPermissionModel->removeModule( [ 'module_id' => $moduleId ] );
    }

    /**
     * @param $moduleId
     * @return bool
     */
    public function getOneModule ($moduleId)
    {

        if (intval($moduleId)) {
            return $this->oPermissionModel->getOneModule( [ 'module_id' => $moduleId ] );
        }
        return false;
    }

    public function getGroupModuleListsByGroupId ( $groupId = null)
    {

        if (empty($groupId)) {
            $aGroupModuleLists  = $this->oPermissionModel->getModuleLists( ['group_id' => $groupId] );
        } else {
            $aGroupModuleLists  = $this->getOneGroupModule($groupId);
        }

        $moduleArr = $moduleTempArr = array();
        $returnArr = array();
        $level = 0;

        if (is_array($aGroupModuleLists) && count($aGroupModuleLists) > 0) {
            foreach ($aGroupModuleLists as $k => $item) {
                $moduleTempArr[ $item->module_id ] = $item->parent_id;
                $moduleArr[ $item->module_id ] = $item;
            }
            $this->sortModule(0, $moduleTempArr, $level, $moduleArr, $returnArr);
        }

        return $returnArr;
    }

    /**
     * @param $parent_id
     * @param $moduleTempArr
     * @param $level
     * @param $moduleArr
     * @param $returnArr
     * @return bool
     */
    private function sortModule ($parent_id, $moduleTempArr, $level, $moduleArr, &$returnArr)
    {

        $arr = array_keys($moduleTempArr, $parent_id);
        if (is_array($arr) && count($arr) > 0) {
            $space = '';
            for ($i = $level; $i > 0; $i--) {
                $space .= "┠";
            }
            if (!empty($space)) {
                $space .= "&nbsp;";
            }
            foreach ($arr as $k => $v) {
                $moduleArr[ $v ]['tempName'] = $space . $moduleArr[ $v ]['module_name'];
                $moduleArr[ $v ]['display'] = $space . $moduleArr[ $v ]['display_weight'];
                $moduleArr[ $v ]['floor'] = $level + 1;
                $returnArr[ $v ] = $moduleArr[ $v ];
                $this->sortModule($v, $moduleTempArr, $level + 1, $moduleArr, $returnArr);
            }
        }

        return false;
    }

    /**
     * @param $data
     * @return int
     */
    public function addGroup($data)
    {
        return $this->oPermissionModel->addGroup($data);
    }

    /**
     * @param $groupId
     * @param $data
     * @return bool
     */
    public function updateGroup($groupId, $data)
    {

        if ($groupId && $data) {
            return $this->oPermissionModel->updateGroup( ['group_id' => $groupId ], $data);
        }

        return false;
    }

    /**
     * 删除一个组，和与组有关的所有信息，如组权限，所有所属这个组的的管理员组
     * @param $groupId
     * @return bool
     * @throws \Exception
     */
    public function delGroup ($groupId)
    {
        if (empty($groupId)) {
            throw new \Exception('缺少必要的参数');
        }

        DB::beginTransaction();
        try {
            //删除这个组的所有权限
            $this->oPermissionModel->removePermissionContainer($groupId);
            //删除属于这个组的管理员信息
            $this->oPermissionModel->removeUserGroup($groupId);
            $this->oPermissionModel->removeGroup($groupId);
            DB::commit();
            return true;
        } catch (\LoginException $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param     $where
     * @param int $offset
     * @param int $limit
     * @return bool
     * @throws \Exception
     */
    public function getGroupLists($where, $offset = 0, $limit = 50)
    {
        if (empty($where)) {
            throw new \Exception('条件不能为空');
        }
        return $this->oPermissionModel->getGroupLists($where,$offset,$limit);
    }

    public function getTotalGroup($where)
    {
        return $this->oPermissionModel->getTotalGroup($where);
    }

    /**
     * @param $groupId
     * @return bool
     * @throws \Exception
     */
    public function getOneGroup($groupId)
    {

        if (empty($groupId)) {
            throw new \Exception('分组id不能为空');
        }

        return $this->oPermissionModel->getOneGroup($groupId);
    }

    /**
     * 设置权限
     * @param $groupId
     * @param $aInsertData
     * @throws \Exception
     */
    public function setGroupPermission ($groupId, $aInsertData)
    {

        if (empty($groupId) && empty($permissionContainer)) {
            throw new \Exception('请选择要分配的权限集合');
        }
        $aPermissionContainer       = $this->oPermissionModel->getGroupPermissionContainer($groupId);
        $aPermissionContainer       = $this->oPermissionModel->object2array($aPermissionContainer);
        if ($aPermissionContainer) {

            $aModuleIdContainer     = array_column($aPermissionContainer,'module_id');
            $aChangeModuleId        = array_column($aInsertData,'module_id');
            $aRemoveModuleId        = array_diff($aModuleIdContainer,$aChangeModuleId);
            $aNewModuleId           = array_diff($aChangeModuleId,$aModuleIdContainer);
            
            DB::beginTransaction();
            try {
                //删除不在新提交权限集合中的权限
                if ($aRemoveModuleId) {
                    $this->oPermissionModel->removePermissionModule($aRemoveModuleId);
                }

                //新增以前集合中没有的数据
                if ($aNewModuleId) {
                    $aPermissionContainer   = array();
                    foreach ($aNewModuleId as $key=>$moduleId) {
                        $containerIndex     = array_search($moduleId, array_column($aInsertData, 'permission_container'));
                        $aPermissionContainer[$key] = [
                            'group_id'              => $groupId,
                            'module_id'             => ['module_id'],
                            'permission_container'  => $aInsertData[$containerIndex]['permission_container']
                        ];
                    }
                    $this->oPermissionModel->addPermissionContainer($aPermissionContainer);
                }

                //修改其他剩余信息
                if ($aChangeModuleId != $aNewModuleId) {
                    foreach ($aInsertData as $key=>$item) {
                        $this->oPermissionModel->updatePermissionContainer(
                            $groupId,
                            [
                                'module_id'             => $item['module_id'],
                                'permission_container'  => $item['permission_container'],
                            ]
                        );
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throwException($e->getMessage());
            }

        } else {
            $aPermissionContainer   = array();
            foreach ($aInsertData as $key => $item) {
                $aPermissionContainer[$key] = [
                    'group_id'              => $groupId,
                    'module_id'             => $item['module_id'],
                    'permission_container'  => $item['permission_container']
                ];
            }
            $this->oPermissionModel->addPermissionContainer($permissionContainer);
        }

    }

    /**
     * @param $groupId
     * @return array|bool
     */
    public function getGroupPermission ($groupId)
    {

        if ($groupId) {
            $aContainer     = $this->oPermissionModel->getGroupPermissionContainer($groupId);
            $aShowContainer = array();
            if (!empty($aContainer)) {
                foreach ($aContainer as $key => $item) {
                    if (!empty($item->permission_container)) {
                        $aShowContainer[ $item->module_id ] = $item->permission_container;
                    }
                }
                return $aShowContainer;
            }
        }

        return false;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function getUserGroupByUserId($userId)
    {

        if (intval($userId)) {
            return $this->oPermissionModel->getUserGroupByUserId($userId);
        }

        return false;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function removeUserGroup($userId)
    {

        if (intval($userId)) {
            return $this->oPermissionModel->removeUserGroup($userId);
        }
        return false;
    }

    /**
     * @param $userId
     * @param $groupId
     * @return int
     * @throws \Exception
     */
    public function addUserGroup ($userId, $groupId)
    {
        if(empty($userId) && empty($groupId)) {
            throw new \Exception('缺少必要参数');
        }

        return $this->oPermissionModel->addUerGroup(['user_id' => $userId, 'group_id' => $groupId]);
    }


    public function updateUserGroup($userId, $groupId)
    {

        if(empty($userId) && empty($groupId)) {
            throw new \Exception('缺少必要参数');
        }

        DB::beginTransaction();
        try {
            $this->oPermissionModel->removeUserGroup($userId);
            $this->oPermissionModel->addUerGroup( ['user_id' => $userId, 'group_id' => $groupId ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throwException($e->getMessage());
        }
    }

    /**
     * @param $groupId
     * @param $moduleId
     * @return bool
     * @throws \Exception
     */
    public function getPermissionByGroupAndModuleId($groupId, $moduleId)
    {

        if(empty($moduleId) && empty($groupId)) {
            throw new \Exception('缺少必要参数');
        }
        return $this->oPermissionModel->getPermissionByGroupAndModuleId($groupId,$moduleId);
    }

    public function getPermissionModuleTree($userId){

        $oModule        = $this->oPermissionModel->getGroupPermissionModule($userId);
        $oModule        = $this->oPermissionModel->object2array($oModule);
        $aModule        = array();
        if ($oModule) {
            array_map(function ($item) use (&$aChangeModule) {
                $aChangeModule[$item['module_id']] = $item;
            }, $oModule);

            $aModule    = array_filter($aChangeModule,function($item) {
                if ( $item['module_level'] == 1 ) {
                    return $item;
                }
            });

            foreach ( $aChangeModule as $key => $item ) {
                if ($item['module_level'] == 2 && $item['parent_module_id'] == $aModule[$item['parent_module_id']]['module_id']) {
                    $aModule[$item['parent_module_id']]['module_lists'][$item['module_id']]    = $item;
                }
            }
        }
        return $aModule;
    }
}

?>