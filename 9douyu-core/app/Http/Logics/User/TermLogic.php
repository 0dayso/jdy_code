<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/14
 * Time: 上午10:42
 */
namespace App\Http\Logics\User;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\CreditAssignLogic;
use App\Http\Models\Common\UserModel;
use App\Http\Models\User\TermModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Lang;
use Log;

class TermLogic extends Logic
{

    /**
     * app 需要投资中和回款中的项目合集
     * @param  [int]    $user_id
     * @param  [int]    $size
     * @return [array]
     */
    public function getNoFinish($user_id,$size){
        try {
            UserModel::isUserId($user_id);
            $records = TermModel::getNoFinish($user_id,$size);
            $data['total'] = $records['total'];

            $investListIds = $this->getInvestIdsByUserIdRefundList($user_id, $records['data']);
            //获取用户下一期回款数据
            $nextRefundList = ToolArray::arrayToKey(TermModel::getNextRefundByInvestIds($user_id, $investListIds), 'invest_id');
            foreach($records['data'] as $record){
                if(empty($record)) continue;
                $record = get_object_vars($record);
                $record['total'] = ToolMoney::formatDbCashDelete($record['total']);
                $record['principal'] = ToolMoney::formatDbCashDelete($record['principal']);
                $record['profit_percentage'] = (int)$record['profit_percentage'].'%';
                $record['refund_type'] = Lang::get('messages.PROJECT.REFUND_TYPE_'.$record['refund_type']);

                /*$refund = TermModel::getNextRefund($user_id,$record['invest_id']);
                $refund = is_object($refund)?get_object_vars($refund):['cash'=>0,'times'=>''];*/

                $refund = isset($nextRefundList[$record['invest_id']]) ? ['cash'=>$nextRefundList[$record['invest_id']]['cash'],'times'=>$nextRefundList[$record['invest_id']]['times']] : ['cash'=>0,'times'=>''];

                $record['next_cash'] = ToolMoney::formatDbCashDelete($refund['cash']);
                $record['next_at'] = $refund['times'];

                if( !in_array($record['invest_id'], $investListIds) ){

                    $record['created_at'] = '债权承接日';

                }else{

                    $record['created_at'] = date('Y-m-d',strtotime($record['created_at']));

                }
                if(!empty($record['invest_type']) && !empty($record['assign_project_id'])){
                    $record['name'] = $record['name']. " (".CreditAssignDb::CREDIT_ASSIGN_NAME.')';
                }

                $data['record'][] = $record;
            }
            $data['record'] = $records['total']==0 ? [[]] : $data['record'];
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }

    /**
     * @param $user_id
     * @return array
     * @desc 未回款项目数据
     */
    public function getRefunding($user_id,$size){
        try {
            UserModel::isUserId($user_id);
            $records = TermModel::getRefunding($user_id,$size);
            $data['total'] = $records['total'];

            $investListIds = $this->getInvestIdsByUserIdRefundList($user_id, $records['data']);
            //获取用户下一期回款数据
            $nextRefundList = ToolArray::arrayToKey(TermModel::getNextRefundByInvestIds($user_id, $investListIds), 'invest_id');
            foreach($records['data'] as $record){
                if(empty($record)) continue;
                $record = get_object_vars($record);
                $record['profit_percentage'] = (int)$record['profit_percentage'].'%';
                $record['refund_type'] = Lang::get('messages.PROJECT.REFUND_TYPE_'.$record['refund_type']);
                $record['invest_time'] = $record['invest_time'].Lang::get('messages.PROJECT.TYPE_'.$record['type']);
                /*$refund = TermModel::getNextRefund($user_id,$record['invest_id']);
                $refund = is_object($refund)?get_object_vars($refund):['cash'=>0,'times'=>''];*/

                $refund = isset($nextRefundList[$record['invest_id']]) ? ['cash'=>$nextRefundList[$record['invest_id']]['cash'],'times'=>$nextRefundList[$record['invest_id']]['times']] : ['cash'=>0,'times'=>''];

                $record['next_cash'] = $refund['cash'];
                $record['next_at'] = $refund['times'];

                if( !in_array($record['invest_id'], $investListIds) ){

                    $record['created_at'] = '债权承接日';

                }else{

                    $record['created_at'] = date('Y-m-d',strtotime($record['created_at']));

                }

                if(!empty($record['invest_type']) && !empty($record['assign_project_id'])){
                    $record['name'] = $record['name']. " (".CreditAssignDb::CREDIT_ASSIGN_NAME.')';
                }

                $data['record'][] = $record;
            }
            $data['record'] = $records['total']==0 ? [[]] : $data['record'];
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }

    /**
     * @param $user_id
     * @return array
     * @desc 已回款项目数据
     */
    public function getRefunded($user_id,$size){
        try {
            UserModel::isUserId($user_id);
            $records = TermModel::getRefunded($user_id,$size);
            $data['total'] = $records['total'];

            $investListIds = $this->getInvestIdsByUserIdRefundList($user_id, $records['data']);

            foreach($records['data'] as $record){
                if(empty($record)) continue;
                $record = get_object_vars($record);

                if( !in_array($record['invest_id'], $investListIds) ){

                    $record['created_at'] = '债权承接日';

                }else{

                    $record['created_at'] = date('Y-m-d',strtotime($record['created_at']));

                }

                if(!empty($record['invest_type']) && !empty($record['assign_project_id'])){
                    $record['name'] = $record['name']. " (".CreditAssignDb::CREDIT_ASSIGN_NAME.')';
                }
                $record['profit_percentage'] = (float)$record['profit_percentage'].'%';
                $record['refund_type'] = Lang::get('messages.PROJECT.REFUND_TYPE_'.$record['refund_type']);
                //$record['invest_time'] = $record['invest_time'].Lang::get('messages.PROJECT.TYPE_'.$record['type']);
                $data['record'][] = $record;
            }
            $data['record'] = $records['total']==0 ? [[]] : $data['record'];
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }

    /**
     * @param $user_id
     * @return array
     * @desc 投资中项目数据
     */
    public function getInvesting($user_id,$size){
        try {
            UserModel::isUserId($user_id);
            $records = TermModel::getInvesting($user_id,$size);
            $data['total'] = $records['total'];

            $investListIds = $this->getInvestIdsByUserIdRefundList($user_id, $records['data']);

            foreach($records['data'] as $record){
                if(empty($record)) continue;
                $record = get_object_vars($record);
                $record['progress'] = (round($record['invested_amount']/$record['total_amount'],2)*100).'%';
                $record['cash'] = ToolMoney::formatDbCashDelete($record['cash']);
                $record['invested_amount'] = ToolMoney::formatDbCashDelete($record['invested_amount']);
                $record['total_amount'] = ToolMoney::formatDbCashDelete($record['total_amount']);
                $record['profit_percentage'] = (int)$record['profit_percentage'].'%';
                $record['refund_type'] = Lang::get('messages.PROJECT.REFUND_TYPE_'.$record['refund_type']);
                $record['invest_time'] = $record['invest_time'].Lang::get('messages.PROJECT.TYPE_'.$record['type']);

                if( !in_array($record['invest_id'], $investListIds) ){

                    $record['created_at'] = '债权承接日';

                }else{

                    $record['created_at'] = date('Y-m-d',strtotime($record['created_at']));

                }

                $data['record'][] = $record;
            }
            $data['record'] = $records['total']==0 ? [[]] : $data['record'];
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }

    /**
     * @param $userId
     * @param $list
     * @return array
     * @desc 主要是债权转让
     */
    private function getInvestIdsByUserIdRefundList($userId, $list)
    {

        $investIds = ToolArray::arrayToIds($list, 'invest_id');

        $investModel = new InvestDb();

        $investList = $investModel->getListByUserIdIds($userId, $investIds);

        return ToolArray::arrayToIds($investList, 'id');

    }



    /**
     * @param $userId
     * @param $investId
     * @return array
     * @desc 回款计划
     */
    public function getRefundPlan($userId, $investId){

        $data = [];

        try {

            UserModel::isUserId($userId);

            $records = TermModel::getRefundPlan($investId);

            $total = $refuned = $principal = $interestTotal = 0;

            foreach($records as $record){

                $record                 = get_object_vars($record);

                if( $record['user_id'] != $userId ){

                    continue;

                }

                $record['refund_type']  = Lang::get('messages.PROJECT.REFUND_TYPE_'.$record['refund_type']);

                $record['status_note']  = $record['status'] == RefundRecordDb::STATUS_SUCCESS ? '已回款' : '未回款';

                $data['plan'][]         = $record;

                $total                 += $record['cash'];

                $principal             += $record['principal'];

                $interestTotal         += $record['interest'];

                $refuned               += $record['status'] == RefundRecordDb::STATUS_SUCCESS ? $record['cash'] : 0;

            }

            if(!empty($data['plan'])){

                $data['total']      = $total;

                $data['refunded']   = $refuned;

                $data['principal']  = $principal;

                $data['interestTotal']  = $interestTotal;

                $data['noRefunded'] = $total - $refuned;

            }

        }catch (\Exception $e){

            $data['user_id']   = $userId;

            $data['msg']     = $e->getMessage();

            $data['code']    = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($data);
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 获取普付宝用户投资列表
     */
    public function getPfbInvestList($userId, $page, $size){

        $model = new TermModel();

        $res = $model->getPfbInvestList($userId, $page, $size);

        $res = empty($res) ? [] : $res;

        return self::callSuccess($res);

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取普付宝用户投资质押项目的总额
     */
    public function getPfbInvestTotal($userId)
    {

        $model = new TermModel();

        $res = $model->getPfbInvestTotalCash($userId);

        $res = empty($res) ? [] : $res;

        return self::callSuccess($res);

    }


    //TODO:APP4.0-

    /**
     * @desc    APP4.0- 我的资产-定期资产-持有中
     * @param  [int]    $user_id
     * @param  [int]    $page
     * @param  [int]    $size
     * @return [array]
     *
     */
    public function getAppV4UserTermNoFinish($user_id, $page, $size){
        try {
            UserModel::isUserId($user_id);
            $resData    = TermModel::getAppV4UserNoFinish($user_id, $page, $size);
            $creditAssignLogic  = new CreditAssignLogic();
            #用户可转让投资
            $creditAssignArr1   = $creditAssignLogic->getAssignmentInvest($user_id);
            $creditInvest1      = ToolArray::arrayToIds($creditAssignArr1, 'invest_id');
            #用户转让中投资
            $creditAssignDb     = new CreditAssignDb();
            $creditInvestArr3   = $creditAssignDb->getUserDoingCreditAssign($user_id);
            $creditInvest3      = ToolArray::arrayToIds($creditInvestArr3, 'invest_id');

            #用户已转让投资
            $creditAssignArr2   = $creditAssignLogic->getCreditAssignInvestIds($user_id);
            $creditInvest2      = $creditAssignArr2['data'];


            #定期-在投中项目
            $investRefundData   = RefundRecordDb::getNoRefundInterestByUserId($user_id);
            #定期-在投金额
            $regularCash        = empty($investRefundData["refund_principal"])?0 : $investRefundData["refund_principal"];
            #定期-在投利息
            $regularInterest    = empty($investRefundData["refund_interest"])? 0 : $investRefundData["refund_interest"];

            $data       = [
                'user_principal'=> $regularCash,
                'user_interest' => $regularInterest,
                'total'         => $resData['total'],
                'record'        => [],
            ];

            if(!empty($resData['list'])){
                foreach($resData['list'] as $key=>$record){
                    $record = get_object_vars($record);
                    $record['invest_interest']      = ToolMoney::formatDbCashDelete($record['invest_interest']);
                    $record['invest_principal']     = ToolMoney::formatDbCashDelete($record['invest_principal']);
                    $record['profit_percentage']    = (int)$record['profit_percentage'].'%';
                    //产品类型
                    $record['product_line_note']    = Lang::get('messages.PROJECT.PRODUCT_LINE_' . $record['product_line']);
                    //项目期限
                    $record['invest_time_note']     = $record['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $record['type']);

                    $assignment = 0;
                    if(in_array($record['invest_id'], $creditInvest1)  ){
                        $assignment = 1;
                    }elseif(in_array($record['invest_id'], $creditInvest2)  ){
                        $assignment = 130;
                    }elseif(in_array($record['invest_id'], $creditInvest3)  ){
                        $assignment = 100;
                    }
                    $record['assignment']           = $assignment;
                    $data['record'][] = $record;
                }
            }
            return self::callSuccess(empty($data)?[]:$data);
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }

    }



    /**
     * @desc    APP4.0- 我的资产-定期资产-已完结
     * @param  [int]    $user_id
     * @param  [int]    $page
     * @param  [int]    $size
     * @return [array]
     */
    public function getAppV4UserTermFinish($user_id, $page, $size){
        try {
            UserModel::isUserId($user_id);
            $resData    = TermModel::getAppV4UserFinish($user_id, $page, $size);
            #用户已转让投资
            $creditAssignLogic  = new CreditAssignLogic();
            $creditAssignArr2   = $creditAssignLogic->getCreditAssignInvestIds($user_id);
            $creditInvest2      = $creditAssignArr2['data'];

            #定期-已回款信息
            $investRefundData   = RefundRecordDb::getRefundInterestByUserId($user_id);
            #定期-已回款本金
            $regularCash        = empty($investRefundData["refund_principal"])?0 : $investRefundData["refund_principal"];
            #定期-已回款收益
            $regularInterest    = empty($investRefundData["refund_interest"])? 0 : $investRefundData["refund_interest"];

            $data       = [
                'user_principal'=> $regularCash,
                'user_interest' => $regularInterest,
                'total'         => $resData['total'],
                'record'        => [],
            ];
            if(!empty($resData['list'])){
                foreach($resData['list'] as $key=>$record){
                    $record = get_object_vars($record);
                    $record['invest_interest']      = ToolMoney::formatDbCashDelete($record['invest_interest']);
                    $record['invest_principal']     = ToolMoney::formatDbCashDelete($record['invest_principal']);
                    $record['profit_percentage']    = (int)$record['profit_percentage'].'%';
                    //产品类型
                    $record['product_line_note']    = Lang::get('messages.PROJECT.PRODUCT_LINE_' . $record['product_line']);
                    //项目期限
                    $record['invest_time_note']     = $record['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $record['type']);

                    $assignment = 0;
                    if(in_array($record['invest_id'], $creditInvest2)){
                        $assignment = 130;
                    }
                    $record['assignment']   = $assignment;

                    $data['record'][] = $record;
                }
            }

        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }


    /**
     * @desc    APP4.0- 我的资产-定期资产-项目详情
     * @param  [int]    $user_id
     * @param  [int]    $invest_id
     * @return [array]
     */
    public function getAppV4UserTermDetail($user_id, $invest_id){
        try {
            UserModel::isUserId($user_id);

            #投资回款信息
            $refundRecord   = $this->getRefundPlan($user_id,$invest_id);
            #\Log::info(__METHOD__ .' : '.__LINE__.' : '.var_export($refundRecord,true));
            $refundRecord   = isset($refundRecord['data'])?$refundRecord['data']:[];
            #\Log::info(__METHOD__ .' : '.__LINE__.' : '.var_export($refundRecord,true));
            #投资信息
            $investDb       = new InvestDb();
            $investInfo     = $investDb->getInvestInfoById($invest_id);
            #\Log::info(__METHOD__ .' : '.__LINE__.' : '.var_export($investInfo,true));

            #获取投资-转让状态
            $assignment     = 0;
            $credit_assign_project_id   = '';
            $creditAssignDb     = new CreditAssignDb();
            $creditAssignInfo   = $creditAssignDb->getByInvestId($invest_id);
            $date = ToolTime::dbDate();

            if(!empty($creditAssignInfo)){
                if($creditAssignInfo['status'] == 130){
                    $assignment = 130;
                }elseif($creditAssignInfo['status'] == 100 && $creditAssignInfo['end_at'] > $date ){
                    $assignment = 100;
                    $credit_assign_project_id   = $creditAssignInfo['id'];
                }
            }else{
                $creditAssignLogic  = new CreditAssignLogic();
                #用户可转让投资
                $creditAssignArr1   = $creditAssignLogic->getAssignmentInvest($user_id);
                $creditInvest1      = ToolArray::arrayToIds($creditAssignArr1, 'invest_id');
                if(in_array($invest_id, $creditInvest1)  ){
                    $assignment = 1;
                }
            }

            $data   = [];
            if($refundRecord && $investInfo){
                $data       = [
                    'refund_record' => $refundRecord,
                    'invest_info'   => $investInfo,
                    'assignment'    => $assignment,
                    'credit_assign_project_id'    => $credit_assign_project_id,
                ];
            }

        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess(empty($data)?[]:$data);
    }


}
