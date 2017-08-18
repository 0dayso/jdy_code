<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/13
 * Time: 17:11
 * Desc: 零钱计划加息券计息相关逻辑
 */
namespace App\Http\Logics\Module\Current;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\CurrentInterestHistoryDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\CurrentLogic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Current\InterestHistoryModel;
use App\Jobs\Current\BonusInterestAccrualJob;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Queue;
use Log;

class BonusLogic extends Logic{


    /**
     * @param $interestList
     * @return array
     * 零钱计划加息券计息数据入队列
     */
    public function saveInterestUserList($interestList){

        //直接入队列,加快响应速度

        $result = Queue::pushOn('bonusInterestAccrualJob',new BonusInterestAccrualJob($interestList));
        //入队列成功
        if($result){
            return self::callSuccess([]);
        }

        return self::callError('零钱计划加息券计息失败');

    }

    /**
     * @param $data
     * 零钱计划加息券批量计息核心业务逻辑
     */
    public function batchInterestAccrual($data){

        //数据格式化处理
        $interestList  = json_decode($data,true);
        //数据格式错误
        if(empty($interestList) || !is_array($interestList)){

            $data['msg']    = '无效的数据';
            Log::error(__METHOD__.'Error',[$data]);

            exit('非法的数据,程序退出');
        }

        $userIds        = ToolArray::arrayToIds($interestList,'user_id');

        $return         = $this->getCurrentData($userIds);

        $userList       = $return['user_list'];     //零钱计划账户信息
        $fundList       = $return['fund_list'];     //资金流水信息
        $bonusList      = $return['interest_list']; //加息券计息信息

        $today      = ToolTime::dbDate();

        $list = [];

        foreach($interestList as $val){

            $userId = $val['user_id'];

            //昨日加息券已计息过,直接跳过
            if(isset($bonusList[$userId])){

                continue;
            }
            $rate           = $val['rate'];                 //加息券利率

            //用户不存在,直接跳过
            if(!isset($userList[$userId])){

                continue;
            }
            $currentCash    = $userList[$userId]['cash'];   //零钱计划当前账户金额
            $interestAt     = $userList[$userId]['interested_at'];//零钱计划基准利息计息时间
            //计息时间大于今天0点,则需要减掉昨日的利息
            if($interestAt > $today){
                $currentCash -= $userList[$userId]['yesterday_interest'];
            }

            //零钱计划资金流水变更恢复到0点
            if(isset($fundList[$userId])){
                $currentCash += $fundList[$userId]['balance_change'];
            }
            //计息大于1分钱的最小本金,小于此金额不计息
            $minCash = IncomeModel::getCurrentInterestMinCash($rate);

            if($currentCash >= $minCash){
                //零钱计划加息券利息
                $interest = IncomeModel::getCurrentInterest($currentCash,$rate);

                $list[] = [
                    'user_id'   => $userId,
                    'interest'  => $interest,
                    'principal' => $currentCash,
                    'rate'      => $rate
                ];

            }else{
                continue;
            }

        }
        //批量添加零钱计划加息券计息记录
        $this->addBonusInterest($list);
    }

    /**
     * @param $userIds
     * @return array
     * 获取零钱计划计息需要统计的数据
     */
    private function getCurrentData($userIds){
        
        //获取多个用户零钱计划帐户金额
        $currentDb      = new CurrentAccountDb();
        $userData       = $currentDb->getByUserIds($userIds);

        $userList       = ToolArray::arrayToKey($userData,'user_id');
        //获取零钱计划资金变化情况
        $fundDb         = new FundHistoryDb();
        $funddata       = $fundDb->getCurrentCashChangeByUserIds($userIds);

        $fundList       = ToolArray::arrayToKey($funddata,'user_id');
        
        //获取多个用户零钱计划加息券利息数据
        $interestDb     = new CurrentInterestHistoryDb();
        $interestData   = $interestDb->getBonusListByUserIds($userIds);
        $interestList   = ToolArray::arrayToKey($interestData,'user_id');

        return [
            'user_list'     => $userList,
            'fund_list'     => $fundList,
            'interest_list' => $interestList
        ];
    }

    /**
     * 添加零钱计划加息券利息数据
     */
    private function addBonusInterest($list){

        if($list){

            $date = date('Y-m-d',strtotime('-1 day'));
            //利息类型 加息券利息
            $type = CurrentInterestHistoryDb::INTEREST_TYPE_BONUS;

            $interestModel  = new InterestHistoryModel();
            $accountModel   = new AccountModel();

            foreach($list as $val){

                $userId     = $val['user_id'];
                $interest   = $val['interest'];
                //数据组装
                $data = [
                    'user_id'           => $userId,
                    'rate'              => $val['rate'],
                    'interest'          => $interest,
                    'principal'         => $val['principal'],
                    'interest_date'     => $date,
                    'type'              => $type
                ];
        
                try{

                    self::beginTransaction();
                    //添加零钱计划加息券利息记录
                    $interestModel->addInfo($data);
                    //更新零钱计划账户金额
                    $accountModel->updateBonusInterest($userId,$interest);

                    self::commit();

                    $data['msg']    = '用户零钱计划加息券计息成功';

                    Log::info(__METHOD__.'Success',$data);

                }catch (\Exception $e){

                    self::rollback();

                    $data['msg']     = $e->getMessage();

                    Log::error(__METHOD__.'Error',$data);

                    CurrentLogic::addBonusInterest($data);

                }

            }
        }
    }
}