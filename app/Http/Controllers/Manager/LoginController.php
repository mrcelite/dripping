<?php
namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\LogicException;
use App\Services\Manager\PermissionService;
use Illuminate\Routing\Controller as BaseController;

class LoginController extends BaseController
{

    public function index(Request $request)
    {
        $csrfToken  = $request->session()->token();
        return view('manager.login',
            [
                'title'     => '管理员登录',
                '_token'    => $csrfToken
            ]);
    }

    public function login(Request $request)
    {
        //setcookie('XDEBUG_SESSION', 'marongcai', 0x7fffffff, '/');
        $username           = $request->get('username');
        $password           = $request->get('password');
        if($username && $password) {

            $oPermission    = PermissionService::getInstance();
            $oUser          = $oPermission->getUserByName($username,$password);
            if ($oUser) {

                $aManager   = [
                    'userId'        => $oUser->user_id,
                    'userName'      => $oUser->user_name,
                    'realName'      => $oUser->real_name,
                    'mobilePhone'   => $oUser->mobile_phone
                ];

                $request->session()->put('manager_session', json_encode($aManager));
                return response()->json(['code'=>200,'msg'=> 'success']);
            }
        }

        return response()->json(['code'=>400,'msg'=> '用户名密码不能为空']);
    }
}