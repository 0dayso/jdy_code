<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/25
 * Time: 14:25
 */

namespace App\Http\Logics\Project;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Project\ProjectModel;
use App\Http\Models\Project\CreditAssignModel;
use App\Http\Models\Common\UserModel;
use App\Http\Models\Common\UserFundModel;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Refund\ProjectLogic;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;
use Illuminate\Support\Facades\Lang;

class CreditAssignLogic extends Logic{


    /**
     * @param $investId
     * @param $cash
     * @return array
     * 创建债转项目
     */
    public function create($investId,$cash){


        try{
            
            ValidateModel::isCash($cash);
            ValidateModel::isInvestId($investId);
            //根据投资ID获取对应的债转项目信息,若存在则抛出异常
            $creditModel = new CreditAssignModel();
            $creditModel->getByInvestId($investId);

            //获取投资信息
            $investModel = new InvestModel();
            $investData = $investModel->getById($investId);

            $projectId = $investData['project_id'];

            //投资当日不能债转
            $creditModel->checkInvestTime($investData['created_at']);

            //获取项目信息
            $projectModel = new ProjectModel();
            $projectData  = $projectModel->getById($projectId);
            //闪电付息项目不允许债转
            $projectModel->checkProjectSdf($projectData);

            //项目完结日不允许债转
            $projectModel->checkProjectEndDate($projectData);

            $endAt = $projectData['end_at'];

            //创建债转项目
            $data = [
                'project_id'        => $projectId,
                'invest_id'         => $investId,
                'total_amount'      => $cash,
                'end_at'            => $endAt,
                'user_id'           => $investData['user_id'],
            ];
            
            $db = new CreditAssignDb();
            $db->addRecord($data);

        }catch (\Exception $e){

            $log = [
                'invest_id' => $investId,
                'cash'  => $cash,
                'msg'   => $e->getMessage()
            ];

            Log::error(__METHOD__.'Error',$log);
            
            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * 根据原项目相关信息匹配投资ID
     */
    public function getInvestId($projectId,$userId,$cash){
        
        try{
            
            ValidateModel::isProjectId($projectId);
            ValidateModel::isUserId($userId);
            ValidateModel::isCash($cash);

            //获取投资信息
            $investModel = new InvestModel();
            $investData = $investModel->getByProjectIdAndUserId($projectId,$userId,$cash);
            $investIds = ToolArray::arrayToIds($investData,'id');

            //获取不能债转的投资记录
            $db             = new CreditAssignDb();
            $unusableInvest = $db->getUnusableByInvestIds($investIds);

            if($unusableInvest){
                
                $unusableIds = ToolArray::arrayToIds($unusableInvest,'invest_id');
                $diffIds = array_diff($investIds,$unusableIds);

                if(!$diffIds){

                    return self::callError('投资信息不存在');
                }

                $result = array_values($diffIds);
                $investId = $result[0];
            }else{

                $investId = $investIds[0];
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['invest_id' => $investId]);
    }
    
    /**
     * @param $id
     * @param $userId
     * @return array
     * @desc 取消债权项目
     */
    public function cancel($id,$userId){

        try{

            ValidateModel::isProjectId($id);
            //获取债转项目信息
            $model = new CreditAssignModel();
            $project = $model->getById($id);

            if($userId != $project['user_id']){

                return self::callError('用户信息不匹配');
            }

            //检查项目是否能取消
            $model->checkCancel($project);

            $db  = new CreditAssignDb();
            $db->cancel($id);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
        
    }

    /**
     * @param $projectId
     * @return array
     * @desc 债转项目详情
     */
    public function getDetailById($projectId){

        $db = new CreditAssignDb();

        $project = $db->getDetailById($projectId);

        $data['project'] = $this->formatDetail($project);

        return self::callSuccess($data);
    }

    /**
     * @param $project
     * @return array
     * @desc 债转详情数据格式化
     */
    public function formatDetail($project)
    {
        if(empty($project)){
            return [];
        }

        $projectType = $this->get_project_type_attribute($project['status']);
        $projectTypeNote = $this->get_project_type_note_attribute($projectType);
        if($project['status'] != CreditAssignDb::STATUS_INVESTING){
            $leftDay = ToolTime::getDayDiff($project['created_at'],$project['end_at']);
        }else{
            $leftDay         = ToolTime::getDayDiff(ToolTime::dbDate(),$project['end_at']);
        }
        $canInvestAmount = $project['total_amount']-$project['invested_amount'];
        //大于10000
        $canInvestAmountW = sprintf("%.2f", $canInvestAmount / 10000);
        if($canInvestAmountW > 1) {
            $canInvestAmountNote = $canInvestAmountW. "万"; //可投资金额
        } else if($canInvestAmountW) {
            $canInvestAmountNote = number_format($canInvestAmount,2); //可投资金额
        } else {
            $canInvestAmountNote = 0;
        }

        $userModel   = new UserModel();

        if(!empty($project['user_id'])){
            $userInfo = $userModel->getUserInfo($project['user_id']);
        }

        $data = [
            'project_id'            => $project['id'],
            'project_name'          =>  CreditAssignDb::CREDIT_ASSIGN_NAME,
            'project_name_2'        => CreditAssignDb::CREDIT_ASSIGN_NAME,
            'orig_project_id'       => $project['project_id'],
            'orig_project_name'     => $project['name'],
            'refund_type'           => $project['refund_type'],
            'project_way'           => '40',
            'percentage_note'       => (float)$project['profit_percentage'],
            'profit_percentage'     => (float)$project['profit_percentage'],
            'status'                => $project['status'],
            'project_type'          => $projectType,
            'project_type_note'     => $projectTypeNote,
            'publish_time'          => ToolTime::getDate($project['created_at']),
            'can_invest_amount'     => $canInvestAmount,
            'can_invest_amount_note'=> $canInvestAmountNote,
            'total_time'            => $leftDay,
            'finish_time'           => $project['end_at'],
            'min_invest'            => $canInvestAmount,
            'min_invest_note'       => $canInvestAmount."元起投",
            'interest_note'         => "当日计息",
            'people_note'           => "单人购买",
            'is_credit_assign'      => "1",
            'user_id'               => $project['user_id'],
            'project_invest_type'   => "3",
            'origin_project_invest_type'=> "1",
            'total_time_note'       => "天",
            'refund_type_name'      => Lang::get('messages.PROJECT.REFUND_TYPE_' . $project['refund_type']),
            'discount_rate'         => "0.00",
            'invest_note'           => "转让本期变现宝",
            'invest_user'           => empty($userInfo['phone'])?'':$userInfo['phone'],
            'invest_user_note'      => empty($userInfo['phone'])?'':substr($userInfo['phone'],0,3).'****'.substr($userInfo['phone'],-3,3),
            'safe'                  => "http://wx.9douyu.com/article/safe",
        ];

        return $data;

    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return array
     * 购买债转项目
     * 1.数据验证
     * 2.判断债转项目是否存在
     * 3.判断用户是否存在
     * 4.判断投资信息是否存在
     * 5.检测债转项目是否能够投资(状态\金额\用户)
     * 6.债转项目承接者(扣钱)
     * 7.债转项目发起者(加钱)
     * 8.承接者添加投资记录
     * 9.承接者生成还款计划
     * 10.出售方还款计划变更
     * 11.更新债转项目金额及状态
     */
    public function invest($projectId,$userId,$cash){

        try{

            ValidateModel::isProjectId($projectId);
            ValidateModel::isUserId($userId);
            ValidateModel::isCash($cash);


            //获取债转项目信息
            $model = new CreditAssignModel();
            $project = $model->getById($projectId);

            //判断用户是否存在
            $userModel = new UserModel();
            $userModel->checkUserExitsByUserId($userId);

            //原投资ID
            $originInvestId = $project['invest_id'];
            //原项目ID
            $originProjectId = $project['project_id'];

            //获取投资信息
            $investModel = new InvestModel();
            $investData = $investModel->getById($originInvestId);

            //检测是否可能投资
            $isFull = $model->checkInvest($project,$investData,$userId,$cash);

            self::beginTransaction();

            //债转项目承接者生成资金流水(扣钱)
            $fundModel = new UserFundModel();
            $fundModel->decreaseUserBalance($userId,$cash,FundHistoryDb::INVEST_CREDIT_ASSIGN, '承接债转 '.$originProjectId);

            //债转项目发起者生成资金流水(加钱)
            $fundModel->increaseUserBalance($investData['user_id'],$cash,FundHistoryDb::CREDIT_ASSIGN_PROJECT, '债转 '.$originProjectId);

            //项目承接者添加投资记录
            $investModel = new InvestModel();
            $investId = $investModel->add($originProjectId, $userId, $cash,InvestDb::INVSET_TYPE_CREDIT_ASSIGN,$projectId);

            //修改债转项目信息
            $db = new CreditAssignDb();
            $db->invest($projectId,$cash,$isFull);


            //债转项目承接者生成还款计划
            //$this->createRefundRecord($userId, $investId, $originInvestId);

            //债转项目发起者变更还款计划 && 债转项目承接者生成还款计划
            $refundModel = new \App\Http\Models\Refund\ProjectModel();
            $refundModel->changeRecord($originInvestId,$investData['created_at'],$investId,$userId);

            self::commit();


            $params = [
                'invest_id'     => $investId,   //债转投资ID
                'user_id'       => $userId,
                'project_id'    => $originProjectId,   //原项目ID
                'origin_invest_id' => $originInvestId,    //原投资ID
                'cash'          => $cash,
            ];

            Log::Info(__METHOD__.'Success',$params);

            $refundDb = new RefundRecordDb();

            $date = $refundDb -> getFirstRefundingDateByInvestId($investId);

            //返回数据
            $projectDb = new ProjectDb();

            $originProjectInfo = $projectDb->getInfoById($originProjectId);

            $refundType = $originProjectInfo['refund_type']==10?40:$originProjectInfo['refund_type'];

            $params = $data = [
                'invest_id'  => $investId,
                'cash'       => number_format($cash,2),
                'refundDate' => $date['times'],
                'refundType' => $refundType,
                'total'      => $refundDb->getInterestRefundIngByInvestId($investId),
                'investType' => 3,
                "refundText" => "回款时间",
                'refundEndData'     => $date['times'],
                'refundEndDataNote' => '收益到账',
                'alert_message' => '',
                'originProjectId' => $originProjectId,
                'origin_user_id'   => $project['user_id']

            ];

            //债转投资成功发送短信提醒
            $params['buyer_uid'] = $userId;
            $params['seller_uid'] = $project['user_id'];
            $params['project_id'] = $projectId;
            \Event::fire('App\Events\Invest\CreditAssignSuccessEvent',[$params]);

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess($data);
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return array
     * 通过零钱购买债转项目
     * 1.数据验证
     * 2.判断债转项目是否存在
     * 3.判断用户是否存在
     * 4.判断投资信息是否存在
     * 5.检测债转项目是否能够投资(状态\金额\用户)
     * 6.债转项目承接者(扣钱)
     * 7.债转项目发起者(加钱)
     * 8.承接者添加投资记录
     * 9.承接者生成还款计划
     * 10.出售方还款计划变更
     * 11.更新债转项目金额及状态
     */
    public function investByCurrent($projectId,$userId,$cash){

        try{

            ValidateModel::isProjectId($projectId);
            ValidateModel::isUserId($userId);
            ValidateModel::isCash($cash);


            //获取债转项目信息
            $model = new CreditAssignModel();
            $project = $model->getById($projectId);

            //判断用户是否存在
            $userModel = new UserModel();
            $userModel->checkUserExitsByUserId($userId);

            /********************************************零钱转出检测***************************************************/
            //最少转出1分钱
            ValidateModel::isDecimalCash($cash);

            //检测零钱计划账户余额是否充足
            $currentAccountModel = new AccountModel();
            $currentAccountModel->checkAccountBalance($userId,$cash);
            /********************************************零钱转出完毕***************************************************/

            //原投资ID
            $originInvestId = $project['invest_id'];
            //原项目ID
            $originProjectId = $project['project_id'];

            //获取投资信息
            $investModel = new InvestModel();
            $investData = $investModel->getById($originInvestId);

            //检测是否可能投资
            $isFull = $model->checkInvest($project,$investData,$userId,$cash);

            self::beginTransaction();

            //减少零钱计划账户金额
            $currentAccountModel->decreaseUserCash($userId, $cash);

            //增加账户金额
            $userFundModel = new UserFundModel();
            $userFundModel->increaseUserBalance($userId, $cash, FundHistoryDb::INVEST_OUT_CURRENT);

            //债转项目承接者生成资金流水(扣钱)
            $fundModel = new UserFundModel();
            $fundModel->decreaseUserBalance($userId,$cash,FundHistoryDb::INVEST_CREDIT_ASSIGN, '零钱直投 '. $originProjectId);

            //债转项目发起者生成资金流水(加钱)
            $fundModel->increaseUserBalance($investData['user_id'],$cash,FundHistoryDb::CREDIT_ASSIGN_PROJECT, '债转 '.$originProjectId);

            //项目承接者添加投资记录
            $investModel = new InvestModel();
            $investId = $investModel->add($originProjectId, $userId, $cash,InvestDb::INVSET_TYPE_CREDIT_ASSIGN,$projectId);

            //修改债转项目信息
            $db = new CreditAssignDb();
            $db->invest($projectId,$cash,$isFull);


            //债转项目承接者生成还款计划
            //$this->createRefundRecord($userId, $investId, $originInvestId);

            //债转项目发起者变更还款计划 && 债转项目承接者生成还款计划
            $refundModel = new \App\Http\Models\Refund\ProjectModel();
            $refundModel->changeRecord($originInvestId,$investData['created_at'],$investId,$userId);

            self::commit();


            $params = [
                'invest_id'     => $investId,   //债转投资ID
                'user_id'       => $userId,
                'project_id'    => $originProjectId,   //原项目ID
                'origin_invest_id' => $originInvestId,    //原投资ID
                'cash'          => $cash,
            ];

            Log::Info(__METHOD__.'Success',$params);

            $refundDb = new RefundRecordDb();

            $date = $refundDb -> getFirstRefundingDateByInvestId($investId);

            //返回数据
            $projectDb = new ProjectDb();

            $originProjectInfo = $projectDb->getInfoById($originProjectId);

            $refundType = $originProjectInfo['refund_type']==10?40:$originProjectInfo['refund_type'];

            $params = $data = [
                'invest_id'  => $investId,
                'cash'       => number_format($cash,2),
                'refundDate' => $date['times'],
                'refundType' => $refundType,
                'total'      => $refundDb->getInterestRefundIngByInvestId($investId),
                'investType' => 3,
                "refundText" => "回款时间",
                'refundEndData'     => $date['times'],
                'refundEndDataNote' => '收益到账',
                'alert_message' => '',
                'originProjectId' => $originProjectId,
                'origin_user_id'   => $project['user_id']

            ];

            //债转投资成功发送短信提醒
            $params['buyer_uid'] = $userId;
            $params['seller_uid'] = $project['user_id'];
            $params['project_id'] = $projectId;
            \Event::fire('App\Events\Invest\CreditAssignSuccessEvent',[$params]);

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess($data);
    }

    /**
     * @param $investId
     * @return array
     * 债转项目承接者生成还款计划
     */
    private function createRefundRecord($userId, $investId, $assignInvestId){

        $refundModel = new \App\Http\Models\Refund\ProjectModel();

        $refundDb = new RefundRecordDb();

        $refundRecord = $refundDb->getRefundingListByInvestId($assignInvestId);

        if( !empty($refundRecord) ){

            $today = ToolTime::dbDate();

            $data = [];

            foreach ($refundRecord as $value){

                if( $value['status'] != RefundRecordDb::STATUS_ING ){

                    continue;

                }

                if( $value['type'] == RefundRecordDb::TYPE_BONUS_RATE ){

                    continue;

                }



                if( $value['times'] < ToolTime::getDateAfterCurrent(30) ){

                    $diffDay = ToolTime::getDayDiff($today, $value['times']);

                    $interest = round(($value['interest'] / IncomeModel::BASE_MONTH_DAY * $diffDay), 2);

                }else{

                    $interest = round($value['interest'] ,2);

                }

                $data[] = [
                    'project_id'        => $value['project_id'],
                    'invest_id'         => $investId,
                    'user_id'           => $userId,
                    'principal'         => $value['principal'],
                    'interest'          => $interest,
                    'cash'              => $value['principal'] + $interest,
                    'times'             => $value['times'],
                    'status'            => $value['status'],
                    'type'              => 0,
                ];

            }

            if( !empty( $data ) ){

                return $refundModel->createRefundList($data);

            }

        }

        throw new \Exception('生成回款计划失败');

    }

    /**
     * @param $userId
     * @return array
     * @desc 用户债权转让列表
     */
    public function userCreditAssign($userId){

        //可转让债权
        $list['can']   = $this->ableCreditAssign($userId);
        //转让中债权
        $list['doing'] = $this->doingCreditAssign($userId);
        //已转让债权
        $list['done']  = $this->doneCreditAssign($userId);
        //债权转让规则
        $list['assign_rule'] = $this->getCreditAssignRule();

        return self::callSuccess($list);

    }

    /**
     * @param $userId
     * @desc 可转让债权列表
     */
    public function ableCreditAssign($userId){

        //最小转让金额
        $config= SystemConfigLogic::getConfig('CREDIT_ASSIGN_MIN_CASH');

        $minCash = empty($config['value']) ? 0 : $config['value'];

        $creditAssignDb   = new CreditAssignDb();

        $creditAssignList = $creditAssignDb->getNoCancelByUserId($userId);

        $CAInvestIds = ToolArray::arrayToIds($creditAssignList, 'invest_id');

        $configArr = SystemConfigLogic::getConfig('CREDIT_ASSIGN_PROJECT');

        $limitDays = min($configArr['value']);

        $listObj = $creditAssignDb->getUserAbleCreditAssign($userId, $minCash, $CAInvestIds, $limitDays);

        $listArr = ToolArray::objectToArray($listObj);

        $data['can_str'] = ['rest_principal'=>'债权本金','refunded_times'=>'已回款期数','project_date'=>'项目期限','left_day'=>'剩余期限',];
        $data['total']   = count($listArr);
        $data['list']    = $this->ableCreditAssignFormat($listArr);

        return $data;

    }

    /**
     * @param $userId
     * @desc 转让中债权列表
     */
    public function doingCreditAssign($userId){

        $creditAssignDb   = new CreditAssignDb();

        $creditAssignList = $creditAssignDb->getUserDoingCreditAssign($userId);

        $data['doing_str'] = ['assign_principal'=>'转让本金','create_time'=>'申请时间','discount_rate'=>'折让率','left_day'=>'剩余期限',];
        $data['total']     = count($creditAssignList);
        $data['list']      = [[]];

        if(empty($creditAssignList) || !is_array($creditAssignList)){

            return $data;

        }

        $list = [];

        foreach($creditAssignList as $key => $item){

            $list[] = [
                'id'                => $item['id'],
                'title'             => CreditAssignDb::CREDIT_ASSIGN_NAME.' '.$item['id'],
                'discount_rate'     => empty($item['discount_rate'])?'0.00%':$item['discount_rate'].'%',
                'assign_principal'  => $item['total_amount'],
                'create_time'       => ToolTime::getDate($item['created_at']),
                'left_day'          => ToolTime::getDayDiff(ToolTime::dbDate(),$item['end_at']), //剩余天数
            ];

        }

        $data['list'] = $list;

        return $data;

    }

    /**
     * @param $userId
     * @desc 已转让债权列表
     */
    public function doneCreditAssign($userId){

        $creditAssignDb   = new CreditAssignDb();

        $creditAssignList = $creditAssignDb->getUserDoneCreditAssign($userId);

        $data['done_str']  = ['assigned_principal'=>'转让本金','discount_rate'=>'折让率','handling_fee'=>'手续费','create_time'=>'申请时间','final_profit'=>'到账金额','full_scale_time'=>'结束时间',];
        $data['total']     = count($creditAssignList);
        $data['list']      = [[]];

        if(empty($creditAssignList) || !is_array($creditAssignList)){

            return $data;

        }

        $list = [];

        foreach($creditAssignList as $key => $item){

            $refundInterest = RefundRecordDb::getCreditAssignInterest($item['project_id'],$item['invest_id'],$item['user_id'],ToolTime::getDate($item['created_at']));

            $refundInterest = empty($refundInterest) ? 0 : $refundInterest;

            $list[] = [
                'id'                => $item['id'],     //项目id
                'title'             => CreditAssignDb::CREDIT_ASSIGN_NAME.' '.$item['id']." ({$item['project_id']})", //项目名称
                'discount_rate'     => empty($item['discount_rate'])?'0.00%':$item['discount_rate'].'%', //折让率
                'assigned_principal'  => $item['total_amount'],       //转让本金
                'create_time'       => ToolTime::getDate($item['created_at']),  //申请日期
                'handling_fee'      => empty($item['handling_fee'])?'0.00':$item['handling_fee'].'%', //手续费
                'full_scale_time'   => ToolTime::getDate($item['end_at']), ////结束日期
                'final_profit'      => $item['total_amount']+$refundInterest,
            ];

        }

        $data['list'] = $list;

        return $data;

    }

    /**
     * @param $data
     * @return array
     * @desc 可转让项目格式化
     */
    protected function ableCreditAssignFormat($data){

        if(empty($data) || !is_array($data)){
            return [[]];
        }

        $formatData = [];

        $investIds = ToolArray::arrayToIds($data, 'invest_id');

        $refundedArr = RefundRecordDb::getRefundedTimes($investIds);

        $refundedArr = ToolArray::arrayToKey($refundedArr, 'invest_id');

        $configArr = SystemConfigLogic::getConfig('CREDIT_ASSIGN_PROJECT');

        $configArr = $configArr['value'];

        foreach($data as $key => $item){

            //$refundedTimes = RefundRecordDb::getRefundedTimes($item['invest_id']);

            //持有天数=当前日期和投资日期的差
            $hadDay = ToolTime::getDayDiff($item['i_created_at'], ToolTime::dbDate());

            $typeAndProductLine = $item['product_line'] + $item['type'];

            $configDay = isset($configArr[$typeAndProductLine]) ? $configArr[$typeAndProductLine] : $configArr['default'];

            if( $hadDay < $configDay ){

                continue;

            }

            $formatData[] = [
                'project_id'        => $item['id'], //项目Id
                'id'                => $item['invest_id'], //项目Id
                'invest_id'         => $item['invest_id'],
                'title'             => $item['name'].' '.$item['id'], //项目名称
                'refund_type'       => Lang::get('messages.PROJECT.REFUND_TYPE_' . $item['refund_type']), //回款方式
                'refunded_times'    => isset($refundedArr[$item['invest_id']]) ? $refundedArr[$item['invest_id']]['num'] : 0,    //已回款期数
                'rest_principal'    => isset($refundedArr[$item['invest_id']]) ? $item['cash']-$refundedArr[$item['invest_id']]['refunded_principal'] : $item['cash'], //本金
                'left_day'          => ToolTime::getDayDiff(ToolTime::dbDate(),$item['end_at']), //剩余天数
                'project_date'      => $item['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $item['type']), //项目期限
            ];

        }

        return $formatData;

    }

    /**
     * 债权转让说明限制规则
     */
    public function getCreditAssignRule(){

        $info[1] = "1.对于直接购买的债权成功后，30日后可挂牌转让；";
        $info[2] = "2.在项目完结日的当日不能申请转让；";
        $info[3] = "3.对于承接人：目前仅支持一次性购买某转让债权全部本金。";
        $str = '';
        foreach($info as $k=>$v){
            if($k==5){
                $str .= $v;
            }else{
                $str .= $v."\n";
            }
        }
        return $str;

    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc 债转项目列表
     */
    public function getList($page=1, $size=10){

        $creditAssignDb   = new CreditAssignDb();

        $list             = $creditAssignDb->getList($page ,$size);

        $data             = [];

        if(is_array($list) && !empty($list)){

            foreach($list as $key => $item){

                if($item['status'] != CreditAssignDb::STATUS_INVESTING){
                    $leftDay = ToolTime::getDayDiff($item['created_at'],$item['end_at']);
                }else{
                    $leftDay = ToolTime::getDayDiff(ToolTime::dbDate(),$item['end_at']);
                }

                $projectType = $this->get_project_type_attribute($item['status']);
                $projectTypeNote = $this->get_project_type_note_attribute($projectType);

                $data[] = [

                    'id'                   => $item['id'],
                    'project_way'          => '40',
                    'default_title'        => CreditAssignDb::CREDIT_ASSIGN_NAME.' '.$item['id'],
                    'assign_principal'     => $item['total_amount'],
                    'project_type'         => $projectType,
                    'project_type_note'    => $projectTypeNote,
                    'left_day'             => $leftDay,
                    'invest_time_unit'     => '天',
                    'percentage_float_one' => (float)$item['profit_percentage'],
                    'project_invest_type'  => 3,
                    'refund_type_text'     => Lang::get('messages.PROJECT.REFUND_TYPE_' . $item['refund_type']),
                    'can_invest_amount'    => $item['total_amount']-$item['invested_amount'],
                    'invest_cash'          => $item['total_amount'],

                ];

            }

        }

        return self::callSuccess($data);

    }

    /**
     * 获取项目类型
     * @return string
     */
    public function get_project_type_attribute($status) {

        switch ($status) {
            case CreditAssignDb::STATUS_INVESTING:
                $processType = 'investing';
                break;
            case CreditAssignDb::STATUS_CANCEL:
                $processType = 'canceled';
                break;
            case CreditAssignDb::STATUS_SELL_OUT:
                $processType = 'fullscale';
                break;
            case CreditAssignDb::STATUS_FINISHED:
                $processType = 'finished';
                break;
            default:
                $processType = 'canceled';
                break;
        }

        return $processType;
    }

    /**
     * 项目状态文字的转码
     * @author zhuming
     * @return string
     */
    public function get_project_type_note_attribute($projectType)
    {
        $noteArr = ["refunding" => "已售罄", "investing" => "立即投资", "fullscale" => "已售罄",  "finished" => "已还款", "canceled" => "取消"];
        return isset($noteArr[$projectType]) ? $noteArr[$projectType] : "不可投资" ;
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户已转让的投资Id数组
     */
    public function getCreditAssignInvestIds($userId){

        $db = new CreditAssignDb();

        $userIds = $db->getCreditAssignInvestIds($userId);

        $userIds = ToolArray::arrayToIds($userIds, 'invest_id');

        return self::callSuccess($userIds);

    }

    /**
     * @return array
     * @desc 获取债权转让可投资个数
     */
    public function getInvestingCount(){

        $db = new CreditAssignDb();

        $data['total'] = $db->getInvestingCount();

        return self::callSuccess($data);

    }

    /**
     * @desc 确认转让信息页面数据
     * @param $investId
     * @return array
     */
    public function getPreCreditAssign( $investId ){

        $db = new InvestDb();

        $info = $db->getInvestInfoById( $investId );

        $investDay = ToolTime::getDayDiff(ToolTime::dbDate(),$info['end_at']);

        $data = [
            'projectId'         => $investId,
            'minDiscountRate'   => 0,
            'maxDiscountRate'   => 0,
            'duration_str'      => '无时限',
            'assign_desc'       => $this->userCreditAssignDesc(),
            'handle_fee'        => 0,
            'assignableCash'    => $info['cash'],
            'assignableCashMat' => $info['cash'],
            'interestSum'       => $info['cash']*$info['profit_percentage']*$investDay/100/365,
            'investDay'         => $investDay,
        ];

        return self::callSuccess($data);

    }

    /**
     * @return array
     * @desc 描述
     */
    public function userCreditAssignDesc(){

        /*$info[1] = "1.转让本金：发起债权转让时，申请出售的债权本金;";
        $info[2] = "2.转让率：债权转让时本金折让比例，为方便转让人快速赎回资金，不支持溢价转让，规定折让率在“0%-10.0%”之间，由转让人进行选择，递增单位0.1%;";
        $info[3] = "3.转让价格：转让本金-转让本金*折让率;";
        $info[4] = "4.手续费：成功转让本金*0.5%；自项目满标审核日起，若用户持有债权90天及以上，转让时不收取手续费;";
        $info[5] = "5.转让时效：为发起债权转让后的72h，在转让有效期内可主动取消转让，取消转让后24h内不可再次发起转让.";*/

        $info[1] = "1.转让本金：发起债权转让时，申请出售的债权本金;";
        $info[2] = "2.转让时效：为发起债权转让后截止项目完结日之前.";

        $str = '';

        foreach($info as $k=>$v){
            if($k==2){
                $str .= $v;
            }else{
                $str .= $v."\n";
            }
        }

        return $str;

    }

    /**
     * @desc 获取投资信息、以及债转投资信息、转让人信息
     * @param $investId
     * @return array
     */
    public function getCreditAssignByInvestId( $investId ){
        try {
            $db = new InvestDb();

            $info = $db->getInvestInfoById($investId);
            if (!empty($info['invest_type']) && $info['invest_type'] == InvestDb::INVSET_TYPE_CREDIT_ASSIGN) {
                $creditAssignId = $info['assign_project_id'];
                $db = new CreditAssignDb();
                $creditAssignDetail = $db->getDetailById($creditAssignId);
                if (!empty($creditAssignDetail['user_id'])) {
                    $userId = $creditAssignDetail['user_id'];
                    $user = UserModel::getUserInfo($userId);
                    if(!empty($user)) {
                        $data = ['invest' => $info, 'creditAssignDetail' => $creditAssignDetail, 'user' => $user];
                        return self::callSuccess($data);
                    }
                }
            }
        }catch (\Exception $e){
            \Log::error(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
        return self::callSuccess([]);
    }


    /**
     * @desc    根据用户ID，获取可债转信息
     **/
    public function getAssignmentInvest( $userId ){
        //最小转让金额
        $config     = SystemConfigLogic::getConfig('CREDIT_ASSIGN_MIN_CASH');
        $minCash    = empty($config['value']) ? 0 : $config['value'];

        $creditAssignDb     = new CreditAssignDb();
        $creditAssignList   = $creditAssignDb->getNoCancelByUserId($userId);
        $CAInvestIds        = ToolArray::arrayToIds($creditAssignList, 'invest_id');
        $configArr  = SystemConfigLogic::getConfig('CREDIT_ASSIGN_PROJECT');
        $configArr['value'] = empty($configArr['value']) ? [30] : $configArr['value'];
        $limitDays  = min($configArr['value']);
        $listObj    = $creditAssignDb->getUserAbleCreditAssign($userId, $minCash, $CAInvestIds, $limitDays);
        $listArr = ToolArray::objectToArray($listObj);
        return $listArr;
    }

    /**
     * @desc    APP4.0- 我的资产-定期资产-转让中
     * @param  [int]    $user_id
     * @param  [int]    $page
     * @param  [int]    $size
     * @return [array]
     */
    public function doingAssignmentInvest($userId, $page=1, $size=50){
        try {
            $creditAssignDb   = new CreditAssignDb();
            $resData    = $creditAssignDb->getUserDoingAssignmentRecord($userId,$page,$size);

            #\Log::info( __METHOD__." : ".__LINE__.' : '.var_export($resData,true));

            #定期-转让中信息
            $assignmentDb       = new CreditAssignDb();
            $assignmentData     = $assignmentDb->getUserDoingAssignmentTotalAmount($userId);
            #定期-转让中金额
            $assignmentCash     = empty($assignmentData["cash"])?0 : $assignmentData["cash"];

            $data       = [
                'assignment_cash'   => $assignmentCash,
                'total'             => $resData['total'],
                'record'            => [],
            ];

            if(!empty($resData['list'])){
                $investIds  = ToolArray::arrayToIds($resData['list'], 'invest_id');
                $refundedArr= RefundRecordDb::getRefundedTimes($investIds);
                $refundedArr= ToolArray::arrayToKey($refundedArr, 'invest_id');

                foreach($resData['list'] as $key=>$record){
                    //债转原项目名称
                    $record['project_name'] = $record['name'];
                    $record['credit_cash']  = ToolMoney::formatDbCashDelete($record['total_amount']);
                    //产品类型
                    $record['product_line_note'] = Lang::get('messages.PROJECT.PRODUCT_LINE_' . $record['product_line']);
                    //项目期限
                    $record['project_time'] = $record['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $record['project_type']);
                    #已回款期数
                    $record['refund_time']  = isset($refundedArr[$record['invest_id']])?$refundedArr[$record['invest_id']]['num']:0;
                    //剩余天数
                    $record['rest_days']    = ToolTime::getDayDiff(ToolTime::dbDate(),$record['end_at']);

                    $data['record'][] = $record;
                }
            }

            return self::callSuccess(empty($data)?[]:$data);
        }catch (\Exception $e){
            $data['user_id']   = $userId;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }


    }

}