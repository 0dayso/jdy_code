<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/25
 * Time: 14:41
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;

class CreditAssignModel extends Model{

    public static $codeArr = [

        'checkInvestTime'      => 1,
        'getByInvestId'          => 2,
        'getById'                => 3,
        'checkCancel'            => 4,
        'checkStatus'            => 5,
        'checkInvest'            => 6,
        'cancelByProjectIds'     => 7,

    ];

    public static $defaultNameSpace = ExceptionCodeModel::EXP_CREDIT_ASSIGN_PROJECT;

    /**
     * @param $id
     * @param bool $isExist
     * @return mixed
     * @throws \Exception
     *
     */
    public function getById($id){


        $db = new CreditAssignDb();
        $result = $db->getObj($id);

        //要求项目必须存在,否则抛出异常
        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_ASSIGN_PROJECT_NOT_EXIST'), self::getFinalCode('getById'));

        }

        return $result;
        
    }


    /**
     * @param $investId
     * @throws \Exception
     * 根据投资ID获取债转项目信息
     */
    public function getByInvestId($investId){


        $db = new CreditAssignDb();
        $result = $db->getByInvestId($investId);

        if($result){
            throw new \Exception(LangModel::getLang('ERROR_ASSIGN_PROJECT_EXIST'), self::getFinalCode('getByInvestId'));
        }

    }


    /**
     * @param $time
     * @throws \Exception
     * 今天
     */
    public function checkInvestTime($time){

        if(substr($time,0,10) >= ToolTime::dbDate()){

            throw new \Exception(LangModel::getLang('ERROR_ASSIGN_PROJECT_INVEST_TIME'), self::getFinalCode('checkInvestTime'));

        }
    }

    /**
     * @param $project
     * @throws \Exception
     * 取消债转项目
     */
    public function checkCancel($project){

        $this->checkStatus($project);

        //项目已被投资,无法取消
        if($project['invested_amount'] > 0){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVESTING'), self::getFinalCode('checkCancel'));

        }
    }

    /**
     * @param $project
     * @throws \Exception
     * 项目状态检查
     */
    private function checkStatus($project){

        $status = $project['status'];

        //原项目已完结
        if($status == CreditAssignDb::STATUS_FINISHED || $project['end_at'] <= ToolTime::dbDate()){

            throw new \Exception(LangModel::getLang('ERROR_ORIGIN_PROJECT_FINISHED'), self::getFinalCode('checkStatus'));

        }

        //项目已售罄
        if($status == CreditAssignDb::STATUS_SELL_OUT){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_SELL_OUT'), self::getFinalCode('checkStatus'));

        }

        //项目已取消,请勿重复操作
        if($status == CreditAssignDb::STATUS_CANCEL){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_CANCELED'), self::getFinalCode('checkStatus'));

        }
    }

    /**
     * @param $project
     * @param $investData
     * @param $userId
     * @param $cash
     */
    public function checkInvest($project,$investData,$userId,$cash){

        $this->checkStatus($project);

        //不能购买自己发起的债权项目
        if($investData['user_id'] == $userId){
            
            throw new \Exception(LangModel::getLang('ERROR_CAN_NOT_INVEST_SAM_USER_PROJECT'), self::getFinalCode('checkInvest'));

        }
        //剩余可投金额
        $leftAmount = $project['total_amount'] - $project['invested_amount'];

        //暂时不支持分批购买
        if($leftAmount != $cash){

            throw new \Exception(LangModel::getLang('ERROR_ASSIGN_PROJECT_CASH'), self::getFinalCode('checkInvest'));

        }else{

            return true;
        }

        /*
        //剩余可投金额不足
        if($leftAmount < $cash){

            throw new \Exception(LangModel::getLang('ERROR_ASSIGN_PROJECT_FREE_AMOUNT_NOT_ENOUGH'), self::getFinalCode('checkInvest'));

            //投资满额
        }elseif($leftAmount == $cash){

            return true;
        }else{
            return false;
        }
        */

    }

    /**
     * @param $projectIds
     * @return bool
     * @throws \Exception
     * @desc 提前还款取消正在转让的债权项目
     */
    public function cancelByProjectIds( $projectIds ){

        $db = new CreditAssignDb();

        //取消转让中的债权
        $cancel = $db->cancelByProjectIds( $projectIds );

        if( !$cancel ){

            throw new \Exception(LangModel::getLang('ERROR_BEFORE_CANCEL_FAIL'), self::getFinalCode('cancelByProjectIds'));

        }

        //更新提前还款的项目完日为提前还款当天
        $result = $db->beforeUpdateEndAt( $projectIds );

        if( !$result ){

            throw new \Exception(LangModel::getLang('ERROR_BEFORE_UPDATE_END_AT'), self::getFinalCode('cancelByProjectIds'));

        }

        return true;

    }


}