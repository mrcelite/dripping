<?php
namespace App\Http\Controllers\Home;

//setcookie('XDEBUG_SESSION', 'marongcai', 0x7fffffff, '/');
use App\UserMembershipCard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\LogicException;

class HomeController extends Controller
{

    public function index()
    {
        $oUserMembership    = new UserMembershipCard();
        $brandId            = 1;
        $aShopId            = [ 1,4,5,6,7,9,12,13,14,16,24,25,28 ];
        $offset             = 5;
        $limit              = 5;
        $aCardLists         = $oUserMembership->getShopInfo($brandId,$aShopId,$offset,$limit);
        //$aOrder             = $oUserMembership->getOrderInfo();
        //$cardInfo           = $oUserMembership->getOneUserCard( [ 'id' => $id ] );
        $aCardLists         = $oUserMembership->getUserCardLists([62,63,64,65]);
        //$cardInfo           = $oUserMembership->getOneUserCardByCardNumber('1183300687575560');
        return response()->json($aCardLists);
        return view('home.index',
            [
                'title'     => 'this is an example',
                //'cardInfo'  => $cardInfo,
                'cardLists' => $aCardLists
            ]);
    }

    public function show($id)
    {
        $oUserMembership    = new UserMembershipCard();
        $cardInfo           = $oUserMembership->getOneUserCard( [ 'id' => $id ] );
        return view('home.show',
            [
                'title'     => '卡详情页',
                'cardInfo'  => $cardInfo
            ]);
    }

    public function check()
    {
        DB::beginTransaction();
        try {
            $oUserMembership    = new UserMembershipCard();

            $where              = [ 'id' => 65 ];
            $data               = [ 'is_encrypt' => 1, 'encryption_code' => 654321];
            $oUserMembership->updateUserCard($where,$data);

            $aData              = [
                'card_number'       => '1183300687575360',
                'phone_number'      => '15012345678',
                'contact_name'      => 'mrc',
                'card_credit'       => 200,
                'card_balance'      => 100,
                'benefit_balance'   => 100,
                'discount'          => 5,
                'is_encrypt'        => 2,
                'contact_address'   => '北京市昌平区',
                'shop_id'           => 5,
                'fc_shipcard_id'    => 13,
                'payment_method'    => 1
            ];
            //$oUserMembership->addOneUserCard($aData);
            $affectRows = $oUserMembership->removeUserCard( [ 'id' => 81 ] );

            DB::commit();
        } catch (LogicException $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}