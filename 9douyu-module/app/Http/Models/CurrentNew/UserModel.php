<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/22
 * Time: 下午4:42
 */

namespace App\Http\Models\CurrentNew;


use App\Http\Dbs\CurrentNew\CurrentNewAccountDb;
use App\Http\Dbs\CurrentNew\UserCurrentNewFundHistoryDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;

class UserModel extends Model
{

    public static $codeArr = [
        'getUserAmount'                     => 1,
        'checkUserBalance'                  => 2,
        'editRateNotExist'                  => 3,
        'editRateFailed'                    => 4,
        'create'                            => 5,
        'updateInvestOutStatus'             => 6,
        'checkCanInvest'                    => 7,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_USER;


    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     * 用户账户余额
     */
    public static function getUserAmount($userId){

        if(!$userId){

            throw new \Exception(LangModel::getLang('ERROR_PARAMS'), self::getFinalCode('getUserAmount'));

        }

        $db = new CurrentNewAccountDb();

        $balance = $db->getCurrentAccountByUserId($userId);

        return $balance;

    }

    /**
     * @param $balance
     * @param $cash
     * @return bool
     * @throws \Exception
     * 检测账户余额
     */
    public static function checkUserBalance($balance, $cash){

        if($balance < $cash){
            //帐户余额不足
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_USER_BALANCE_NOT_ENOUGH'), self::getFinalCode('checkUserBalance'));
        }

        return true;

    }

    /**
     * @param $userId
     * @param $cash
     * @param $afterBalance
     * @param $eventId
     * @param $note
     * @throws \Exception
     * 添加数据
     * @return boolean
     */
    public function create($userId, $cash, $afterBalance, $eventId, $note=''){

        $db = new UserCurrentNewFundHistoryDb();

        $result = $db->add($userId, $cash, $afterBalance, $eventId, $note);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_RECORD_ADD_FAIL'), self::getFinalCode('create'));


        }

        return true;

    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * 转出
     */
    public function updateInvestOutStatus($id){

        $db = new UserCurrentNewFundHistoryDb();

        $result = $db->updateInvestOutStatus( $id );

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_RECORD_UPDATE_FAIL'), self::getFinalCode('updateInvestOutStatus'));


        }

        return true;

    }

    /**
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public static function checkCanInvest( $userId, $cash ){

        $balance = self::getUserAmount( $userId );

        $maxInvest = self::getMaxInvestCash();

        if($balance > $maxInvest){

            $leftInvest = $maxInvest - $balance + $cash;

            $leftInvest = $leftInvest<=0 ? 0 : $leftInvest;

            throw new \Exception("您当前最高可投".(int)$leftInvest.'元', self::getFinalCode('checkCanInvest'));

        }

        return true;

    }

    /**
     * @return int
     * 最高持有金额
     */
    public static function getMaxInvestCash(){

        $config = SystemConfigModel::getConfig('CURRENT_NEW');

        if(empty($config) || empty($config['INVEST_MAX'])){
            $maxInvest = 10000;
        }else{
            $maxInvest = $config['INVEST_MAX'];
        }

        return $maxInvest;

    }

}