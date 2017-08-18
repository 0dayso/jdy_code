<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/17
 * Time: 下午8:55
 * Desc: 账户中心相关逻辑
 */

namespace App\Http\Logics\User;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\UserLogic;
use App\Http\Models\BankCard\CardModel;
use App\Http\Models\Common\UserFundModel;
use App\Http\Models\Common\UserModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Fund\TicketModel;
use Log;

class IndexLogic extends Logic
{

    /**
     * @param $userId
     * @param $password
     * @return array
     * @desc 修改密码
     */
    public function doModifyPassword($userId, $password)
    {

        $userModel = new UserModel();

        try{

            $res = $userModel->doModifyPassword($userId, $password);

            $return = self::callSuccess($res);

        }catch (\Exception $e){

            $log = [
                'msg'   => '修改登录密码失败', //$e->getMessage(),
                'code'  => $e->getCode(),
                'data'  => [
                    'user_id'   => $userId,
                    'password'  => $password
                ]
            ];

            Log::Error('doModifyPasswordError', $log);

            $return = self::callError($log['msg']);

        }

        return $return;

    }

    /**
     * @param $userId
     * @param $tradingPassword
     * @return array
     * @desc 更新交易密码
     */
    public function doModifyTradingPassword($userId, $tradingPassword)
    {

        $userModel = new UserModel();

        try{

            $res = $userModel->doModifyTradingPassword($userId, $tradingPassword);

            $return = self::callSuccess($res);

        }catch (\Exception $e){

            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'data'  => [
                    'user_id'               => $userId,
                    'trading_password'      => $tradingPassword
                ]
            ];

            Log::Error('doModifyTradingPasswordError', $log);

            $return = self::callError($log['msg']);

        }

        return $return;

    }

    /**
     * @param $userId
     * @param $cash
     * @param $tradePassword
     * @param $note
     * @return array|bool
     * @desc module调用加钱接口,先进入账户余额,再扣除至零钱计划
     */
    public function doIncreaseBalanceToCurrentAccount($userId, $cash, $tradePassword, $note, $ticketId, $eventId=''){

        $return = [];

        $eventId = $eventId ? $eventId : FundHistoryDb::ACTIVITY_AWARD;

        $model = new UserFundModel();

        //增加零钱计划信息
        $currentAccountModel = new AccountModel();

        //唯一票据
        $ticketModel = new TicketModel();

        try{
            self::beginTransaction();

            //检测票id是否已经存在
            $ticketModel->checkTicketExist($ticketId);

            //增加账户余额
            $fundId = $model->increaseUserBalance($userId, $cash, $eventId, $note);

            /*****如果业务发展不需要自动转入零钱计划,请从该处开始注释请从该处开始注释请从该处开始注释******/

            //扣除账户余额
            $model->decreaseUserBalance($userId, $cash, FundHistoryDb::INVEST_CURRENT, '自动转入零钱计划');

            //增加到零钱计划
            $currentAccountModel->doAdd($userId, $cash);

            /*****如果业务发展不需要自动转入零钱计划,请从该处结束注释请从该处结束注释请从该处结束注释******/

            //添加票据
            $ticketModel->doCreate($ticketId, $fundId);

            if($note != '合伙人佣金转出' && $eventId != FundHistoryDb::INVEST_OUT_CURRENT_NEW){
                $msg = 'user_id => '.$userId.'; cash => '.$cash.'; note => 加钱'.$note.'; event => '.$eventId;
                UserLogic::doChangeBalanceWarning($msg);
            }

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'data'  => [
                    'user_id'               => $userId,
                    'cash'                  => $cash,
                    'note'                  => $note,
                ]
            ];

            Log::Error('doActivityIncBalanceError', $log);

            return self::callError($log['msg']);

        }

        return self::callSuccess($return);

    }

    /**
     * @param $userId
     * @param $cash
     * @param $tradePassword
     * @param $note
     * @param $ticketId
     * @return array|bool
     * @desc 账户余额执行扣款
     */
    public function doDecreaseBalance($userId, $cash, $tradePassword, $note, $ticketId, $eventId=''){

        $return = [];

        $eventId = $eventId ? $eventId : FundHistoryDb::CHARGE_BALANCE;

        $userModel = new UserFundModel();

        //唯一票据
        $ticketModel = new TicketModel();

        try{
            self::beginTransaction();

            //检测票id是否已经存在
            $ticketModel->checkTicketExist($ticketId);

            //执行扣款
            $fundId = $userModel->decreaseUserBalance($userId, $cash, $eventId, $note);

            //添加票据
            $ticketModel->doCreate($ticketId, $fundId);

            if( $eventId != FundHistoryDb::INVEST_CURRENT_NEW){

                $msg = 'user_id => '.$userId.'; cash => '.$cash.'; note => 扣款'.$note.'; event => '.$eventId;

                UserLogic::doChangeBalanceWarning($msg);

            }

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'data'  => [
                    'user_id'               => $userId,
                    'cash'                  => $cash,
                    'note'                  => $note,
                ]
            ];

            Log::Error('doActivityDelBalanceError', $log);

            return self::callError($log['msg']);

        }

        return self::callSuccess($return);

    }


    /**
     *
     * 1. 验证用户是否存在
     * 2. 验证用户是否为激活用户
     * 3. 验证是否为已帮卡用户
     *
     * @param $userId
     * @return array
     */
    public function doFrozenAccount( $userId ){

        $return = false;

        try{

            self::beginTransaction();

            $model = new UserModel();

            //用户ID是否合法
            $model->isUserId($userId);

            //1. 验证用户是否存在
            $model->checkUserExitsByUserId($userId);

            $model->getUserInfo($userId);

            //冻结用户信息
            $model->userFrozen($userId);

            $cardModel = new CardModel();

            $cardModel->cardFrozenByUserId($userId);

            $return = true;

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error('userFrozen',[$userId, $e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($return);

    }


    /**
     * @param $userId
     * @return array
     * @desc 实网解冻
     */
    public function doUnFrozenAccount( $userId ){

        $return = false;

        try{

            self::beginTransaction();

            $model = new UserModel();

            //用户ID是否合法
            $model->isUserId($userId);

            //1. 验证用户是否存在
            $model->checkUserExitsByUserId($userId);

            $userInfo = $model->getUserInfo($userId);

            //2. 验证用户是否为冻结用户
            $model->isFrozen($userInfo['status_code']);

            //用户信息解冻
            $model->userUnFrozen($userId);

            $cardModel = new CardModel();

            $cardModel->cardUnFrozenByUserId($userId);

            $return = true;

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error('userFrozen',[$userId, $e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($return);

    }



}