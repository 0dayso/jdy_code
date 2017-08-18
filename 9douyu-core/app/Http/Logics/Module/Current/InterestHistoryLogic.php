<?php
/**
 * Created by PhpStorm.
 * User: lgh－dev
 * Date: 16/11/25
 * Time: 15:08
 * Desc: 活期计息记录逻辑层
 */

namespace App\Http\Logics\Module\Current;


use App\Http\Logics\Logic;
use App\Http\Models\Current\CurrentInterestHistoryModel;

class InterestHistoryLogic extends Logic
{

    /**
     * @desc 获取活期计息所有列表【管理后台】
     * @param $params
     * @return array
     */
    public function getAdminFundHistoryListAll($params){

        $pageSize = $params['size'];

        $attributes = $this->formatSearchAttributes($params);

        try{
            $fundHistoryModel = new CurrentInterestHistoryModel();
            $adminFundHistoryList = $fundHistoryModel->getAdminFundHistoryListAll($pageSize, $attributes);

        }catch(\Exception $e){
            \Log::error(__METHOD__.'Error', $params);
            return $this->callError($e->getMessage(),$e->getCode());
        }

        return $this->callSuccess($adminFundHistoryList);
    }

    /**
     * @desc 格式化搜索的条件
     * @param $params
     * @return array
     */
    public function formatSearchAttributes($params){
        $attributes = [];
        //用户id
        if(!empty($params['user_id'])){
            $userId = $params['user_id'];
            $attributes['user_id'] = $userId;
        }
        //类型
        if(!empty($params['type'])){
            $type = $params['type'];
            $attributes['type'] = $type;
        }

        //时间区间
        if(!empty($params['startTime'])){
            $startTime = $params['startTime'];
            $attributes[]  = ['created_at','>=', $startTime];
        }
        if(!empty($params['endTime'])){
            $endTime = $params['endTime'];
            $attributes[]  = ['created_at','<=', $endTime." 23:59:59"];
        }

        return $attributes;
    }

}