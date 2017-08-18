<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:05
 */

namespace App\Http\Models\User;


use App\Http\Dbs\User\PartnerDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class PartnerModel extends Model
{

    public static $codeArr = [
        'create'           => 1,
        'checkUserId'      => 2,
        'checkInvestOut'   => 3,
        'delCash'          => 4,
        'incCash'          => 5,
        'addUser'          => 6,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_PARTNER;

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     * @desc 成为合伙人
     */
    public function create ( $userId ){

        if( empty($userId) ){
            throw new \Exception(LangModel::getLang('ERROR_PARTNER_CREATE'), self::getFinalCode('create'));
        }

        $db = new PartnerDb();

        //user_id是否已成为合伙人
        $this -> checkUserId( $userId );

        $result = $db -> add($userId);

        if( !$result ) {
            throw new \Exception(LangModel::getLang('ERROR_PARTNER_CREATE'), self::getFinalCode('create'));
        }

        return $result;

    }

    /**
     * @param $userId 被邀请Id
     * @return bool
     * @throws \Exception
     * @desc 验证被邀请人是否已被邀请,如果已被邀请则不再建立邀请关系
     */
    public function checkUserId( $userId ){

        $db = new PartnerDb();

        $result = $db -> getByUserId( $userId );

        if( !empty($result) ){

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_USER_ID'), self::getFinalCode('checkUserId'));

        }

        return true;

    }

    /**
     * @param int $userId
     * @param int $cash
     * @return bool
     * @throws \Exception
     * @desc 转出佣金检测
     */
    public function checkInvestOut($userId=0, $cash=0)
    {

        if( $userId < 1 ){
            throw new \Exception(LangModel::getLang('ERROR_PARTNER_DATA'), self::getFinalCode('checkInvestOut'));
        }
        if( $cash < 1 ){
            throw new \Exception(LangModel::getLang('ERROR_PARTNER_CASH'), self::getFinalCode('checkInvestOut'));
        }

        //获取用户佣金信息
        $db           = new PartnerDb();

        $userInfo     = $db->getByUserId($userId);

        if( $userInfo['cash'] < $cash ){

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_CASH_BIG'), self::getFinalCode('checkInvestOut'));
            
        }

        return true;

    }

    /**
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 合伙人佣金转出
     */
    public function delCash($userId, $cash){

        $this->checkInvestOut($userId, $cash);

        //更新佣金金额
        $cash = round($cash, 2);

        if ($userId < 1 || $cash <= 0) {

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_DATA'), self::getFinalCode('updateCash'));

        }

        $db = new PartnerDb();

        $res = $db->delCash($userId, $cash);

        if ( !$res ) {

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_FAIL'), self::getFinalCode('updateCash'));

        }

        return true;

    }

    /**
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 合伙人佣金转入
     */
    public function incCash($userId, $interest, $cash, $inviteNum = 0, $rate = 0){

        //更新佣金金额
        if ($userId < 1) {

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_DATA'), self::getFinalCode('incCash'));

        }

        $db = new PartnerDb();

        $res = $db->incCash($userId, $interest, $cash, $inviteNum, $rate);

        if ($res == false) {

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_FAIL'), self::getFinalCode('incCash'));

        }

        return true;

    }


    /**
     * @param $data
     * @throws \Exception
     * 创建合伙人账户
     */
    public function addUser($data){

        $db = new PartnerDb();

        $result = $db->addRecord($data);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_PARTNER_CREATE'), self::getFinalCode('addUser'));

        }
    }

}