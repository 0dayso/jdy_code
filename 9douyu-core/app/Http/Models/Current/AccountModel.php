<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/21
 * Time: 上午10:08
 */

namespace App\Http\Models\Current;


use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolArray;

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

        return $res;

    }

    /**
     * @param $id
     * @return CurrentAccountDb
     * @desc 通过id获取零钱计划账户信息
     */
    public function getInfoByUserId($userId)
    {

        $db = new CurrentAccountDb();

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

        $db = new CurrentAccountDb();

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

        $db = new CurrentAccountDb();

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

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_BALANCE_NOT_ENOUTH'),self::getFinalCode('checkAccountBalance'));

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

        $accountDb = new CurrentAccountDb();

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

        $accountDb = new CurrentAccountDb();

        $res = $accountDb->doUpdateCash($userId, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_UPDATE'), self::getFinalCode('decreaseUserBalance'));

        }

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

        $accountDb = new CurrentAccountDb();

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
        
        $db = new CurrentAccountDb();
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
     * @param $userId
     * 获取零钱计划帐户今日零点时的金额,用于匹配债权
     */
    public function getCreditAmount($userId){

        $result = $this->getUserInfo($userId);
        //用户零钱计划账户不存在
        if(empty($result)){
            
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_ACCOUNT_NOT_EXIST'),self::getFinalCode('getCreditAmount'));

        }
        //当前零钱计划账户总金额
        $cash   = $result['cash'];
        $db = new FundHistoryDb();
        //用户今日零钱计划账户变化情况
        $currentChangeData = $db->getTodayUserInvestCurrentAmount($userId);

        if($currentChangeData){
            $changeCash = $currentChangeData->balance_change;

            $cash += $changeCash;
        }

        return $cash;
    }

}