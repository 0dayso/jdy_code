<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 19:28
 * Desc: 充值限额相关model层
 */

namespace App\Http\Models\Pay;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\PayLimitModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolOrder;

class LimitModel extends Model{

    /**
     * @param $userId
     * @throws \Exception
     * 获取绑卡用户是否充值限额
     */
    public static function getBindUserLimit($userId,$bankId,$minRechargeCash,$version,$isApp){

        $maxCash = 0;
        $list = [];

        $limitArr = PayLimitModel::getLimitListByBankId($bankId,true,$version,$isApp);
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

                //渠道当前可充值金额为0 或者小于最少充值金额,则不显示
                if($tmpMaxCash == 0 || $tmpMaxCash < $minRechargeCash){
                    continue;
                }
                if($tmpMaxCash > $maxCash){
                    $maxCash = $tmpMaxCash;
                }

                $list[] = [
                    'limit' => number_format($limit),
                    'day_free_limit' => number_format($todayFreeCash),
                    'real_limit' => $tmpMaxCash,
                    'pay_type' => $payType
                ];
            }
        }

        return [
                    'maxCash' => $maxCash,
                    'list' => $list
                ];
    }

    /**
     * @return array
     * 未绑卡情况下获取所有银行对应的限额
     */
    public static function getBankLimit($version,$isApp){

        //获取所有可用的支付通道限额列表
        $limitDb = new PayLimitDb();
        $limitList = $limitDb->getAllBankLimit();

        $list = [];
        foreach ($limitList as $value) {

            $val        = (array)$value;

            //按版本号过滤支付渠道
            if(PayLimitModel::isFilter($version,$val['version'],$isApp)){

                continue;
            }

            $limit      = (int)$val['limit']; //单笔限额
            $dayLimit   = (int)$val['day_limit']; //单日限额

            $limit      = ($limit    === 0) ? PayLimitDb::PAY_LIMIT : $limit; //单笔限额
            $dayLimit   = ($dayLimit === 0) ? PayLimitDb::PAY_LIMIT : $dayLimit;//单日限额

            $usableLimit = min($limit, $dayLimit); //单笔、单日限额取较小值 并转换成元为单位
            $bankId = $val['bank_id']; //银行ID

            if (isset($list[$bankId])) {
                if ($list[$bankId]['cash'] <= $usableLimit) {
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