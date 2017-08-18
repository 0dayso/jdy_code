<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/25
 * Time: 下午12:04
 */

namespace App\Http\Models\CurrentNew;


use App\Http\Dbs\CurrentNew\CurrentNewAccountDb;
use App\Http\Models\Credit\CreditDisperseModel;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use Cache;

class AccountModel extends Model
{

    public static $codeArr = [
        'doAddDoUpdateCash'                 => 1,
        'doAddDoAdd'                        => 2,
        'updateInterest'                    => 3,
        'increaseUserBalance'               => 4,
        'decreaseUserBalance'               => 5,
        'createAccount'                     => 6,
        'getCreditAmount'                   => 7,
        'checkAccountBalance'               => 8,
        'getUserInfo'                       => 9,
        'updateBonusInterest'               => 10,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_ACCOUNT;

    /**
     * @param $userId
     * @param $cash
     * @return mixed
     * @throws \Exception
     * @desc 创建用户信息，或者更新信息
     */
    public function doAdd($userId, $cash)
    {

        $info = $this->getInfoByUserId($userId);

        $cash = abs($cash);

        if( $info ){

            $res = $this->increaseUserCash($userId, $cash);

        }else{

            $res = $this->createAccount($userId, $cash);

        }

        $this->getUseAmount( true );

        return $res;

    }

    /**
     * @param $id
     * @return CurrentAccountDb
     * @desc 通过id获取零钱计划账户信息
     */
    public function getInfoByUserId($userId)
    {

        $db = new CurrentNewAccountDb();

        return $db->getInfoByUserId($userId);

    }

    /**
     * @param $userId
     * @param $interest
     * @throws \Exception
     * @desc 更新收益信息
     */
    public function updateInterest($userId,$interest)
    {

        $db = new CurrentNewAccountDb();

        $res = $db->doUpdateInterest($userId, $interest);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_UPDATE'),self::getFinalCode('updateInterest'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @param $interest
     * @throws \Exception
     * @desc 更新加息券收益信息
     */
    public function updateBonusInterest($userId,$interest)
    {

        $db = new CurrentNewAccountDb();

        $res = $db->doUpdateBonusInterest($userId, $interest);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_UPDATE'),self::getFinalCode('updateBonusInterest'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @param $cash
     * 检查零钱计划金额是否充足
     */
    public function checkAccountBalance($userId,$cash){

        $info = $this->getUserInfo($userId);

        if(empty($info) || $info['cash'] < $cash){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_BALANCE_NOT_ENOUGH'),self::getFinalCode('checkAccountBalance'));

        }
    }

    /**
     * @param $userId
     * @param $cash
     * @param $eventId
     * @param string $note
     * @return bool
     * @throws \Exception
     * @desc 增加零钱计划账户金额，零钱计划转入
     */
    public function increaseUserCash($userId, $cash)
    {

        $cash = abs($cash);

        $accountDb = new CurrentNewAccountDb();

        $res = $accountDb->doUpdateCash($userId, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_UPDATE'), self::getFinalCode('increaseUserBalance'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @param $cash
     * @param $eventId
     * @param string $note
     * @return bool
     * @throws \Exception
     * @desc 减少零钱计划账户金额，零钱计划转出
     */
    public function decreaseUserCash($userId, $cash)
    {

        $cash = -abs($cash);

        $accountDb = new CurrentNewAccountDb();

        $res = $accountDb->doUpdateCash($userId, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_UPDATE'), self::getFinalCode('decreaseUserBalance'));

        }

        $this->getUseAmount( true );

        return $res;

    }


    /**
     * @param $userId
     * @param $cash
     * @return mixed
     * @throws \Exception
     * @desc 创建零钱计划账户信息
     */
    public function createAccount($userId, $cash)
    {

        $accountDb = new CurrentNewAccountDb();

        $res = $accountDb->doAdd($userId, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_ADD'), self::getFinalCode('doAddDoAdd'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @return mixed
     * 根据用户ID获取零钱计划账户信息
     */
    public function getUserInfo($userId,$flag = false){

        $db = new CurrentNewAccountDb();
        $obj = $db->getUserInfo($userId);

        if(is_object($obj)){
            return $obj->toArray();
        }else{

            if($flag){

                throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_NOT_EXIST'),self::getFinalCode('getUserInfo'));

            }else{
                return [];
            }
        }


    }

    /**
     * @return int|mixed
     * @param $isUpdate
     * 债权池已用金额
     */
    public function getUseAmount($isUpdate = false){

        $key = "CURRENT_NEW_CREDIT_USE_AMOUNT";

        $useAmount = Cache::get($key);

        if( empty($useAmount) || $isUpdate ){

            $db = new CurrentNewAccountDb();

            $useAmount = $db->getUseAmount();

            $useAmount = $useAmount>0 ? $useAmount : 0;

            Cache::put($key, $useAmount, 60);

        }

        return $useAmount;

    }

    /**
     * @return int
     * 剩余可投资债权金额
     */
    public function getLeftAmount(){

        // 债权可用总资产

        $totalAmount = CreditDisperseModel::getCreditAbleAmountCache(); //债权可匹配的债权金额总资产接口数据

        // 债权已用总资产

        $useAmount  = $this->getUseAmount();

        // 剩余可用资产

        $leftAmount = $totalAmount - $useAmount;

        return $leftAmount>0 ? $leftAmount : 0;

    }

    /**
     * @param $cash
     * @throws \Exception
     * 检测是否可投
     * @return int
     */
    public function checkCanInvest( $cash ){

        $leftAmount = $this->getLeftAmount();

        if( $leftAmount < abs($cash) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_LEFT_AMOUNT_NOT_ENOUGH'), self::getFinalCode('checkCanInvestCashLeftAmountNotEnough'));

        }

        return $leftAmount;

    }

}