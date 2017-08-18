<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午10:59
 * Desc: 零钱计划项目投资
 */

namespace App\Http\Logics\Invest;

use App\Http\Dbs\CurrentPrincipalDb;
use App\Http\Dbs\CurrentProjectDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\InvestLogic;
use App\Http\Models\Common\UserFundModel;
use App\Http\Models\Common\UserModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Invest\CurrentModel;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;

class CurrentLogic extends Logic{

    /**
     * @param $userId
     * @param $cash
     * @param $isAuto 判断是否是自动转入
     * @return array
     * @desc 零钱计划转入
     */
    public function invest($userId, $cash,$isAuto=false)
    {

        $return  = self::callError();

        $userFundModel = new UserFundModel();

        $userModel = new UserModel();

        $currentAccountModel = new AccountModel();

        try{

            self::beginTransaction();

            $note = '';

            //用户投资
            if(!$isAuto){

                ValidateModel::isUserId($userId);

                ValidateModel::isCash(ToolMoney::formatDbCashDelete($cash));

                //检测用户是否存在
                $userModel->checkUserExitsByUserId($userId);

                $eventId = FundHistoryDb::INVEST_CURRENT;

            }else{
                //判断用户今日回款是否已经自动投资过零钱计划
                $count = FundHistoryDb::getTodayAutoInvestRecord($userId);

                if($count > 0){

                    $log = [
                        'user_id'       => $userId,
                        'cash'          => $cash,
                        'msg'           => '用户今日回款已经投资零钱计划,请勿重复操作'
                    ];
                    Log::Error('AutoInvestCurrentError', $log);

                    return $return;
                }

                //自动回款转零钱计划
                $eventId = FundHistoryDb::INVEST_CURRENT_AUTO;

                $note = '回款自动投资零钱计划';
            }

            //资金流水扣除余额
            $userFundModel->decreaseUserBalance($userId, $cash, $eventId, $note);

            //增加零钱计划信息
            $currentAccountModel->doAdd($userId, $cash);

            self::commit();

            /*
            $return = [
                'status'    => true,
                'code'      => self::CODE_SUCCESS,
                'data'      => [
                    'user_id' => $userId,
                    'cash'    => $cash
                ],
                'msg'       => LangModel::SUCCESS_INVEST
            ];
            */
            $data =  [
                'user_id' => $userId,
                'cash'    => $cash
            ];
            $return = self::callSuccess($data);

            $log = [
                'user_id'       => $userId,
                'cash'          => $cash,
                'create_time'   => ToolTime::dbNow()
            ];

            Log::Info('InvestCurrentSuccess',$log);
            
        }catch (\Exception $e) {

            self::rollback();

            $return['msg'] = $e->getMessage();

            $log = [
                'user_id'       => $userId,
                'cash'          => $cash,
                'code'          => $e->getCode(),
                'msg'           => $e->getMessage()
            ];

            Log::Error('InvestCurrentError', $log);

            InvestLogic::investCurrentWarning(implode(',', $log));

        }

        return $return;

    }

    /**
     * @param $userId
     * @param $cash
     * @return array
     * @desc 零钱计划转出
     */
    public function investOut($userId, $cash)
    {

        $return  = self::callError();

        $userFundModel = new UserFundModel();

        $userModel = new UserModel();

        $currentAccountModel = new AccountModel();

        try{

            self::beginTransaction();

            ValidateModel::isUserId($userId);

            //ValidateModel::isCash($cash);
            //最少转出1分钱
            ValidateModel::isDecimalCash($cash);

            //检测用户是否存在
            $userModel->checkUserExitsByUserId($userId);

            //检测零钱计划账户余额是否充足
            $currentAccountModel->checkAccountBalance($userId,$cash);

            //增加账户金额
            $userFundModel->increaseUserBalance($userId, $cash, FundHistoryDb::INVEST_OUT_CURRENT);

            //减少零钱计划账户金额
            $currentAccountModel->decreaseUserCash($userId, $cash);

            self::commit();

            $return = [
                'status'    => true,
                'code'      => self::CODE_SUCCESS,
                'data'      => '',
                'msg'       => LangModel::SUCCESS_INVEST
            ];

            $log = [
                'user_id'       => $userId,
                'cash'          => $cash,
                'create_time'   => ToolTime::dbNow()
            ];

            Log::Info('InvestCurrentSuccess',$log);

        }catch (\Exception $e) {


            self::rollback();

            $return['msg'] = $e->getMessage();

            $log = [
                'user_id'       => $userId,
                'cash'          => $cash,
                'code'          => $e->getCode(),
                'msg'           => $e->getMessage()
            ];

            Log::Error('InvestCurrentError', $log);

        }

        return $return;

    }

    /**
     * @return bool
     * 生成活期用户今日零点账户金额
     */
    public function createPrincipal()
    {


        $db = new CurrentPrincipalDb();

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

        $fundDb = new FundHistoryDb();

        $changeData =  $fundDb->getTodayChangeCash();

        if($changeData){

            foreach($changeData as $val){

                $db->updateRecord($val['user_id'],$val['balance_change']);
            }
        }
    }


}
