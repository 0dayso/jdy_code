<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/18
 * Time: 13:46
 */

namespace App\Http\Models\Common\ServiceApi;

use App\Http\Models\Common\ServiceApiModel;
use Config;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolCurl;

class BankCardModel extends ServiceApiModel{


    /**
     * @param $cardNo
     * @return array
     * 根据银行卡号获取对应的信息
     */
    public static function getCardInfo($cardNo){

        /*todo 上线后使用此段代码*/

        $api  = Config::get('serviceApi.moduleBankCard.getCardInfo');

        $params = [
            'card_no'   => $cardNo,
        ];

        $return = HttpQuery::serverPostkb($api,$params);

        return $return;


        /*todo 本地测试使用些段代码,第三方接口存在IP白名单,上面的代码本地测试无法通过*/

        /*
        $api = 'http://api.9douyu.com/API/bank/fetchCardInfoByCardNo';

        $sign = md5($cardNo.'xuN1mFEI3viLXMg7');

        $params = [
            'sign'      => $sign,
            'card_no'   => $cardNo
        ];

        $result = json_decode(ToolCurl::curlPost($api, $params),true);

        return $result;
        */

    }

    /**
     * @param $params
     * @return null|void
     * 联动优势卡鉴权
     */
    public static function checkCardByUmp($params){

        $res = HttpQuery::serverPost('/recharge/index',$params);
        return $res;
    }



    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param $phone
     * @param $cvv2
     * @param $validthru
     * @return array
     */
    public static function checkCreditCard($cardNo,$name,$idCard,$phone,$cvv2,$validthru){

        $api  = Config::get('serviceApi.moduleBankCard.checkCreditCard');

        $params = [
            'card_no'   => $cardNo,
            'name'      => $name,
            'id_card'   => $idCard,
            'phone'     => $phone,
            'cvv2'      => $cvv2,
            'validthru' => $validthru,
        ];

        $return = HttpQuery::serverPost($api,$params);

        return $return;

    }


    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param $phone
     * @return array
     * 融宝储蓄卡鉴权接口
     */
    public static function checkDepositCard($cardNo,$name,$idCard,$phone = ''){

        $api  = Config::get('serviceApi.moduleBankCard.checkDepositCard');

        $params = [
            'card_no'   => $cardNo,
            'name'      => $name,
            'id_card'   => $idCard,
            'phone'     => $phone,
        ];

        $return = HttpQuery::serverPost($api,$params);

        return $return;

    }


    /**
     * @param $userId
     * @param $cardNo
     * @return null|void
     * 先锋支付银行卡解绑
     */
    public static function ucfUnbind($userId,$cardNo){

        $params = [
            'user_id'   => $userId,
            'card_no'   => $cardNo,
            'method'    => 'unbind',
            'driver'    => 'UCFAuth'
        ];
        $res = HttpQuery::serverPost('/recharge/index',$params);

        if($res['status'] && $res['data']){

            return $res['data'];
        }else{

            return [];
        }

    }
}