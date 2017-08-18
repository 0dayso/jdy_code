<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 上午11:22
 */

namespace App\Http\Logics\Order;

use App\Http\Logics\Logic;

use App\Http\Models\Order\OrderListsModel;

use App\Tools\ToolMoney;

use App\Http\Dbs\OrderDb;
/**
 * 订单 记录
 * Class OrderListsLogic
 * @package App\Http\Logics\Fund
 */
class OrderListsLogic extends Logic
{

    protected $orderListsModel = null;

    public function __construct(){
        $this->orderListsModel = new OrderListsModel;
    }
    /**
     * 获取订单列表
     * @param array $data
     * @return array
     */
    protected static function getList($data = []){
        $coreRequestData = [];

        $coreRequestData['userId']     = isset($data['user_id']) ? $data['user_id'] : null;         // 用户ID
        $coreRequestData['type']       = isset($data['type']) ? $data['type'] : null;             // 类型
        $coreRequestData['page']       = isset($data['page']) ? $data['page'] : 1;                // 当前页码
        $coreRequestData['size']       = isset($data['size']) ? $data['size'] : 20;               // 每页数量

        $coreRequestData['start_time'] = isset($data['start_time']) ? $data['start_time'] : null; // 时间
        $coreRequestData['ent_time']   = isset($data['ent_time']) ? $data['ent_time'] : null;     // 时间

        return self::getCoreFundHistoryList($coreRequestData, new OrderListsModel);
    }
    /**
     * 获取内核订单数据
     *
     * @param array $coreRequestData
     * @return array
     */
    public static function getCoreFundHistoryList($coreRequestData = [], OrderListsModel $orderListsModel){
        return $orderListsModel->getCoreHistoryFormData($coreRequestData);
    }


    /**
     * 格式化金额 分转元
     *
     * @param array $data
     * @return array
     */
    public static function formatGetListOutput($data = []){
        $data       = self::getList($data);

        if(isset($data['data']) && !empty($data['data'])){
            $extends = $banks = [];
            if(!empty($data['bank'])){
                $banks = array_column($data['bank'], 'name', 'id');
            }
            if(!empty($data['extend'])){
                $extends = array_column($data['extend'], 'bank_id', 'order_id');
            }
            foreach($data['data'] as $key => $record){

                if( $record['status'] != 200 ){

                    unset($data['data'][$key]);

                    continue;

                }

                $record['note'] = $record['type'] == 1 ? '充值' : '提现';
                $record['balance_change'] = $record['type'] == 1 ? '+' . $record['cash']  : '-' . $record['cash'];

                if($extends && $banks) {
                    $bankId = isset($extends[$record['order_id']]) ? $extends[$record['order_id']] : 0;
                    $record['note_other'] = isset($banks[$bankId]) ? $banks[$bankId] : null;
                }
                $data['data'][$key] = $record;

            }
        }

        $data['status_note'] = OrderDb::getStatusData();

        $data['type_note'] = OrderDb::getTypeData();

        return self::callSuccess($data);
    }

}