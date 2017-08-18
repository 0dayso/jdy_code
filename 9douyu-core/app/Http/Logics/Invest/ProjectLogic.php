<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午10:59
 * Desc: 定期项目投资
 */

namespace App\Http\Logics\Invest;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\InvestExtendDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\TermPrincipalDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\InvestLogic;
use App\Http\Models\Common\UserFundModel;
use App\Http\Models\Common\UserModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Invest\InvestExtendModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Invest\ProjectModel;
use App\Jobs\Refund\ProjectJob;
use App\Lang\LangModel;
use Log;
use Queue;

class ProjectLogic extends Logic{

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $bonusCash
     * @param $bonusRate
     * @return array
     * @desc 投资
     */
    public function invest($userId, $projectId, $cash, $bonusCash, $bonusRate)
    {

        $return  = self::callError();

        $userFundModel = new UserFundModel();

        $projectModel = new ProjectModel();

        $investModel = new InvestModel();

        $userModel = new UserModel();

        $investExtendModel = new InvestExtendModel();

        $totalCash = $cash + $bonusCash;

        try{

            self::beginTransaction();

            ValidateModel::isUserId($userId);

            ValidateModel::isCash($totalCash);

            ValidateModel::isProjectId($projectId);

            //检测用户是否存在
            $userModel->checkUserExitsByUserId($userId);

            //检测项目可投
            $projectModel->checkCanInvest($projectId, $totalCash);

            //资金流水扣除余额
            $userFundModel->decreaseUserBalance($userId, $cash, FundHistoryDb::INVEST_PROJECT, '项目id-'.$projectId);

            //更新项目金额
            $projectModel->invest($projectId, $totalCash);

            //插入投资记录
            $investId = $investModel->add($projectId, $userId, $totalCash);

            if($bonusCash > 0 || $bonusRate > 0){

                //投资记录扩展
                if($bonusCash > 0){
                    $bonusType = InvestExtendDb::BONUS_TYPE_MONEY;
                    $bonusValue = $bonusCash;
                }else{
                    $bonusType = InvestExtendDb::BONUS_TYPE_RATE;
                    $bonusValue = empty($bonusRate)?0:$bonusRate;
                }

                $investExtendModel->add($investId, $bonusValue, $bonusType);

            }

            self::commit();

            $return = [
                'status'    => true,
                'code'      => self::CODE_SUCCESS,
                'data'      => [
                    'invest_id' => $investId
                ],
                'msg'       => LangModel::SUCCESS_INVEST
            ];

            $log = [
                'invest_id'     => $investId,
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $cash,
                'event_name'    => 'App\Events\Api\Invest\ProjectSuccessEvent',
            ];

            Log::Info('InvestProjectSuccess',$log);

            //触发事件
            \Event::fire('App\Events\Api\Invest\ProjectSuccessEvent', [$log]);

        }catch (\Exception $e) {

            self::rollback();

            $return['msg'] = $e->getMessage();

            $log = [
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $cash,
                'code'          => $e->getCode(),
                'msg'           => $e->getMessage()
            ];

            Log::Error('InvestProjectError', $log);

            InvestLogic::investProjectWarning(implode(',', $log));

        }

        return $return;

    }


    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $bonusCash
     * @param $bonusRate
     * @return array
     * @desc 零钱计划投资定期
     */
    public function investByCurrent($userId, $projectId, $cash, $bonusCash, $bonusRate)
    {

        $return  = self::callError();

        $userFundModel = new UserFundModel();

        $projectModel = new ProjectModel();

        $investModel = new InvestModel();

        $userModel = new UserModel();

        $investExtendModel = new InvestExtendModel();

        $currentAccountModel = new AccountModel();

        $totalCash = $cash + $bonusCash;

        try{

            self::beginTransaction();

            //数据检测
            ValidateModel::isUserId($userId);

            ValidateModel::isCash($totalCash);

            ValidateModel::isProjectId($projectId);

            //检测用户是否存在
            $userModel->checkUserExitsByUserId($userId);

            //检测项目可投
            $projectModel->checkCanInvest($projectId, $totalCash);

            /********************************************零钱转出检测***************************************************/
            //最少转出1分钱
            ValidateModel::isDecimalCash($cash);

            //检测零钱计划账户余额是否充足
            $currentAccountModel->checkAccountBalance($userId,$cash);

            //减少零钱计划账户金额
            $currentAccountModel->decreaseUserCash($userId, $cash);

            //增加账户金额
            $userFundModel->increaseUserBalance($userId, $cash, FundHistoryDb::INVEST_OUT_CURRENT);
            /********************************************零钱转出完毕***************************************************/
            //资金流水扣除余额
            $userFundModel->decreaseUserBalance($userId, $cash, FundHistoryDb::INVEST_PROJECT, '零钱直投');

            //更新项目金额
            $projectModel->invest($projectId, $totalCash);

            //插入投资记录
            $investId = $investModel->add($projectId, $userId, $totalCash);

            if($bonusCash > 0 || $bonusRate > 0){

                //投资记录扩展
                if($bonusCash > 0){
                    $bonusType = InvestExtendDb::BONUS_TYPE_MONEY;
                    $bonusValue = $bonusCash;
                }else{
                    $bonusType = InvestExtendDb::BONUS_TYPE_RATE;
                    $bonusValue = empty($bonusRate)?0:$bonusRate;
                }

                $investExtendModel->add($investId, $bonusValue, $bonusType);

            }

            self::commit();

            $return = [
                'status'    => true,
                'code'      => self::CODE_SUCCESS,
                'data'      => [
                    'invest_id' => $investId
                ],
                'msg'       => LangModel::SUCCESS_INVEST
            ];

            $log = [
                'invest_id'     => $investId,
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $cash,
                'event_name'    => 'App\Events\Api\Invest\ProjectSuccessEvent',
            ];

            Log::Info('InvestProjectSuccess',$log);

            //触发事件
            \Event::fire('App\Events\Api\Invest\ProjectSuccessEvent', [$log]);

        }catch (\Exception $e) {

            self::rollback();

            $return['msg'] = $e->getMessage();

            $log = [
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $cash,
                'code'          => $e->getCode(),
                'msg'           => $e->getMessage()
            ];

            Log::Error('InvestProjectError', $log);

            InvestLogic::investProjectWarning(implode(',', $log));

        }

        return $return;

    }

    public function createTermPrincipal(){
        
        $db = new TermPrincipalDb();

        $total = $db->getTotal();

        if ($total > 0) {

            try {

                $db->clearData();

            } catch (\Exception $e) {

                $log = [
                    'msg' => $e->getMessage(),
                    'title' => '清除数据失败',
                ];
                Log::Error(__METHOD__ . 'Error', $log);

                return false;
            }
        }
        try {

            $db->createData();

        } catch (\Exception $e) {

            $log = [
                'msg' => $e->getMessage(),
                'title' => '创建数据失败'
            ];
            Log::Error(__METHOD__ . 'Error', $log);

            return false;
        }

        
    }


}
