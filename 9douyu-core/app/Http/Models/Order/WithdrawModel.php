<?php
/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 11:36
 * Desc: 提现订单相关model层
 */
namespace App\Http\Models\Order;
use App\Http\Dbs\WithdrawOrderDb;
use App\Http\Dbs\WithdrawRecordDb;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\OrderDb;
use App\Http\Models\Common\UserFundModel;
use App\Http\Dbs\FundHistoryDb;

class WithdrawModel extends OrderModel{


    public static $codeArr = [
        'makeOrder'                 => 1,
        'succOrder'                 => 2,
        'failedOrder'               => 3,
        'cancelOrder'               => 4,
        'dealOrder'                 => 5,
        'canceldOrderStatusError'   => 6,
        'succOrderStatusError'      => 7,
        'failedOrderStatusError'    => 8,
        'cancelWithdraw'            => 9,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_WITHDRAW_ORDER;
    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * 创建充值订单
     */
    public static function makeOrder($userId,$cash,$orderId,$handingFee){


        //创建订单之前判断判断号是否存在 用户是否存在
        self::beforeInsert($userId,$orderId);

        //生成数据库订单数据
        $withdrawDb = new WithdrawOrderDb();

        $result = $withdrawDb->makeOrder($userId,$cash,$handingFee,$orderId);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_ORDER_ADD_FAILED'),self::getFinalCode('makeOrder'));
        }

        
    }


    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * @throws \Exception
     * 提现成功
     */
    public static function succOrder($orderId,$orderStatus,$orderType){

        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_SUCCESS,OrderDb::TYPE_WITHDRAW);

        //只能标识处理中的提现订单为成功
        if((int)$orderStatus !== OrderDb::STATUS_DEALING){

            throw new \Exception(LangModel::getLang('ERROR_UNDEAING_ORDER_CAN_NOT_SUCCESS'),self::getFinalCode('succOrderStatusError'));

        }

        $db = new WithdrawOrderDb();
        $result = $db->succOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('succOrder'));

        }
    }

    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * 将提现置为失败
     */
    public static function failedOrder($orderId,$orderStatus,$orderType){

        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_FAILED,OrderDb::TYPE_WITHDRAW);

        //只能标识处理中的提现订单为失败
        if((int)$orderStatus !== OrderDb::STATUS_DEALING){

            throw new \Exception(LangModel::getLang('ERROR_UNDEAING_ORDER_CAN_NOT_FAILED'),self::getFinalCode('failedOrderStatusError'));

        }

        $db = new WithdrawOrderDb();
        $result = $db->failedOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('failedOrder'));

        }

    }



    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * 取消提现
     */
    public static function canceldOrder($orderId,$orderStatus,$orderType){

        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_CANCLE,OrderDb::TYPE_WITHDRAW);

        //只能取消待处理的订单
        if((int)$orderStatus !== OrderDb::STATUS_ING){

            throw new \Exception(LangModel::getLang('ERROR_UNING_ORDER_CAN_NOT_CANCEL'),self::getFinalCode('canceldOrderStatusError'));

        }

        $db = new WithdrawOrderDb();
        $result = $db->cancelOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('canceldOrder'));
        }

    }

    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * 将提现状态个改为处理中
     */
    public static function submitToBank($orderId,$orderStatus,$orderType){

        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_DEALING,OrderDb::TYPE_WITHDRAW);

        $db = new WithdrawOrderDb();
        $result = $db->submitToBank($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('dealOrder'));

        }
    }

    /**
     * [所有提现订单]
     * @param [int] $[size] [分页数]
     * @return [array]
     */
    public static function getWithdrawOrders($size){

        $result = [];

        $db = new WithdrawOrderDb();

        $result = $db->getWithdrawOrders($size);

        return $result;

    }

    /**
     * @desc 获取提现统计数据
     * @author lgh
     * @param $where
     * @return mixed
     */
    public static function getWithdrawStatistics($where){

        $startTime = $where['start_time'];
        $endTime   = $where['end_time'];
        $appRequest   = $where['app_request'];
        $status   = $where['status'];
        $userId   = $where['userId'];

        $obj =OrderDb::join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->where('order.type',OrderDb::TYPE_WITHDRAW);
        if($status == OrderDb::STATUS_SUCCESS){
            // 时间范围
            if($startTime && $endTime){
                $obj = $obj->where('order.success_time', '>=', $startTime);
                $obj = $obj->where('order.success_time', '<=', $endTime);
            }elseif($startTime && !$endTime){
                $obj = $obj->where('order.success_time', '>=', $startTime);
            }elseif(!$startTime && $endTime){
                $obj = $obj->where('order.success_time', '<=', $endTime);
            }
        }else{
            // 时间范围
            if($startTime && $endTime){
                $obj = $obj->where('order.created_at', '>=', $startTime);
                $obj = $obj->where('order.created_at', '<=', $endTime);
            }elseif($startTime && !$endTime){
                $obj = $obj->where('order.created_at', '>=', $startTime);
            }elseif(!$startTime && $endTime){
                $obj = $obj->where('order.created_at', '<=', $endTime);
            }
        }
        //平台来源
        if($appRequest){
            $obj = $obj->where('order_extend.app_request', '=', $appRequest);
        }
        //状态
        if($status){
            $obj = $obj->where('order.status', '=', $status);
        }
        //用户ID
        if($userId){
            $obj = $obj->where('order.user_id', '=', $userId);
        }

        $data['cash'] = $obj->sum('order.cash');
        $data['withdrawNum'] = $obj->distinct()->count('order.user_id');

        return $data;
    }

    /**
     * @desc 获取时间段内提现大于5万用户的信息
     * @param $attribute
     * @return mixed
     */
    public function getWithdrawUserCashFive($attribute){

        $startTime = $attribute['start_time'];
        $endTime   = $attribute['end_time'];

        $withdrawOrderDb = new WithdrawOrderDb();

        $result = $withdrawOrderDb->getWithdrawUserCashFive($startTime, $endTime);

        return $result;
    }

    /**
     * @param $createdAt
     * @param $cash
     * @throws \Exception
     * @desc 取消提现
     */
    public static function cancelWithdraw($createdAt, $cash){

        $withdrawRecordDb = new WithdrawRecordDb();

        $result = $withdrawRecordDb->cancelWithdraw($createdAt, $cash);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('cancelWithdraw'));

        }

    }



    /**
     * @desc    丰付代付-银行对应关系
     *
     ***/
    public static function getSumaBank(){

        return [
            '1' =>[
                'bank_id'   => '1',
                'bank_name' => '中国工商银行',
                'bank_company'  => '中国工商银行',
                'bank_first'    => 'Z',
                'bank_code'     => 'ICBC',
            ],

            '2' =>[
                'bank_id'   => '2',
                'bank_name' => '中国农业银行',
                'bank_company'  => '中国农业银行股份有限公司',
                'bank_first'    => 'Z',
                'bank_code'     => 'ABC',
            ],
            '3' =>[
                'bank_id'   => '3',
                'bank_name' => '中国银行',
                'bank_company'  => '中国工商银行',
                'bank_first'    => 'Z',
                'bank_code'     => 'BOC',
            ],
            '4' =>[
                'bank_id'   => '4',
                'bank_name' => '中国建设银行',
                'bank_company'  => '中国建设银行股份有限公司',
                'bank_first'    => 'Z',
                'bank_code'     => 'CCB',
            ],
            '5' =>[
                'bank_id'   => '5',
                'bank_name' => '交通银行',
                'bank_company'  => '交通银行',
                'bank_first'    => 'J',
                'bank_code'     => 'COMM',
            ],
            '6' =>[
                'bank_id'   => '6',
                'bank_name' => '招商银行',
                'bank_company'  => '招商银行股份有限公司',
                'bank_first'    => 'Z',
                'bank_code'     => 'CMB',
            ],
            '7' =>[
                'bank_id'   => '7',
                'bank_name' => '上海浦东发展银行',
                'bank_company'  => '上海浦东发展银行',
                'bank_first'    => 'S',
                'bank_code'     => 'SPDB',
            ],
            '8' =>[
                'bank_id'   => '8',
                'bank_name' => '中国民生银行',
                'bank_company'  => '中国民生银行',
                'bank_first'    => 'Z',
                'bank_code'     => 'CMSB',
            ],
            '9' =>[
                'bank_id'   => '9',
                'bank_name' => '兴业银行',
                'bank_company'  => '兴业银行',
                'bank_first'    => 'X',
                'bank_code'     => 'CIB',
            ],
            '10' =>[
                'bank_id'   => '10',
                'bank_name' => '中国光大银行',
                'bank_company'  => '中国光大银行',
                'bank_first'    => 'Z',
                'bank_code'     => 'CEB',
            ],
            '11' =>[
                'bank_id'   => '11',
                'bank_name' => '北京银行',
                'bank_company'  => '北京银行',
                'bank_first'    => 'B',
                'bank_code'     => 'BJB',
            ],
            '12' =>[
                'bank_id'   => '12',
                'bank_name' => '广发银行',
                'bank_company'  => '广发银行股份有限公司',
                'bank_first'    => 'G',
                'bank_code'     => 'CGB',
            ],
            '13' =>[
                'bank_id'   => '13',
                'bank_name' => '中信银行',
                'bank_company'  => '中信银行股份有限公司',
                'bank_first'    => 'Z',
                'bank_code'     => 'CNCB',
            ],
            '14' =>[
                'bank_id'   => '14',
                'bank_name' => '中国邮政储蓄银行',
                'bank_company'  => '中国邮政储蓄银行有限责任公司',
                'bank_first'    => 'Z',
                'bank_code'     => 'PSBC',
            ],
            '15' =>[
                'bank_id'   => '15',
                'bank_name' => '华夏银行',
                'bank_company'  => '华夏银行股份有限公司',
                'bank_first'    => 'H',
                'bank_code'     => 'HXB',
            ],
            '16' =>[
                'bank_id'   => '16',
                'bank_name' => '上海银行',
                'bank_company'  => '上海银行',
                'bank_first'    => 'S',
                'bank_code'     => 'SHB',
            ],
            '17' =>[
                'bank_id'   => '17',
                'bank_name' => '平安银行',
                'bank_company'  => '平安银行（原深圳发展银行）',
                'bank_first'    => 'P',
                'bank_code'     => 'PAB',
            ],
            '18' =>[
                'bank_id'   => '18',
                'bank_name' => '深圳发展银行',
                'bank_company'  => '平安银行（原深圳发展银行）',
                'bank_first'    => 'P',
                'bank_code'     => 'PAB',
            ],
            '19' =>[
                'bank_id'   => '19',
                'bank_name' => '南京银行',
                'bank_company'  => '南京银行股份有限公司',
                'bank_first'    => 'N',
                'bank_code'     => 'NJCB',
            ],
            '20' =>[
                'bank_id'   => '20',
                'bank_name' => '杭州银行',
                'bank_company'  => '杭州银行股份有限公司',
                'bank_first'    => 'H',
                'bank_code'     => 'HZB',
            ],
            '21' =>[
                'bank_id'   => '21',
                'bank_name' => '宁波银行',
                'bank_company'  => '宁波银行股份有限公司',
                'bank_first'    => 'N',
                'bank_code'     => 'NBCB',
            ],
        ];
    }

}