<?php

namespace App\Http\Models\Order;

use App\Http\Models\Model;

use App\Tools\ToolCurl;

use Config;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use Log;

use App\Tools\ToolMoney;

use App\Http\Models\Common\CoreApi\OrderModel;
/*
 * 订单记录
 */
class OrderListsModel extends Model{

    public static $codeArr            = [
        'getCoreOrderData' => 1,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_ORDER;

    /**
     * 获取核心历史数据列表
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getCoreOrderData($data = []){
        try {

            $data = OrderModel::getOrderList($data);

        }catch (\Exception $e){
            $data['data']           = $data;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'curl-Error', $data);

            throw new \Exception(LangModel::getLang('ERROR_ORDER_LIST_GET_FAIL') . 1, self::getFinalCode('getCoreOrderData'));
        }

        return $data;
    }

    /**
     * 金额转化【元 转 分】
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getCoreHistoryFormData($data = []){
        $data = $this->getCoreOrderData($data);
        if(isset($data['data']['data'])){
            $record = $data['data']['data'];
            if(!empty($record)){
                $data['data']['data'] = self::formatList($record);
            }
        }
        return $data;
    }

    /**
     * 格式化金额数据【元转分】
     */
    protected static function formatList($record){

        foreach($record as $k => $value){
            $record[$k]['cash']        = ToolMoney::formatDbCashAdd($value['cash']);
        }
        return $record;
    }

}