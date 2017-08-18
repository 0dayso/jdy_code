<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 18:49
 * Desc: 零钱计划帐户相关logic
 */
namespace App\Http\Logics\Module\Current;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\CurrentInterestHistoryDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\UserModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Fund\FundHistoryModel;
use App\Tools\ToolMoney;

class AccountLogic extends Logic{

    /**
     * @param $userId
     * @return array
     * 获取用户需要匹配的债权金额
     */
    public function getCreditAmount($userId){

        try{
            $this->checkUser($userId);
            
            $model     = new AccountModel();
            $cash = $model->getCreditAmount($userId);
            //金额转化为元
            $cash = ToolMoney::formatDbCashDelete($cash);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['cash' => $cash]);
    }


    /**
     * @param $userId
     * @throws \Exception
     * 根据用户ID获取对应的零钱计划帐户信息
     */
    public function getUserFund($userId,$page,$size){

        $return = [
            'account_info'  => [],
            'fund_list'     => [],
            'interest_list' => [],
        ];

        try{
            $this->checkUser($userId);

            $model       = new AccountModel();
            $userInfo    = $model->getUserInfo($userId);

            //零钱计划账户不存在,直接返回
            if(empty($userInfo)){

                return self::callSuccess($return);

            }else{

                //零钱计划账户金额转化
                $userInfo = $this->formatAccountInfo($userInfo);

                $return['account_info'] = $userInfo;

                $fundHistoryLogic       = new FundHistoryLogic();
                //零钱计划转入转出列表
                $return['fund_list'] = $fundHistoryLogic->getCurrentLists($userId,$page,$size);

                //近一周收益
                $return['interest_list']    = $this->getUserInterestListsByDate($userId);
            }
            
            
        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return);
    }


    /**
     * @param $userId
     * @return array|mixed
     * 零钱计划用户近一周收益明细
     */
    private function getUserInterestListsByDate($userId){

        //近一周零钱计划收益
        $interestDb     = new CurrentInterestHistoryDb();
        $interestList   = $interestDb->getFundHistoryList($userId);
        //近一周收益金额转化
        if($interestList){
            foreach($interestList as $k=>$val){
                $interestList[$k]['principal']  = ToolMoney::formatDbCashDelete($val['principal']);
                $interestList[$k]['interest']   = ToolMoney::formatDbCashDelete($val['interest']);

            }

            return $interestList;
        }else{

            return [];
        }


    }

    /**
     * @param $userId
     * @throws \Exception
     * 检查用户ID是否合法,用户是否存在
     */
    private function checkUser($userId){

        ValidateModel::isUserId($userId);

        //判断用户是否存在
        $userModel = new UserModel();
        $userModel->checkUserExitsByUserId($userId);
    }

    /**
     * @return array
     * 获取零钱计划总投资人数
     */
    public function getUserNum(){
        
        $accountDb = new CurrentAccountDb();
        
        $count = $accountDb->getCount();

        return self::callSuccess(['num' => $count]);
    }

    /**
     * @param $userId
     * 获取零钱计划账户信息
     */
    public function getUserInfo($userId){

        try{

            ValidateModel::isUserId($userId);


            $model       = new AccountModel();
            $userInfo    = $model->getUserInfo($userId,true);

            if($userInfo){
                $userInfo = $this->formatAccountInfo($userInfo);
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($userInfo);

    }

    /**
     * @param $userInfo
     * 零钱计划账户金额处理
     */
    private function formatAccountInfo($userInfo){

        $userInfo['cash']                 = ToolMoney::formatDbCashDelete($userInfo['cash']);
        $userInfo['interest']             = ToolMoney::formatDbCashDelete($userInfo['interest']);
        $userInfo['yesterday_interest']   = ToolMoney::formatDbCashDelete($userInfo['yesterday_interest']);

        return $userInfo;
    }

    /**
     * @param $userId
     * 零钱计划用户今日转出总额
     */
    public function getTodayInvestOutAmount($userId){


        try{

            ValidateModel::isUserId($userId);

            $fundDb = new FundHistoryDb();
            $amout = $fundDb->getTodayInvestOutAmount($userId);
            //金额转化为元
            $amout = ToolMoney::formatDbCashDelete($amout);


        }catch(\Exception $e) {

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['amount' => $amout]);
    }


    /**
     * @return array
     * 获取零钱计划转入总金额
     */
    public function getInvestAmount(){
        
        $fundDb = new FundHistoryDb();
        $amount = $fundDb->getInvestAmount();
        //金额转化为元
        $amount = ToolMoney::formatDbCashDelete($amount);

        return self::callSuccess(['amount' => abs($amount)]);
    }


    /**
     * @param $userId
     * @return array
     * 获取用户近一周零钱计划收益
     */
    public function getInterestList($userId){

        try{
            $this->checkUser($userId);

            $model       = new AccountModel();
            $userInfo    = $model->getUserInfo($userId);

            if(empty($userInfo)){
                return self::callError('用户未投资过零钱计划');
            }
            //总收益
            $return['total_interest']   = ToolMoney::formatDbCashDelete($userInfo['interest']);
            //近一周收益
            $return['interest_list']    = $this->getUserInterestListsByDate($userId);


        }catch(\Exception $e) {

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return);
    }

    /**
     * @return array
     * @desc 当前的零钱计划的资金留存
     */
    public function getCurrentAccountAmount()
    {
        $currentAccountDb   =   new CurrentAccountDb();

        $currentData        =   $currentAccountDb->getCurrentFundStatistics();

        return self::callSuccess($currentData);
    }
}