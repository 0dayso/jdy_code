<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/18
 * Time: 16:26
 */

namespace App\Http\Models\Bank;

use App\Http\Dbs\Bank\BankListDb;
use App\Http\Dbs\Bank\CardErrorLogDb;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Logics\BankCard\CardLogic;
use App\Http\Models\Model;
use App\Http\Models\Common\ServiceApi\BankCardModel;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\Bank\BankDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Http\Models\Pay\RechargeModel;
use Config;

class CardModel extends Model{


    public static $codeArr = [
        'checkCardHandle'               => 1,
        'checkCardHandleFailedMsg'      => 2,
        'getCardInfo'                   => 3,
        'checkInvalidPayTypeBankId'     => 4,
        'checkUserBankCard'             => 5,
        'checkCardByAdmin'              => 6,
        'checkCardByAdminFailedMsg'     => 7,
        'checkCardByRea'                => 8,

    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BANK_CARD;

    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param $isAmdin 是否后台验证,若是,不记录错误日志
     * 用户三要素验卡
     */
    public static function checkUserBankCard($cardNo,$name,$idCard){

        $db     = new CardErrorLogDb();

        $channel = CardErrorLogDb::UMPAY;
        $list = $db->getByThreeElements($idCard,$cardNo,$name,$channel);


        if($list){

            throw new \Exception($list['result_msg'], self::getFinalCode('checkUserBankCard'));

            /*
            $result = ToolArray::arrayToKey($list,'channel');

            //存在联动优势的错误记录
            if(isset($result[CardErrorLogDb::UMPAY])){

                throw new \Exception($result[$channel]['result_msg'], self::getFinalCode('checkUserBankCard'));
            }
            //存在融宝的错误记录
            if(isset($result[CardErrorLogDb::REAPAY])){

                $return = CardErrorLogDb::UMPAY;
            }
            */
        }

    }


    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param string $phone
     * @throws \Exception
     * 后台四要素鉴权
     */
    public static function checkCardByAdmin($cardNo,$name,$idCard,$phone=''){

        //融宝储蓄卡鉴权接口
        $checkResult = BankCardModel::checkDepositCard($cardNo,$name,$idCard,$phone);
        
        if(!$checkResult['status']){

            throw new \Exception($checkResult['msg'], self::getFinalCode('checkCardByAdmin'));
        }

        if($checkResult['data']['status'] == OrderDb::TRADE_FAIL){

            throw new \Exception(LangModel::getLang($checkResult['data']['msg']), self::getFinalCode('checkCardByAdminFailedMsg'));
        }
    }


    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param string $phone
     * @param bool $isAmdin
     * @throws \Exception
     * 融宝三要素验卡
     */
    public static function checkCardByRea($cardNo,$name,$idCard,$phone){

        /*
        $channel = CardErrorLogDb::REAPAY;
        //是否存在错误日志记录
        $db = new CardErrorLogDb();
        $result = $db->getByFourElements($idCard,$cardNo,$name,$channel);

        if($result){
            throw new \Exception(LangModel::getLang($result->result_msg), self::getFinalCode('checkCardByRea'));
        }
        */

        $channel = CardErrorLogDb::REAPAY;

        //融宝储蓄卡鉴权接口
        $checkResult = BankCardModel::checkDepositCard($cardNo,$name,$idCard);

        return self::checkCardHandle($cardNo,$name,$idCard,$phone,$channel,$checkResult);
    }


    /**
     * @param $cardNo
     * @param $name
     * @param $idCard
     * @param string $phone
     * @param bool $isAmdin
     * @throws \Exception
     * 联动优势验卡接口
     */
    public static function checkCardByUmp($cardNo,$name,$idCard,$phone){

        $channel = CardErrorLogDb::UMPAY;

        //融宝储蓄卡鉴权接口
        $param = [
            'method'        =>  'checkCard',
            'driver'        =>  'UmpWithholding',
            'card_no'       =>  $cardNo,
            'name'          =>  $name,
            'id_card'       =>  $idCard
        ];

        $checkResult = BankCardModel::checkCardByUmp($param);

        self::checkCardHandle($cardNo,$name,$idCard,$phone,$channel,$checkResult);

    }


    /**
     * @throws \Exception
     * 验卡结果处理
     */
    private static function checkCardHandle($cardNo,$name,$idCard,$phone,$channel,$checkResult){

        if(!$checkResult['status']){

            throw new \Exception($checkResult['msg'], self::getFinalCode('checkCardHandle'));
        }

        //联动优势记录所有的鉴权请求
        if($channel == CardErrorLogDb::UMPAY){
            $data = [
                'card_no'       => $cardNo,
                'name'          => $name,
                'id_card'       => $idCard,
                'register_phone'=> $phone,
                'channel'       => $channel,
            ];

            if($checkResult['data']['status'] == OrderDb::TRADE_FAIL){

                $data['status']         = CardErrorLogDb::STATUS_FAIL;
                $data['result_code']    = $checkResult['data']['result_code'];
                $data['result_msg']     = $checkResult['data']['msg'];

            }else{

                $data['status']         = CardErrorLogDb::STATUS_SUCCESS;
                $data['result_code']    = '0000';
                $data['result_msg']     = '鉴权成功';
            }
            $db = new CardErrorLogDb();
            $db->addRecord($data);

            if($checkResult['data']['status'] == OrderDb::TRADE_FAIL){

                throw new \Exception(LangModel::getLang($checkResult['data']['msg']), self::getFinalCode('checkCardHandleFailedMsg'));
            }

        }else{
            //融宝仅记录失败的请求
            if($checkResult['data']['status'] == OrderDb::TRADE_FAIL){

                $data = [
                    'card_no'       => $cardNo,
                    'name'          => $name,
                    'id_card'       => $idCard,
                    'register_phone'=> $phone,
                    'channel'       => $channel,
                    'result_code'   => $checkResult['data']['result_code'],
                    'result_msg'    => $checkResult['data']['msg']
                ];

                $db = new CardErrorLogDb();
                $db->addRecord($data);

                return false;

            }else{

                return true;
            }
        }


    }


    /**
     * @param $cardNo
     * @throws \Exception
     * 获取银行信息
     */
    public static function getCardInfo($cardNo){
        //融宝储蓄卡鉴权接口
        #TODO: linglu-从数据库中获取所属银行
        #$checkResult = BankCardModel::getCardInfo($cardNo);
        $clogic     = new CardLogic();
        $checkResult= $clogic->getCardInfoV2( $cardNo );

        if(!$checkResult['status']){

            throw new \Exception($checkResult['msg'], self::getFinalCode('getCardInfo'));

        }else{

            if($checkResult['data']['ret_code'] != '0000'){

                throw new \Exception($checkResult['data']['ret_msg'], self::getFinalCode('getCardInfo'));
            }else{

                //信用卡标识
                if($checkResult['data']['card_type'] == 1){

                    throw new \Exception('请不要绑定信用卡', self::getFinalCode('getCardInfo'));

                }
            }

        }
        

        $bankCode = $checkResult['data']['bank_code'];

        $bankCodeList = Config::get('bankcode.cardbin');

        $bankId = isset($bankCodeList[$bankCode]) ? $bankCodeList[$bankCode] : 0;

        return $bankId;
    }

    /**
     * @param $bankId
     * @throws \Exception
     * 检查银行是否有可用的支付通道
     */
    public static function checkInvalidPayTypeBankId($bankId){

        $db         = new PayLimitDb();
        $payLimit   = $db->getLimitByBank($bankId);

        if(!$payLimit){

            throw new \Exception(LangModel::getLang('ERROR_CARD_IS_NOT_SUPPORT'), self::getFinalCode('checkInvalidPayTypeBankId'));

        }

    }

    /**
     * 获取所有银行
     * @return array
     */
    public static function getBanks(){

        $bankDb = new BankDb();

        $res = $bankDb->getAllBank();

        if(empty($res)) return [];

        $res = ToolArray::arrayToKey($res);

        return $res;
    }

    /**
     * 获取bankname
     * @param  int $id
     * @return string
     */
    public static function getBankName($id){

        $bankDb = new BankDb();

        $res = $bankDb->getBankName($id);

        if(empty($res)) return [];

        return $res['name'];
    }

    /**
     * 获取bankCode
     * @param int $type
     * @param $id
     * @return string
     */
    public static function getBankCode($id, $type=BankListDb::RECHARGE_ONLINE_BANKING ){

        $bankListDb = new BankListDb();

        $res = $bankListDb->getAlias($type, $id);

        return empty($res['alias'])?'':$res['alias'];

    }

    /**
     * @desc    读取数据库的方式-根据银行卡获取所属银行
     * @param   $cardNo
     **/
    public static function getBankInfoByCardNo( $cardNo ){

        $verify_code = substr($cardNo, 0, 6);


        $result = \DB::table("bank_cardbin")
                    ->select("*")
                    ->where("verify_code",  $verify_code)
                    ->first();

        $result = ToolArray::objectToArray($result);

        return $result;
    }

    /**
     * @desc    完善bank_cardbin 数据表
     *
     **/
    public static function addBankCardBin( $data ){

        $result = \DB::table("bank_cardbin")->insert( $data );

        return $result;
    }

}