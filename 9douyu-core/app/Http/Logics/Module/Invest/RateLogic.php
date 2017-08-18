<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/6
 * Time: 下午3:52
 * Desc: 投资使用加息券，相关逻辑
 */

namespace App\Http\Logics\Module\Invest;

use App\Http\Dbs\InvestDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\RefundLogic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Refund\ProjectModel;
use App\Tools\ToolMoney;
use Log;

class RateLogic extends Logic
{

    /**
     * @param $investId
     * @param $profit
     * @return array
     * @desc 创建加息利息
     */
    public function createBonusRateRecord($investId, $profit)
    {

        $investDb = new InvestDb();

        $refundDb = new RefundRecordDb();

        $projectModel = new ProjectModel();

        $projectDb = new ProjectDb();

        $investInfo = $investDb->getObj($investId);

        $projectInfo = $projectDb->getObj($investInfo->project_id);

        if($projectInfo->new){

            Log::Error("createBonusRateRecordError", ['msg' => '新定期满标后生成还款计划', 'invest_id' => $investId, 'profit' => $profit,'project'=>$projectInfo]);

            return self::callError('新定期满标后生成还款计划');

        }

        $rateRecord = $refundDb->getRateInfoByInvestId($investId);

        if( $rateRecord ){

            Log::Error(__METHOD__."Error", ['msg' => '记录已存在', 'invest_id' => $investId, 'profit' => $profit]);

            return self::callError('记录已存在');

        }

        $record = $this->getRateRecord($investId, $profit);

        if( empty($record) ){

            return self::callError('记录为空');

        }

        try{

            $projectModel->createRefundList($record);

            $return = self::callSuccess();

        }catch (\Exception $e){

            $return = self::callError($e->getMessage());

            $log = [
                'invest_id'     => $investId,
                'profit'        => $profit,
                'msg'           => $return['msg']
            ];

            RefundLogic::createBonusRateRecordWarning($log);

        }

        return $return;

    }

    /**
     * @param $investId
     * @param $projectId
     * @param $profit
     * @return mixed
     * @desc 获取加息券投资所生成的回款记录
     */
    public function getRateRecord($investId, $profit)
    {

        $incomeModel = new IncomeModel();

        return $incomeModel->getRateRecord($investId, $profit);

    }

    /**
     * @param $projectId
     * @param $cash
     * @param $profit
     * @return array
     * @desc 获取预期收益/加息券
     */
    public function getPlanInterest($projectId, $cash, $profit)
    {

        $incomeModel = new IncomeModel();

        try {

            $records = $incomeModel->getPlanInterest($projectId, $cash, $profit);

            $cashInterest = 0;

            $rateInterest = 0;

            foreach ($records['cash_record'] as $cashVal) {

                $cashInterest += $cashVal['interest'];

            }

            if (!empty($records['rate_record'])) {

                $rateInterest = $records['rate_record']['interest'];

            }

            $list = [
                'cash_interest' => ToolMoney::formatDbCashDelete($cashInterest),
                'rate_interest' => ToolMoney::formatDbCashDelete($rateInterest)
            ];

            return Logic::callSuccess($list);

        }catch (\Exception $e){

            \Log::Error(__METHOD__."Error", ['msg' => $e->getMessage(), 'projectId' => $projectId, 'cash' => $cash, 'profit' => $profit]);

            return self::callError($e->getMessage());

        }

    }

}