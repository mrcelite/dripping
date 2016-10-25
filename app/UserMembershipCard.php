<?php
namespace App;

use App\Model\DBModel;
use Illuminate\Support\Facades\DB;
class UserMembershipCard extends DBModel
{
    public $_userMembershipCard = 'user_membership_card';
    public $_cardLibrary        = 'membership_card_library';
    public $_shopTable          = 'shop';
    public $_brandShopTable     = 'business_brand_shop';
    public $_brandTable         = 'business_brand';

    /**
     * 根据卡号获取会员卡信息
     * @param $cardNumber
     * @return mixed
     */
    public function getOneUserCardByCardNumber($cardNumber) {
        $cardInfo   = DB::table($this->_userMembershipCard)
            ->select(DB::raw('id,card_number,phone_number,contact_name,card_credit,card_balance,benefit_balance,shop_id'))
            ->where('card_number','=',$cardNumber)->get();
        return $cardInfo;
    }

    /**
     * 根据指定条件获取一张卡信息
     * @param $where
     * @return mixed
     */
    public function getOneUserCard($where) {
        $cardInfo   = DB::table($this->_userMembershipCard)
            ->select(DB::raw('id,card_number,phone_number,contact_name,card_credit,card_balance,benefit_balance,shop_id'))
            ->where($where)->first();
        return $cardInfo;
    }

    /**
     * 修改会员卡信息
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateUserCard($where,$data) {
        return DB::table($this->_userMembershipCard)
            ->where($where)
            ->update($data);
    }

    /**
     * 添加一条会员卡信息
     * @param $data
     * @return mixed
     */
    public function addOneUserCard($data) {
        $insertId   = DB::table($this->_userMembershipCard)->insertGetId($data);
        return $insertId;
    }

    /**
     * @param $where
     * @return mixed
     */
    public function removeUserCard($where) {
        return DB::table($this->_userMembershipCard)->where($where)->delete();
    }

    /**
     * 获取会员卡列表
     * @param $aId
     * @return mixed
     */
    public function getUserCardLists($aId) {
        $cardInfo   = DB::table($this->_userMembershipCard)
            ->select(DB::raw('id,card_number,phone_number,contact_name,card_credit,card_balance,benefit_balance,shop_id'))
            ->whereIn('id',$aId)->get();
        return $cardInfo;
    }

    /**
     * 列名重命名的demo
     * @param $where
     * @return mixed
     */
    public function getCardInfoDemoAs($where) {
        $aCard      = DB::table($this->_userMembershipCard)
            ->select('card_number', 'shop_id as shopId')->get($where);
        return $aCard;
    }

    /**
     * 表达式追加demo
     * @param $where
     * @return mixed
     */
    public function getCardInfoDemoAppend($where) {
        $oQuery     = DB::table($this->_userMembershipCard)->select('card_number');
        $aCard      = $oQuery->addSelect('shop_id')->get();
        return $aCard;
    }

    /**
     * 原生表达式demo
     * @return bool
     */
    public function getCardInfoDemoNative() {
        $aCard      = DB::table($this->_userMembershipCard)
            ->select(DB::raw('count(*) as total, card_number'))
            ->where('shop_id', '<>', 1)
            ->groupBy('shop_id')
            ->get();
        return $aCard;
    }

    /**
     * 左连接,复杂条件查询Demo
     * @param      $brandId
     * @param null $aShopId
     * @return mixed
     */
    public function getShopInfo($brandId,$aShopId = null, $offset = 0, $limit = 10) {
        $oQuery     = DB::table($this->_brandShopTable)
            ->leftJoin($this->_brandTable.' as b ', 'b.id', '=', 'business_brand_shop.b_brand_id')
            ->leftJoin($this->_shopTable.' as s ', 'business_brand_shop.shop_id', '=', 's.id')
            ->select('b.name as brand_name', 's.name as shop_name', 's.id as shop_id','b.id as brand_id')
            ->where('business_brand_shop.b_brand_id',$brandId);

        if($aShopId) {
            $oQuery->whereIn('s.id',$aShopId);
        }
        $oQuery->where('b.status',1)->where('b.run_type',1);
        $cardInfo   = $oQuery->offset($offset)
            ->limit($limit)
            ->get();
        return $cardInfo;
    }

    /**
     * @return mixed
     */
    public function getBrandList() {
        $aBrand     = DB::table($this->_brandShopTable)
            ->orderBy('addtime', 'desc')
            ->get();
        return $aBrand;
    }

    /**
     * @return mixed
     */
    public function getBrandInfo() {
        $aBrand     = DB::table($this->_brandShopTable)
            ->groupBy('shop_id')
            ->having('shop_id', '>', 10)
            ->get();
        return $aBrand;
    }

    /**
     * @return mixed
     */
    public function getOrderInfo() {
        //DB::enableQueryLog();
        $aOrder     = DB::table('order')
            ->select('b_brand_id','shop_id', DB::raw('SUM(chengjiaojiage) as totalPrice'))
            ->groupBy('b_brand_id')
            ->havingRaw('totalPrice > 15')
            ->get('id','<=',1000);
        //$queries = DB::getQueryLog();
        //$last_query = end($queries);
        return $aOrder;
    }


    public function getModuleLists($where = null, $offset=0, $limit = 50, $order)
    {
        return DB::table('membership_card_library')
            ->select('id','card_identify_code')
            ->orderBy('id','desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}