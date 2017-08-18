<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/10/24
 * Time: 下午8:03
 */

namespace App\Http\Logics\Project;

use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Refund\ProjectLogic;
use App\Http\Models\Project\CreditAssignModel;
use App\Http\Models\Project\ProjectModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Log;

class BeforeRefundRecordLogic extends Logic
{

    /**
     * @param $projectIds
     * @return array
     * @desc 提前还款
     */
    public function beforeRefundRecord( $projectIds ){

        $refundRecordDb = new RefundRecordDb();

        $list = $refundRecordDb->getNeedBeforeRefundListByProjectIds($projectIds);

        if( empty($list) ){

            Log::Error(__METHOD__.'ErrorData', [$projectIds]);

        }

        $beforeProjectIds = ToolArray::arrayToIds($list, 'project_id');

        self::beginTransaction();

        $today = ToolTime::dbDate();

        $projectModel = new ProjectModel();

        $refundProjectModel = new \App\Http\Models\Refund\ProjectModel();

        try{

            $projectDb = new ProjectDb();

            //调用拆分回款数据
            foreach ( $list as $item ){

                $projectId = $item->project_id;

                $projectInfo[$projectId] = empty($projectInfo[$projectId]) ? $projectDb->getInfoById($projectId) : $projectInfo[$projectId];

                $refundProjectModel->beforeChangeRecord($item->invest_id, $item->invest_time,$projectInfo[$projectId]['refund_type']);

            }

            $creditAssignModel = new CreditAssignModel();
            //更新债转原项目为提前还款项目的完结日,正在债转的项目状态更新为取消
            $creditAssignModel->cancelByProjectIds( $beforeProjectIds );

            //更新项目的完结日为今日,并标记项目提前还款
            $projectModel->updateProjectBeforeRefund($beforeProjectIds, $today);

            self::commit();

            //调用拆分回款
            $refundLogic = new ProjectLogic();

            $refundLogic->splitRefund();
            
            
        }catch ( \Exception $e ){

            self::rollback();

            Log::Error(__METHOD__.'ErrorData', [$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess(true);

    }

}