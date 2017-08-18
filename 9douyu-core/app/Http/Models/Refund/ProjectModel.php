<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/21
 * Time: 上午10:44
 * Desc: 回款
 */

namespace App\Http\Models\Refund;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Dbs\InvestExtendDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordBakDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Models\Common\ErrorHandleModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Invest\InvestExtendModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class ProjectModel extends Model
{

    public static $codeArr = [
        'getRefundList'                             => 1,
        'updateRefundSuccessByIds'                  => 2,
        'createRefundList'                          => 3,
        'createBakRefund'                           => 4,
        'changeRefund'                              => 5,
        'deleteOriginRefund'                        => 6,
        'createBuyerRefund'                         => 7,
        'checkNextRefund'                           => 8,
        'changeRecord'                              => 9,
        'beforeChangeRecord'                        => 10,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_REFUND_PROJECT;


    /**
     * @return mixed
     * @throws \Exception
     * @desc 获取回款列表
     */
    public function getRefundList($times = '', $size=200)
    {

        //查询当日回款列表
        $refundRecordDb = new RefundRecordDb();

        $refundList = $refundRecordDb->getRefundListByTimes($times, $size);

        if (empty($refundList)) {

            throw new \Exception(LangModel::getLang('ERROR_EMPTY_REFUND_RECORD'), self::getFinalCode('getRefundList'));

        }

        return $refundList;

    }


    /**
     * @param $ids
     * @return bool
     * @throws \Exception
     * @desc 标记回款状态为成功
     */
    public function updateRefundSuccessByIds($ids)
    {

        $db = new RefundRecordDb();

        $res = $db->updateRefundSuccessByIds($ids);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_UPDATE_REFUND_STATUS'), self::getFinalCode('updateRefundSuccessByIds'));

        }

        return $res;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 创建回款记录
     */
    public function createRefundList($data){

        $db = new RefundRecordDb();

        $res = $db->addRefundRecord($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_INSERT_REFUND_STATUS'), self::getFinalCode('createRefundList'));

        }

        return $res;
    }


    /**
     * @param $investId     原投资ID
     * @param $investTime   原投资日期
     * @param $newInvestId  承接者的投资ID
     * @param $userId       承接者用户ID
     * @throws \Exception
     * 承接者及收售方还款计划重新生成
     */
    public function changeRecord($investId,$investTime,$newInvestId,$userId){

        $list = RefundRecordDb::getByInvestId($investId);


        if($list){
            $today = ToolTime::dbDate();

            $todayRefund = false;   //是否存在今日还款计划
            $lastedRefund = [];     //距今天最近上一期还款计划
            $nextRefund   = [];     //距今天最近下一次还款计划
            $bonusRefund  = [];     //加息券后置收益
            $data = [];             //需要备份的还款计划

            $buyerRefund = [];      //债转承接方回款计划

            foreach ($list as $val){

                $refundDate = $val['times'];

                //判断是否有今日还款计划
                if($refundDate == $today){

                    $todayRefund = true;

                    continue;
                }

                //是否有加息券后置收益
                if($val['type'] == RefundRecordDb::TYPE_BONUS_RATE){
                    $bonusRefund  = $val;
                }else{

                    //获取最近下一期还款计划
                    if(empty($nextRefund) && $refundDate > $today){
                        $nextRefund = $val;
                    }

                    //获取距今天最近的上一次的还款计划
                    if($refundDate < $today){

                        if($lastedRefund){
                            if($lastedRefund['times'] < $refundDate){
                                $lastedRefund = $val;
                            }
                        }else{
                            $lastedRefund = $val;
                        }
                    }
                }

                $id = $val['id'];

                unset($val['created_at'],$val['updated_at'],$val['id']);

                if($refundDate > $today){

                    $tmpRefund = $val;

                    //今日以后的未还款计划(非后置加息),直接转让承接者
                    if($val['type'] != RefundRecordDb::TYPE_BONUS_RATE){

                        $tmpRefund['user_id']     = $userId;
                        $tmpRefund['invest_id']   = $newInvestId;

                        $buyerRefund[$tmpRefund['times']][] = $tmpRefund;

                    }

                    $val['refund_id'] = $id;

                    $data[] = $val;
                }
            }
            $date = $lastedRefund ? $lastedRefund['times'] : $investTime;

            //判断是否存在下一期还款计划,若不存在,直接抛异常
            $this->checkNextRefund($nextRefund);

            //加息后置存在
            if($bonusRefund){
                $bonusRefund['bonus_times'] = $nextRefund['times'];
            }

            //更新债转项目出售方回款计划
            $this->splitRefund($nextRefund,$date,$investTime,$bonusRefund,$todayRefund,$userId,$newInvestId);
            //备份还款计划
            $this->createBakRefund($data);

            //未还款的计划全部转让给承接者
            $this->createBuyerRefund($buyerRefund,$nextRefund,$todayRefund);
            //删除原还款计划
            $this->deleteOriginRefund($data);

        }else{

            throw new \Exception(LangModel::getLang('ERROR_REFUND_NOT_EXIST'), self::getFinalCode('changeRecord'));

        }

    }

    /**
     * @param $nextRefund
     * 判断是否存在下一期还款计划
     */
    private function checkNextRefund($nextRefund){

        //没有下一期还款计划,直接抛异常
        if(empty($nextRefund)){

            throw new \Exception(LangModel::getLang('ERROR_NEXT_REFUND_NOT_EXIST'), self::getFinalCode('checkNextRefund'));

        }
    }

    /**
     * @param $data
     * @throws \Exception
     * 创建备份回款计划
     */
    private function createBakRefund($data){

        if($data){

            //备份原有的还款计划
            $bakDb = new RefundRecordBakDb();
            $result = $bakDb->addRecord($data);

            if(!$result){
                throw new \Exception(LangModel::getLang('ERROR_BAK_REFUND_RECORD_CREATE_FAILED'), self::getFinalCode('createBakRefund'));
            }

        }else{

            throw new \Exception(LangModel::getLang('ERROR_BAK_REFUND_NOT_EXIST'), self::getFinalCode('createBakRefund'));

        }
    }

    /**
     * @param $data
     * @throws \Exception
     * 未还款的计划全部转让给承接者
     */
    private function createBuyerRefund($data,$nextRefund,$todayRefund){

        //去掉下一期还款计划(今日没有回款计划的情况下)
        if($nextRefund && !$todayRefund){

            $nextDate = $nextRefund['times'];

            if(isset($data[$nextDate])){
                unset($data[$nextDate]);
            }
        }


        if($data){

            $record = [];
            foreach ($data as $val){
                $record = array_merge($record,$val);
            }

            //未还款的计划全部转让给承接者
            $bakDb = new RefundRecordDb();
            $result = $bakDb->addRefundRecord($record);

            if(!$result){
                throw new \Exception(LangModel::getLang('ERROR_REFUND_RECORD_CHANGE_FAILED'), self::getFinalCode('createBuyerRefund'));
            }

        }
    }


    /**
     * @param $nextRefund       下一个还款计划
     * @param $date             上一期还款日期(投资日期)
     * @param $investTime       投资日期
     * @param $bonusRefund      加息券后置收益
     * @param $todayRefund      今日是否有还款
     * @param $userId           承接者用户ID
     * @param $investId         承接者的投资ID
     * @throws \Exception
     */
    private function splitRefund($nextRefund,$date,$investTime,$bonusRefund,$todayRefund,$userId,$investId){

        //两个还款日相差的天数
        if($nextRefund){

            $newRefund = [];

            //如果今日有回款,则不需要生成新的回款记录
            if( !$todayRefund ){

                $newRefund = $this->createNewRefund($nextRefund,$date,$userId,$investId);
            }

            //如果使用了加息券,下个回款日回
            if($bonusRefund){

                $newRefund[] = $this->createBonusNewRefund($bonusRefund,$investTime);
            }

            if(!empty($newRefund)){

                $db = new RefundRecordDb();
                //生成新的回款计划
                $result = $db->addRefundRecord($newRefund);

                if(!$result){
                    throw new \Exception(LangModel::getLang('ERROR_REFUND_RECORD_CHANGE_FAILED'), self::getFinalCode('changeRefund'));
                }
            }


        }
    }

    /**
     * @param $nextRefund       下一个还款计划
     * @param $date             上一期还款日期(投资日期)
     * @param $investTime       投资日期
     * @param $bonusRefund      加息券后置收益
     * @param $isCreditAssign   是否为已债转项目
     * @param $refundType
     * @throws \Exception
     * @desc 生成提前还款的回款计划
     */
    private function splitBeforeRefund($nextRefund,$date,$investTime,$bonusRefund,$isCreditAssign,$refundType){

        //两个还款日相差的天数
        if($nextRefund){

            $newRefund = [];

            //如果今日有回款,则不需要生成新的回款记录
            $newRefund = $this->createNewBeforeRefund($nextRefund,$date,$isCreditAssign);

            //如果使用了加息券,下个回款日回
            if($bonusRefund){

                if($refundType == ProjectDb::REFUND_TYPE_EQUAL_INTEREST){

                    $newRefund[] = $this->createBeforeEqualBonusNewRefund($bonusRefund, $nextRefund['principal']);
                    
                }else{

                    $newRefund[] = $this->createBeforeBonusNewRefund($bonusRefund,$investTime,$isCreditAssign);

                }

            }

            if(!empty($newRefund)){

                $db = new RefundRecordDb();
                //生成新的回款计划
                $result = $db->addRefundRecord($newRefund);

                if(!$result){
                    throw new \Exception(LangModel::getLang('ERROR_REFUND_RECORD_CHANGE_FAILED'), self::getFinalCode('beforeChangeRecord'));
                }
            }


        }

    }


    /**
     * @param $data
     * @throws \Exception
     * 删除原回款计划
     */
    private function deleteOriginRefund($data){

        if($data){

            $ids = ToolArray::arrayToIds($data,'refund_id');

            $result = RefundRecordDb::deleteRefund($ids);

            if(!$result){

                throw new \Exception(LangModel::getLang('ERROR_BAK_REFUND_RECORD_DELETE_FAILED'), self::getFinalCode('deleteOriginRefund'));

            }
        }
    }


    /**
     * @param $refund
     * @param $date
     * @param int $userId
     * @param int $investId
     * @return mixed
     * 最近一期还款计划拆分
     */
    private function createBonusNewRefund($refund,$date){

        $today = ToolTime::dbDate();

        $refundDiffDays = ToolTime::getDayDiff($refund['times'],$date);

        $refundedDays = ToolTime::getDayDiff($today,$date);

        $newInterest = round(($refund['interest'] * $refundedDays / $refundDiffDays),2);

        $refundData = [
            'project_id' => $refund['project_id'],
            'invest_id'  => $refund['invest_id'],
            'principal'  => 0,
            'user_id'    => $refund['user_id'],
            'interest'   => $newInterest,
            'cash'       => $newInterest,
            'times'      => $refund['bonus_times'],
            'type'       => $refund['type'],
            'before_refund' => $refund['before_refund']
        ];

        return $refundData;


    }

    /**
     * @param $refund
     * @param $date
     * @param $isCreditAssign   是否为已债转项目
     * @return mixed
     * 最近一期还款计划拆分
     */
    private function createBeforeBonusNewRefund($refund,$date,$isCreditAssign){

        $today = ToolTime::dbDate();

        if($isCreditAssign){

            $newInterest = $refund['interest'];

        }else{

            $refundDiffDays = ToolTime::getDayDiff($refund['times'],$date);

            $refundedDays = ToolTime::getDayDiff($today,$date);

            $newInterest = round(($refund['interest'] * $refundedDays / $refundDiffDays),2);
        }


        $refundData = [
            'project_id' => $refund['project_id'],
            'invest_id'  => $refund['invest_id'],
            'principal'  => 0,
            'user_id'    => $refund['user_id'],
            'interest'   => $newInterest,
            'cash'       => $newInterest,
            'times'      => $today,
            'type'       => $refund['type'],
            'before_refund' => 1,
        ];

        return $refundData;


    }

    /**
     * @param $refund
     * @param $principal
     * @throws \Exception
     */
    private function createBeforeEqualBonusNewRefund($refund, $principal){

        $today = ToolTime::dbDate();

        $projectId = $refund['project_id'];

        $investExtendModel = new InvestExtendModel();

        $investExtendInfo = $investExtendModel->getByInvestId($refund['invest_id']);

        if(!empty($investExtendInfo) && !empty($investExtendInfo['bonus_type'] && $investExtendInfo['bonus_type']==InvestExtendDb::BONUS_TYPE_RATE)){

            $profit = $investExtendInfo['bonus_value'];

        }

        $incomeModel = new IncomeModel();

        $getInterestInfo = $incomeModel->getPlanInterest($projectId, (int)$principal, $profit);

        $newInterest = $refund['interest']-$getInterestInfo['rate_record']['interest'];

        $refundData = [
            'project_id' => $refund['project_id'],
            'invest_id'  => $refund['invest_id'],
            'principal'  => 0,
            'user_id'    => $refund['user_id'],
            'interest'   => $newInterest,
            'cash'       => $newInterest,
            'times'      => $today,
            'type'       => $refund['type'],
            'before_refund' => 1,
        ];

        return $refundData;

    }


    /**
     * @param $refund
     * @param $date
     * @param int $userId
     * @param int $investId
     * @return mixed
     * 最近一期还款计划拆分
     */
    private function createNewRefund($refund,$date,$userId,$investId){

        $today = ToolTime::dbDate();

        $refundDiffDays = ToolTime::getDayDiff($refund['times'],$date);

        $refundedDays = ToolTime::getDayDiff($today,$date);

        $newInterest = round(($refund['interest'] * $refundedDays / $refundDiffDays),2);

        //卖方应得收益
        $refundData = [
            'project_id' => $refund['project_id'],
            'invest_id'  => $refund['invest_id'],
            'principal'  => 0,
            'user_id'    => $refund['user_id'],
            'interest'   => $newInterest,
            'cash'       => $newInterest,
            'times'      => $refund['times'],
            'type'       => $refund['type'],
            'before_refund' => $refund['before_refund']
        ];


        $principal  = $refund['principal'];

        //买方应得收益
        $unRefundInterest = $refund['interest'] - $newInterest;

        $unRefundData = [

            'project_id' => $refund['project_id'],
            'invest_id'  => $investId,
            'principal'  => $principal,
            'user_id'    => $userId,
            'interest'   => $unRefundInterest,
            'cash'       => $unRefundInterest + $principal,
            'times'      => $refund['times'],
            'type'       => $refund['type'],
            'before_refund' => $refund['before_refund']
        ];

        return [$refundData , $unRefundData];


    }

    /**
     * @param $refund       下期回款计划
     * @param $date         上期回款日
     * @param $isCreditAssign   是否为已债转项目
     * @return mixed
     * 最近一期还款计划拆分
     */
    private function createNewBeforeRefund($refund,$date,$isCreditAssign){

        $today = ToolTime::dbDate();

        if($isCreditAssign){

            $newInterest = $refund['interest'];

        }else{

            $refundDiffDays = ToolTime::getDayDiff($refund['times'],$date);

            $refundedDays = ToolTime::getDayDiff($today,$date);

            $newInterest = round(($refund['interest'] * $refundedDays / $refundDiffDays),2);
        }

        //卖方应得收益
        $refundData = [
            'project_id' => $refund['project_id'],
            'invest_id'  => $refund['invest_id'],
            'principal'  => $refund['principal'],
            'user_id'    => $refund['user_id'],
            'interest'   => $newInterest,
            'cash'       => $newInterest+$refund['principal'],
            'times'      => $today,
            'type'       => $refund['type'],
            'before_refund' => ProjectDb::BEFORE_REFUND
        ];

        return [$refundData];

    }

    /**
     * @param $userId
     * @return array
     * @desc 用户已回款总金额
     */
    public static function getRefundInterestByUserId($userId){

        $return = RefundRecordDb::getRefundInterestByUserId($userId);

        return is_object($return)? $return->toArray() : [];

    }

    /**
     * @desc 获取今日回款用户
     * @return mixed
     * @throws \Exception
     */
    public function getTodayRefundUser(){
        $refundRecord = new RefundRecordDb();

        $res = $refundRecord->getTodayRefundUser();
        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('getTodayRefundUser'));

        }
        return $res;
    }

    /**
     * @param $investId     原投资ID
     * @param $investTime   原投资日期
     * @param $refundType
     * @throws \Exception
     * 提前还款计划重新生成
     */
    public function beforeChangeRecord($investId,$investTime,$refundType=''){

        $list = RefundRecordDb::getByInvestId($investId);

        if($list){
            $today = ToolTime::dbDate();

            $lastedRefund = [];     //距今天最近上一期还款计划
            $nextRefund   = [];     //距今天最近下一次还款计划
            $bonusRefund  = [];     //加息券后置收益
            $data = [];             //需要备份的还款计划
            $isCreditAssign = false;//是否已债转

            foreach ($list as $val){

                if(empty($projectId) && empty($userId)){
                    $projectId = $val['project_id'];
                    $userId    = $val['user_id'];
                }

                $refundDate = $val['times'];

                //是否有加息券后置收益
                if($val['type'] == RefundRecordDb::TYPE_BONUS_RATE){
                    $bonusRefund  = $val;
                }else{

                    //获取最近下一期还款计划
                    if(empty($nextRefund) && $refundDate > $today){
                        $nextRefund = $val;
                        $nextRefund['principal'] = 0;
                    }

                    if( !empty($nextRefund) ){

                        $nextRefund['principal'] += $val['principal'];

                    }

                    //获取距今天最近的上一次的还款计划
                    if($refundDate <= $today){

                        if($lastedRefund){
                            if($lastedRefund['times'] <= $refundDate){
                                $lastedRefund = $val;
                            }
                        }else{
                            $lastedRefund = $val;
                        }
                    }
                }

                //用于备份的数据
                $id = $val['id'];

                unset($val['created_at'],$val['updated_at'],$val['id']);

                if($refundDate > $today){

                    $val['refund_id'] = $id;

                    $data[] = $val;
                }
            }

            $db = new CreditAssignDb();

            $isCreditAssign = empty($db->isCreditAssign($investId, $projectId, $userId))?false:true;

            $date = $lastedRefund ? $lastedRefund['times'] : $investTime;

            //判断是否存在下一期还款计划,若不存在,直接抛异常
            $this->checkNextRefund($nextRefund);

            //加息后置存在
            if($bonusRefund){
                $bonusRefund['bonus_times'] = $nextRefund['times'];
            }

            //更新提前还款项目投资的回款计划
            $this->splitBeforeRefund($nextRefund,$date,$investTime,$bonusRefund,$isCreditAssign,$refundType);
            //备份还款计划
            $this->createBakRefund($data);
            //删除原回款计划
            $this->deleteOriginRefund($data);

        }else{

            throw new \Exception(LangModel::getLang('ERROR_REFUND_NOT_EXIST'), self::getFinalCode('beforeChangeRecord'));

        }

    }

}