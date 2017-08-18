<?php
/**
 * 普付宝订单记录
 * User: bihua
 * Date: 16/8/19
 * Time: 15:32
 */
namespace App\Http\Models\Pfb;

use App\Http\Dbs\Pfb\InvestPfbDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class InvestPfbModel extends Model
{
    public static $codeArr = [
        'addInvestPfbErr'    => 1,
        'updateInvestPfbErr' => 2,
        'addInvestPfbParam'  => 3,
        'updateInvestParam'  => 4,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_PFB_OPERATE;

    /**
     * @param $investId
     * @param $cash
     * @param $userId
     * @return mixed
     * @throws \Exception
     * @desc 添加订单记录
     */
    public function addInfo($investId,$cash,$userId){

        if(empty($investId) || empty($cash) || empty($userId)){

            throw new \Exception(LangModel::getLang('ERROR_PFB_ADD_PARAM_EMPTY'),self::getFinalCode('addInvestPfbParam'));
        }

        $db = new InvestPfbDb();

        $id = $db->addInfo($investId,$cash,$userId);

        if(!$id || $id < 1){

            throw new \Exception(LangModel::getLang('ERROR_PFB_ADD_INVEST_FAIL'),self::getFinalCode('addInvestPfbErr'));
        }

        return $id;

    }

    /**
     * @param $investId
     * @param $status
     * @return mixed
     * @throws \Exception
     * @desc 更新记录状态
     */
    public function editStatus($investId,$status){

        if(empty($investId) || empty($status)){

            throw new \Exception(LangModel::getLang('ERROR_PFB_UPDATE_PARAM_EMPTY'),self::getFinalCode('updateInvestParam'));
        }

        $db  = new InvestPfbDb();

        $res = $db->editStatus($investId,$status);

        if(!$res){

            throw new \Exception(LangModel::getLang('ERROR_PFB_UPDATE_INVEST_FAIL'),self::getFinalCode('updateInvestPfbErr'));
        }

        return $res;
    }

    /**
     * @param $userId
     * @return bool|mixed
     * @desc 获取某个用户的冻结订单的总金额
     */
    public function getFreezeCash($userId){

        if(empty($userId)){

            return false;
        }

        $db   = new InvestPfbDb();

        $cash = $db->getFreezeCash($userId);

        return $cash;
    }

    /**
     * @param $userId
     * @return bool|mixed
     * @desc 获取某个用户的冻结订单的ID
     */
    public function getFreezeInvestIds($userId){

        if(empty($userId)){

            return false;
        }

        $db   = new InvestPfbDb();

        $list = $db->getFreezeInvestIds($userId);

        return $list;
    }
}