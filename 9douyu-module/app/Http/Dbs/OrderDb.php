<?php
/**
 * User: caelyn
 * Date: 16/5/20
 * Time: 16:26
 * Desc: 订单Db基类
 */

namespace App\Http\Dbs;


class OrderDb extends JdyDb{

    protected $table = "order";

    const
        RECHARGE_CBPAY_ONLINE_TYPE         = 1000,//JD网银在线充值标记
        RECHARGE_REAPAY_ONLINE_TYPE        = 1001,//融宝网银充值标识
        RECHARGE_HNAPAY_ONLINE_TYPE        = 1002,//新生网银充值标记
        RECHARGE_SUMAAPAY_ONLINE_TYPE      = 1003,//丰付网银充值标记
        RECHARGE_LLPAY_AUTH_TYPE           = 1101,//连连认证充值标记
        RECHARGE_YEEPAY_AUTH_TYPE          = 1102,//易宝认证充值标记
        RECHARGE_BFPAY_AUTH_TYPE           = 1103,//宝付认证充值标记
        RECHARGE_UCFPAY_AUTH_TYPE          = 1104,//先锋认证充值标记
        RECHARGE_SUMAPAY_AUTH_TYPE         = 1105,//丰付认证充值标记
        RECHARGE_QDBPAY_WITHHOLD_TYPE      = 1201,//钱袋宝认证充值标记
        RECHARGE_UMPPAY_WITHHOLD_TYPE      = 1202,//联动优势充值标记
        RECHARGE_BESTPAY_WITHHOLD_TYPE     = 1203,//翼支付充值标记
        RECHARGE_REAPAY_WITHHOLD_TYPE      = 1204,//融宝支付充值标记
        RECHARGE_REAPAY_WITHHOLD_OTHER     = 1301,//老系统中未知的的通道标示

        RECHARGE_WITHDROW_TYPE             = 2000, //提现类型

        RECHARGE_APP_JUMP_UCF_TYPE         = 45,//先锋支付
        RECHARGE_APP_JUMP_BF_TYPE          = 44, //app支付跳转宝付的pay_type
        RECHARGE_APP_JUMP_WX_TYPE          = 43, //app支付跳转h5页的pay_type
        RECHARGE_APP_JUMP_LL_TYPE          = 41, //app支付跳转连连的pay_type


        TRADE_SUCCESS                      = 'success',        //支付成功
        TRADE_FAIL                         = 'fail',           //支付失败
        TRADE_WAITING                      = 'waiting',        //等待付款
        TRADE_DEALING                      = 'dealing',        //处理中
        TRADE_UNKNOW                       = 'unknow',         //未知状态

        STATUS_SUCCESS                     = 200,   //成功
        STATUS_ING                         = 300,   //等待处理
        STATUS_DEALING                     = 301,   //处理中
        STATUS_TIMEOUT                     = 401,   //超时取消
        STATUS_CACLE                       = 402,   //手动取消
        STATUS_ERROR                       = 500,   //订单失败

        
        INVALIED_ORDER_LIMIT               = 3,     //订单切换通道设置的失败次数

        RECHARGE_TYPE                      = 1,
        WITHDRAW_TYPE                      = 2,
        ALL_STATUS                         = 0,

        END                                = true;



//        APP_REQUEST_FROM_PC      = 1,//来源于pc
//        APP_REQUEST_FROM_WAP     = 2,//来源于wap
//        APP_REQUEST_FROM_IOS     = 3,//来源于ios
//        APP_REQUEST_FROM_ANDROID = 4;//来源于android


    /**
     * 状态获取
     *
     * @param int $status
     * @return string|array
     */
    public static function getStatusData($status = 0){
        $statusData = self::orderStatusList();

        if(isset($statusData[$status])){
            return $statusData[$status];
        }else{
            return $statusData;
        }
    }


    /**
     * @return array
     * 订单状态列表
     */
    public static function orderStatusList(){

        $statusData = [

            self::ALL_STATUS                         => '全部状态',
            self::STATUS_SUCCESS                     => '成功',
            self:: STATUS_ING                         => '等待处理',
            self:: STATUS_DEALING                     => '处理中',
            self:: STATUS_TIMEOUT                     => '超时取消',
            self:: STATUS_CACLE                       => '手动取消',
            self:: STATUS_ERROR                       => '订单失败',
        ];

        return $statusData;

    }

    /**
     * 获取充值类型
     *  todo 标签
     * @return array
     */
    public static function getTypeData(){
        return [
            self:: RECHARGE_CBPAY_ONLINE_TYPE          => '在线充值',//JD网银在线充值标记
            self:: RECHARGE_REAPAY_ONLINE_TYPE        => '在线充值',//融宝网银充值标识
            self:: RECHARGE_HNAPAY_ONLINE_TYPE        => '在线充值',//新生网银充值标记
            self:: RECHARGE_SUMAAPAY_ONLINE_TYPE      => '在线充值',//丰付网银充值标记

            self:: RECHARGE_LLPAY_AUTH_TYPE           => '认证充值',//连连认证充值标记
            self:: RECHARGE_YEEPAY_AUTH_TYPE          => '认证充值',//易宝认证充值标记
            self:: RECHARGE_BFPAY_AUTH_TYPE           => '认证充值',//宝付认证充值标记


            self:: RECHARGE_QDBPAY_WITHHOLD_TYPE      => 1201,//钱袋宝认证充值标记
            self:: RECHARGE_UMPPAY_WITHHOLD_TYPE      => 1202,//联动优势充值标记
            self:: RECHARGE_BESTPAY_WITHHOLD_TYPE     => 1203,//翼支付充值标记
            self:: RECHARGE_REAPAY_WITHHOLD_TYPE      => 1204,//融宝支付充值标记

            self:: RECHARGE_WITHDROW_TYPE             => 2000, //提现类型
        ];
    }


}
