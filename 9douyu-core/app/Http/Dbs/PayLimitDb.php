<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:27
 * Desc: 支付限额Db
 */

namespace App\Http\Dbs;


class PayLimitDb extends JdyDb{


    protected $table = "pay_limit";

    /*
     * 网银支付标识码 1000-1100
     * 快捷支付    1101-1200
     * 代扣 1201-1300
     * 提现 1400-1500
     */
    const

        STATUS_NORMAL = 1, //启用
        STATUS_FORBIDDEN = 0, //禁用

        //网银
        RECHARGE_CBPAY_TYPE         = 1000,//网银在线充值标记
        RECHARGE_REAPAY_TYPE        = 1001,//融宝网银充值标识
        //快捷
        RECHARGE_LLPAY_AUTH_TYPE    = 1101,//连连认证充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102,//易宝认证充值标记

        //代扣
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201,//钱袋宝代扣充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202,//联动优势充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203,//翼支付充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204,//融宝支付充值标记

        WITHDRAW_ORDER_TYPE         = 2000,//提现订单类型

        PAY_LIMIT = 100000000;//充值无限额的默认金额(单位：分)

    private $currentTime ; //当前时间
    private $dbPrefix ;    //表前缀

    public function __construct(){

        $this->currentTime = date('Y-m-d H:i:s');
        $this->dbPrefix = env('DB_PREFIX');

    }



    /**
     * @param $bankId
     * @return mixed
     * 获取某个银行可用的支付通道及限额信息(绑卡情况)
     * SQL: select pay_type,limit,day_limit,bank_id from core_pay_limit
     *  where status = 1 and bank_id = 6 and
     * (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     * 查询不在维护期、启用状态、固定银行的可用支付通道列表
     */
    public function getLimitByBank($bankId){


        $sql = "select pay_type,`limit`,day_limit,bank_id from ".$this->dbPrefix."pay_limit
                where bank_id = {$bankId} and
                status = ".self::STATUS_NORMAL." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);



    }


    /**
     * @param $bankId
     * @return mixed
     * 获取某个银行可用的支付通道及限额信息（未绑卡情况，不支持翼支付）
     * SQL: select pay_type,limit,day_limit,bank_id from core_pay_limit
     *  where status = 1 and bank_id = 6 and
     * pay_type <> 1203 and
     * (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     * 查询不在维护期、启用状态、不为翼支付、固定银行的可用支付通道列表
     *
     */
    public function getUnbindLimitByBank($bankId){


        $sql = "select pay_type,`limit`,day_limit,bank_id from ".$this->dbPrefix."pay_limit
                where bank_id = {$bankId} and
                status = ".self::STATUS_NORMAL." and
                pay_type <> ".self::RECHARGE_BEST_AUTH_TYPE." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);
    }


    /**
     * @return mixed
     * 获取所有银行可用的支付限额列表
     * SQL : select pay_type,`limit`,day_limit,bank_id from core_pay_limit
     *       where status = 1 and
     *       pay_type <> 1203 and
     *   (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     *  查询不在维护期内且状态为启用，不为翼支付的通道列表
     */
    public function getAllBankLimit(){


        $sql = "select pay_type,`limit`,day_limit,bank_id from ".$this->dbPrefix."pay_limit
                where status = ".self::STATUS_NORMAL." and
                pay_type <> ".self::RECHARGE_BEST_AUTH_TYPE." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);
    }
}