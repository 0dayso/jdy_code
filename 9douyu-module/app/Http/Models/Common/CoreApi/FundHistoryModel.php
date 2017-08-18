<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:42
 * Desc: 核心资金流水操作调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use App\Http\Models\Common\HttpQuery;
use Config;
use App\Tools\ToolMoney;

class FundHistoryModel extends CoreApiModel{


    /**
     * @param $userId   用户ID    必填
     * @param $eventId  事件ID    必填
     * @param $page     页码      必填
     * @param $size     每页显示记录数 必填
     * @return array
     * 根据事件ID、用户ID、偏移量、每页条数 获取列表数据
     */
    public static function getFundList($data)
    {

        $api = Config::get('coreApi.moduleFundHistory.getList');

        $params = $data;

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            if($data['total'] > 0){

                foreach ($data['data'] as $k => $val) {

                    $data['data'][$k]['balance_before']      = ToolMoney::formatDbCashAdd($val['balance_before']);
                    $data['data'][$k]['balance_change']      = ToolMoney::formatDbCashAdd($val['balance_change']);
                    $data['data'][$k]['balance']             = ToolMoney::formatDbCashAdd($val['balance']);

                }

                return $data;
            }else{
                return [];
            }
        } else {
            return [];
        }

    }


    /**
     * @param $userId   用户ID    必填
     * @param $page     页码      必填
     * @param $size     每页显示记录数 必填
     * @return array
     * 根据用户ID、偏移量、每页条数 获取列表数据
     */
    public static function getCurrentFundList($userId,$page,$size)
    {

        $api = Config::get('coreApi.moduleFundHistory.getCurrentList');

        $params = [
            'user_id'    => $userId,
            'page'      => $page,
            'size'      => $size,
        ];

        $return = HttpQuery::corePost($api, $params);
        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            if($data['total'] > 0){

                foreach ($data['data'] as $k => $val) {

                    $data['data'][$k]['balance_before']      = ToolMoney::formatDbCashAdd($val['balance_before']);
                    $data['data'][$k]['balance_change']      = ToolMoney::formatDbCashAdd($val['balance_change']);
                    $data['data'][$k]['balance']             = ToolMoney::formatDbCashAdd($val['balance']);
                }

                return $data;
            }else{
                return [];
            }
        } else {
            return [];
        }

    }

    /**
     * @param string $date
     * @return array
     * @desc 根据事件类型分组进行数据统计
     */
    public static function getChangeCashGroupByEventId($date = '')
    {
        $coreApi    =   Config::get('coreApi.moduleFundHistory.getChangeCashGroupByEventId');

        $return     =   HttpQuery::corePost($coreApi,['date'=> $date]);

        if( $return['status'] == true && $return['code'] == 200){

            return $return['data'];
        }

        return [];
    }

}