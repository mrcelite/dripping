<?php

namespace App\Http\Controllers\Manager;

use App\Services\Manager\PermissionService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redirect;

class InitController extends BaseController
{
    public $_userId             = null;
    public $_userName           = null;
    public $_realName           = null;
    public $_mobilePhone        = null;
    public $_userModule         = null;
    public $_userGroup          = null;
    public $oPermissionService  = null;

    public function __construct ()
    {
        $this->oPermissionService   = PermissionService::getInstance();
        self::initManager();
    }

    public function initManager()
    {

        $jsonManager = session('manager_session');
        if($jsonManager) {

            $oManager           = json_decode($jsonManager);

            $this->_userId      = $oManager->userId;
            $this->_userName    = $oManager->userName;
            $this->_realName    = $oManager->realName;
            $this->_mobilePhone = $oManager->mobilePhone;

            self::initUserPermissionModule();
            return $this;
        } else {
            return redirect('login/index');
        }
    }

    public function initUserPermissionModule() {
        if ($this->_userId) {
            $aModule                = $this->oPermissionService->getPermissionModuleTree($this->_userId);
            if ($aModule) {
                $this->_userModule  = $aModule;
            }
        }
    }

    /**
     * @param $moduleId
     * @param $operationPermission
     * @return bool
     */
    public function checkPermission($moduleId, $operationPermission)
    {
        if ($this->_userId && !empty($this->_userModule)) {
            if (array_key_exists($moduleId, $this->_userModule)) {
                if (array_key_exists('permission_container', $this->_userModule[$moduleId])) {
                    $container  = $this->_userModule[$moduleId]['permission_container'];
                    if ($container & $operationPermission) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
