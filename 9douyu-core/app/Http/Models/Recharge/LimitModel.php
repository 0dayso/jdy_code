<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 19:28
 * Desc: 充值限额相关model层
 */

namespace App\Http\Models\Recharge;
use App\Http\Dbs\AuthCardDb;
use App\Http\Dbs\PayLimitDb;
use App\Http\Dbs\UserPayListDb;
use App\Http\Models\Common\PayLimitModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class LimitModel extends Model{

    public static $codeArr = [
        'getBindLimitByUser'          => 1,
        'getBindLimitByUserExceed'    => 2

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_LIMIT;


    /**
     * @param $userId
     * @throws \Exception
     * 获取绑卡用户是否充值限额
     */
    public static function getBindUserLimit($userId){

        $maxCash = 0;

        //判断用户是否已绑卡,未绑卡直接抛异常
        $authCard = self::getAuthCard($userId);

        if(empty($authCard)){

            throw new \Exception(LangModel::getLang('ERROR_USER_UNBIND_CARD'),self::getFinalCode('getBindLimitByUser'));

        }


        //有绑卡信息，直接查询当前银行的限额信息
        $bankId = $authCard['bank_id'];

        $limitArr = PayLimitModel::getLimitListByBankId($bankId);
        if(!empty($limitArr)){
            //获取用户成功充值记录列表
            //$userList = $this->getUserRechargedList($userId);
            $userList = PayLimitModel::getUserRechargedList($userId,$bankId);
            //计算最大可充值金额
            foreach($limitArr as $payType => $val){

                $limit = (int)$val['limit'];//单笔限额
                $dayLimit = (int)$val['day_limit'];//单日限额

                if(isset($userList[$payType])){

                    $dayUsableCash = $dayLimit - $userList[$payType];//今日剩余可充值金额
                    $todayFreeCash = max((int)$dayUsableCash,0) ;

                }else{
                    $todayFreeCash = $dayLimit;
                }
                $tmpMaxCash = min($limit,$todayFreeCash); //单笔、今日剩余可充值金额取较小值
                if($tmpMaxCash > $maxCash){
                    $maxCash = $tmpMaxCash;
                }
            }
        }
        
        return $maxCash;
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户绑定卡信息
     */
    private static function getAuthCard($userId){
        //判断是否有绑卡
        $authDb     = new AuthCardDb();
        $authCard = $authDb->getAuthCardByUserId($userId);

        return $authCard;
    }
    /**
     * @return array
     * 未绑卡情况下获取所有银行对应的限额
     */
    public static function getBankLimit($userId){

        //绑卡的情况下不能直接调用此接口
        $authCard = self::getAuthCard($userId);

        if($authCard){
            throw new \Exception(LangModel::getLang('ERROR_USER_BIND_CARD'),self::getFinalCode('getBankLimit'));

        }

        //获取所有可用的支付通道限额列表
        $limitDb = new PayLimitDb();
        $limitList = $limitDb->getAllBankLimit();

        $list = [];
        foreach ($limitList as $value) {

            $val        = (array)$value;

            $limit      = (int)$val['limit']; //单笔限额
            $dayLimit   = (int)$val['day_limit']; //单日限额

            $limit      = ($limit    === 0) ? PayLimitDb::PAY_LIMIT : $limit; //单笔限额
            $dayLimit   = ($dayLimit === 0) ? PayLimitDb::PAY_LIMIT : $dayLimit;//单日限额

            $usableLimit = min($limit, $dayLimit); //单笔、单日限额取较小值 并转换成元为单位
            $bankId = $val['bank_id']; //银行ID

            if (isset($list[$bankId])) {
                if ($list[$bankId]['cash'] < $usableLimit) {
                    $list[$bankId] = [
                        'bank_id' => $bankId,
                        'cash' => $usableLimit, //金额转化成元
                    ];
                }
            } else {
                $list[$bankId] = [
                    'bank_id' => $bankId,
                    'cash' => $usableLimit  //金额转化成元
                ];
            }

        }

        return $list;
    }

}