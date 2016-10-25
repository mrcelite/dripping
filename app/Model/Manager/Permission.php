<?php
namespace App\Model\Manager;

use App\Model\DBModel;
use Illuminate\Support\Facades\DB;
class Permission extends DBModel
{
    private static $_moduleTable            = 'app_admin_module';
    private static $_groupModuleTable       = 'app_admin_group_module';
    private static $_groupTable             = 'app_admin_group';
    private static $_userGroupTable         = 'app_admin_user_group';
    private static $_userTable              = 'app_admin_user';

    private static $_moduleField            = [
        'module_id','module_name','module_content','parent_module_id','module_route',
        'module_level','module_weight'
    ];
    private static $_groupModuleField       = ['id','group_id','module_id','permission_container'];
    private static $_groupField             = ['group_id','group_name','group_content'];
    private static $_userGroupField         = ['id','user_id','group_id'];
    private static $_userField              = [
        'user_id','user_name','real_name','password','mobile_phone','is_forbidden','created_time',
        'last_signin_time','last_signin_ip'
    ];


    /**
     * 添加一个管理员
     * @param $data array
     * @return int
     */
    public function addUser($data)
    {
        
        if (is_array($data) && count($data) > 0) {
            $insertId   = DB::table(self::$_userTable)->insertGetId($data);
            return $insertId;
        }

        return false;
    }

    /**
     * 修改管理员信息
     * @param $userId int
     * @param $data array
     * @return boolean
     */
    public function updateUser($userId, $data=array())
    {
        if (empty($userId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_userTable)
            ->where( [ 'user_id' => $userId ] )
            ->update($data);
    }

    /**
     * 获得一个管理员的信息
     * @param $userName
     * @param $password
     * @return array
     */
    public function getOneUserByName($userName, $password)
    {

        if(empty($userName) || empty($password)) {
            return false;
        }

        $where      = [ 'user_name' => $userName, 'password' => $password ];
        $aUser      = DB::table(self::$_userTable)
            ->select(self::$_userField)
            ->where($where)
            ->where('is_forbidden',2)
            ->first();
        return $aUser;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getOneUserInfoByUserId($userId)
    {

        if (empty($userId)) {
            return false;
        }

        $where      = [ 'user_id' => $userId ];
        $aUserInfo  = DB::table(self::$_userTable. ' as u ')
            ->leftJoin(self::$_userGroupTable.' as ug ', 'mg.user_id', '=', 'u.user_id')
            ->leftJoin(self::$_groupTable.' as g ', 'ug.group_id', '=', 'g.group_id')
            ->select('u.*', 'ug.group_id as group_id','g.group_name as group_name')
            ->where($where)
            ->where('is_forbidden',2)
            ->first();
        return $aUserInfo;
    }

    /**
     * @param      $userName
     * @param      $userId
     * @param bool $real
     * @return mixed
     */
    public function getOneUser($userName, $userId = null, $real = false)
    {

        if (empty($userName)) {
            return false;
        }

        if(!$real)
            $where      = [ 'user_name' => $userName ];
        else
            $where      = [ 'real_name' => $userName ];

        $oQuery         = DB::table(self::$_userTable. ' as u ')
            ->select(self::$_userField)
            ->where($where);
        if($userId) {
            $oQuery->where('user_id', '<>', $userId);
        }

        $aUserInfo      = $oQuery->where('is_forbidden',2)
            ->queryRow();
        return $aUserInfo;
    }

    /**
     * @param       $where
     * @param int   $offset
     * @param int   $limit
     * @param array $aOrder
     * @return mixed
     */
    public function getUserLists($where, $offset = 0, $limit = 50, $aOrder = array())
    {
        $oQuery     = DB::table(self::$_userTable)
            ->select(self::$_userField);

        if($where) {
            $oQuery->where($where);
        }

        $oQuery->where('is_forbidden',2);

        if ($aOrder) {
            foreach ($aOrder as $field=>$order) {
                $oQuery->orderBy($field,$order);
            }
        }
        $aUserLists = $oQuery->offset($offset)
            ->limit($limit)
            ->get();
        return $aUserLists;
    }

    /**
     * 获取总数
     * @param $where
     * @return int
     */
    public function getTotalUser($where)
    {
        $oQuery     = DB::table(self::$_userTable)
            ->select(DB::raw('count(user_id) as total'));

        if($where) {
            $oQuery->where($where);
        }
        $aResult    = $oQuery->where('is_forbidden',2)
            ->first();

        if ($aResult) {
            return $aResult->total;
        }
        return 0;
    }

    /**
     * 添加管理组
     * @param $data array
     * @return int
     */
    public function addGroup($data)
    {

        if (is_array($data) && count($data) > 0) {
            $insertId   = DB::table(self::$_groupTable)->insertGetId($data);
            return $insertId;
        }

        return false;
    }

    /**
     * 修改管理组信息
     * @param $groupId int
     * @param $data array
     * @return boolean
     */
    public function updateGroup($groupId, $data=array())
    {
        if (empty($groupId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_groupTable)
            ->where( [ 'group_id' => $groupId ] )
            ->update($data);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public function removeGroup($groupId) {
        return DB::table(self::$_groupTable)->where( [ 'group_id' => $groupId ] )->delete();
    }

    /**
     * @param $groupId
     * @return bool
     */
    public function getOneGroup($groupId)
    {

        if (empty($groupId)) {
            return false;
        }

        $aUserInfo      = DB::table(self::$_groupTable)
            ->select(self::$_groupField )
            ->where( [ 'group_id' => $groupId ] )
            ->where('is_forbidden',2)
            ->first();
        return $aUserInfo;
    }

    /**
     * 获取管理组列表
     * @param     $where
     * @param int $offset
     * @param int $limit
     * @return bool
     */
    public function getGroupLists($where, $offset = 0, $limit = 50)
    {
        if (empty($where)) {
            return false;
        }

        $aUserLists = DB::table(self::$_groupTable)
            ->select(self::$_groupField)
            ->where($where)
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $aUserLists;
    }

    /**
     * 获取组总数
     * @param $where
     * @return int
     */
    public function getTotalGroup($where)
    {
        $aResult    = DB::table(self::$_groupTable)
            ->select(DB::raw('count(group_id) as total'))
            ->where($where)
            ->first();

        if ($aResult) {
            return $aResult->total;
        }
        return 0;
    }


    /**
     * 添加用户组关联数组
     * @param $data array
     * @return int
     */
    public function addUerGroup($data)
    {

        if (is_array($data) && count($data) > 0) {
            $insertId   = DB::table(self::$_userGroupTable)->insertGetId($data);
            return $insertId;
        }

        return false;
    }

    /**
     * 修改用户管理组信息
     * @param $userId int
     * @param $data array
     * @return boolean
     */
    public function updateUserGroup($userId, $data=array())
    {
        if (empty($userId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_userGroupTable)
            ->where( [ 'user_id' => $userId ] )
            ->update($data);
    }

    /**
     * @param $where
     * @return mixed
     */
    public function removeUserGroup($where) {
        return DB::table(self::$_userGroupTable)->where( $where )->delete();
    }

    /**
     * @param $userId
     * @return bool
     */
    public function getUserGroupByUserId($userId)
    {

        if (empty($userId)) {
            return false;
        }

        $aUserInfo      = DB::table(self::$_userGroupTable)
            ->select('group_id')
            ->where( [ 'user_id' => $userId ] )
            ->first();
        return $aUserInfo;
    }
    
    public function getUserPermissionModule($userId = null, $groupId = null, $offset = 0)
    {
        if(empty($userId) && empty($groupId)) {
            return false;
        }

        $oQuery         = DB::table(self::$_groupModuleTable. ' as gm')
            ->leftJoin(self::$_userGroupTable.' as u ', 'u.group_id', '=', 'gm.group_id')
            ->leftJoin(self::$_moduleTable.' as m ', 'gm.module_id', '=', 'm.module_id')
            ->select(
                'm.module_id as module_id',
                'm.module_name as module_name',
                'gm.group_id as group_id',
                'gm.user_id as user_id');

        if (!empty($userId)) {
            $oQuery->where('gm.user_id',$userId);
        }
        if (!empty($groupId)) {
            $oQuery->where('gm.group_id',$groupId);
        }

        $userModuleSet   = $oQuery->offset($offset)->get();
        return $userModuleSet;
    }

    public function getPermissionByGroupAndModuleId($groupId, $moduleId)
    {
        if(empty($moduleId) && empty($groupId)) {
            return false;
        }

        $oPermission        = DB::table(self::$_groupModuleTable)
            ->select('permission_container')
            ->where('group_id',$groupId)
            ->where('module_id',$moduleId)
            ->first();
        return $oPermission;
    }

    /**
     * 添加功能模块
     * @param $data array
     * @return int
     */
    public function addModule($data)
    {

        if (is_array($data) && count($data) > 0) {
            $insertId   = DB::table(self::$_moduleTable)->insertGetId($data);
            return $insertId;
        }

        return false;
    }

    /**
     * 修改模块信息
     * @param $groupId int
     * @param $data array
     * @return boolean
     */
    public function updateModule($groupId, $data=array())
    {
        if (empty($groupId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_moduleTable)
            ->where( [ 'group_id' => $groupId ] )
            ->update($data);
    }

    /**
     * @param $where
     * @return mixed
     */
    public function removeModule($where) {
        return DB::table(self::$_moduleTable)->where( $where )->delete();
    }

    /**
     * @param $where
     * @return bool
     */
    public function getOneModule($where)
    {
        if ($where) {
            return DB::table(self::$_moduleTable)
                ->select(self::$_moduleField)
                ->where($where)
                ->first();
        }

        return false;
    }

    /**
     * @param $parentModuleId
     * @return bool
     */
    public function getChildModule($parentModuleId)
    {
        if ($parentModuleId) {
            return DB::table(self::$_moduleTable)
                ->select(self::$_moduleField)
                ->where( [ 'parent_module_id' => $parentModuleId ] )
                ->get();
        }

        return false;
    }


    public function getTotalChildModule($parentModuleId)
    {

        if ($parentModuleId) {
            $aResult    = DB::table(self::$_moduleTable)
                ->select(DB::raw('count(user_id) as total'))
                ->where( [ 'parent_module_id' => $parentModuleId ] )
                ->first();

            if ($aResult) {
                return $aResult->total;
            }
        }

        return false;
    }

    /**
     * @param     $where
     * @param int $offset
     * @param int $limit
     * @param     $aOrder
     */
    public function getModuleLists($where, $offset=0, $limit = 50, $aOrder)
    {
        $oQuery     = DB::table(self::$_moduleTable)
            ->select(self::$_moduleField)
            ->where( $where );
        if ($aOrder) {
            foreach ($aOrder as $field=>$order) {
                $oQuery->orderBy($field,$order);
            }
        }

        $oQuery->offset($offset)
            ->limit($limit)
            ->get();
    }

    /**
     * @param $data
     * @return bool
     */
    public function addPermissionContainer($data)
    {

        if (is_array($data) && count($data) > 0) {
            //$insertId   = DB::table(self::$_groupModuleTable)->insertGetId($data);
            //return $insertId;
            return DB::table('users')->insert($data);
        }

        return false;
    }

    /**
     * 修改权限
     * @param $groupId int
     * @param $data array
     * @return boolean
     */
    public function updatePermissionContainer($groupId, $data=array())
    {
        if (empty($groupId) || empty($data)) {
            return false;
        }

        return DB::table(self::$_groupModuleTable)
            ->where( [ 'group_id' => $groupId ] )
            ->update($data);
    }

    /**
     * 删除一个组权限
     * @param $groupId
     * @return mixed
     */
    public function removePermissionContainer($groupId) {
        return DB::table(self::$_groupModuleTable)->where( ['group_id' => $groupId] )->delete();
    }

    /**
     * 删除权限
     * @param $aModuleId
     * @return mixed
     */
    public function removePermissionModule($aModuleId) {
        return DB::table(self::$_groupModuleTable)->whereIn('module_id',$aModuleId )->delete();
    }

    /**
     * @param $groupId
     * @return bool
     */
    public function getGroupPermissionContainer($groupId) {
        if ($groupId) {
            return DB::table(self::$_groupModuleTable)
                ->select(self::$_groupModuleField)
                ->where( [ 'group_id' => $groupId ] )
                ->get();
        }

        return false;
    }

    /**
     * @param $userId
     * @param $groupId
     * @return bool
     */
    public function getGroupPermissionModule($userId = null, $groupId = null)
    {
        if(empty($userId) && empty($groupId)) {
            return false;
        }

        $oQuery         = DB::table(self::$_groupModuleTable. ' as gm')
            ->leftJoin(self::$_userGroupTable.' as u ', 'u.group_id', '=', 'gm.group_id')
            ->leftJoin(self::$_moduleTable.' as m ', 'gm.module_id', '=', 'm.module_id')
            ->select(
                'm.module_id as module_id',
                'm.module_name as module_name',
                'm.module_content as module_content',
                'm.parent_module_id as parent_module_id',
                'm.module_route as module_route',
                'm.module_level as module_level',
                'gm.group_id as group_id',
                'u.user_id as user_id',
                'gm.permission_container as permission_container'
            );

        if (!empty($userId)) {
            $oQuery->where('u.user_id',$userId);
        }
        if (!empty($groupId)) {
            $oQuery->where('gm.group_id',$groupId);
        }

        $userModuleSet   = $oQuery->orderBy('m.module_weight','desc')->get();
        return $userModuleSet;
    }

    /**
     * 获取某一分组的权限子节点
     * @param $groupId
     * @param $parentModuleId
     * @return bool
     */
    public function getGroupPermissionSonModule($groupId, $parentModuleId)
    {
        if(empty($moduleId) && empty($groupId)) {
            return false;
        }

        $oQuery         = DB::table(self::$_groupModuleTable. ' as gm')
            ->leftJoin(self::$_moduleTable.' as m ', 'gm.module_id', '=', 'm.module_id')
            ->select(
                'm.module_id as module_id',
                'm.module_name as module_name',
                'm.module_content as module_content',
                'm.parent_module_id as parent_module_id',
                'm.module_route as module_route',
                'm.module_level as module_level',
                'gm.group_id as group_id',
                'gm.permission_container as permission_container'
            );

        if (!empty($parentModuleId)) {
            $oQuery->where('m.parent_module_id',$parentModuleId);
        }
        if (!empty($groupId)) {
            $oQuery->where('gm.group_id',$groupId);
        }

        $userModuleSet   = $oQuery->orderBy('m.module_weight','desc')->get();
        return $userModuleSet;
    }
}