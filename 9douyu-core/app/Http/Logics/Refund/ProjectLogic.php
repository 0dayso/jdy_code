<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/20
 * Time: 下午1:28
 * Desc: 回款相关
 */

namespace App\Http\Logics\Refund;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\InvestExtendDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\RefundLogic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\UserFundModel;
use App\Http\Models\Refund\ProjectModel;
use App\Jobs\Refund\SendNoticeJob;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Log;
use App\Jobs\Refund\ProjectJob;


class ProjectLogic extends Logic
{

    /**
     * @param $projectId
     * @return array
     * 满标生成项目的回款计划
     */
    public function projectFullCreateRefundRecord( $projectId ){

        $return       = self::callError();

        $investDb     = new InvestDb();

        $incomeModel  = new IncomeModel();

        $projectModel = new ProjectModel();

        $refundDb     = new RefundRecordDb();

        $projectDb    = new ProjectDb();

        $investExtend = new InvestExtendDb();

        try{

            $projectInfo = $projectDb->getInfoById( $projectId );

            if( empty($projectInfo) ){

                Log::error('projectFullCreateRefundRecordError', [ $projectId, 'project detail is empty']);

                return self::callError('生成回款记录的项目不存在');

            }

            if( empty($projectInfo['new']) || !$projectInfo['new'] ){

                Log::error('projectFullCreateRefundRecordError', [ $projectId, 'do not new project' ]);

                return self::callError('非满标生成还款计划项目');

            }

            if( $projectInfo['total_amount'] > $projectInfo['invested_amount'] ){

                Log::error('projectFullCreateRefundRecordError', [ $projectId, 'do not full' ]);

                return self::callError('项目还未满标暂不生成还款计划');

            }

            $investList = $investDb->getInvestListByProjectIds([ $projectId ]);

            if( empty($investList) ){

                Log::error('projectFullCreateRefundRecordError', [ $projectId, 'invest list empty' ]);

                return self::callError('暂无投资记录');

            }

            $investIds = ToolArray::arrayToIds($investList);

            $refundList = $refundDb->getByInvestIds( $investIds );

            $refundInvestIds = ToolArray::arrayToIds($refundList, 'invest_id');

            $useBonusInvest = $investExtend->getListByInvestIds($investIds);

            $useBonusInvestList = ToolArray::arrayToKey($useBonusInvest, 'invest_id');

            $refundDataArr = [];

            foreach( $investList as $key => $value ){

                if( !in_array($value['id'], $refundInvestIds) ){

                    $refundData   = $incomeModel->getIncome($value['project_id'], $value['cash'], $value['created_at']);

                    $records      = $this->recordListFormat($refundData, $value['id'], $value['user_id'], $value['project_id']);

                    if( isset( $useBonusInvestList[ $value['id'] ] ) ){

                        $addRate = $useBonusInvestList[$value['id']]['bonus_value'];

                        $records[] = $incomeModel->getRateRecord( $value['id'], $addRate );

                    }

                    $refundDataArr = array_merge($records, $refundDataArr);

                }

            }

            $res          = $projectModel -> createRefundList($refundDataArr);

            $return = self::callSuccess();

            Log::Info('projectFullCreateRefundRecord',['创建回款记录成功',$res]);

        }catch (\Exception $e){

            $return['msg'] = $e->getMessage();

            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode()
            ];

            Log::error('createRefundFail', $log);

        }

        return $return;

    }

    /**
     * @param $investId
     * @param string $profit
     * @return array
     * @desc 创建回款记录，$profit 为加息券的利率
     */
    public function createRecord($investId)
    {

        $return       = self::callError();

        $investDb     = new InvestDb();

        $incomeModel  = new IncomeModel();

        $projectModel = new ProjectModel();

        $refundDb     = new RefundRecordDb();

        $projectDb    = new ProjectDb();

        $commonInfo = $refundDb->getCommonInfoByInvestId($investId);

        if( $commonInfo ){

            Log::Error(__METHOD__."Error", ['msg' => '记录已存在', 'invest_id' => $investId]);

            return self::callError('记录已存在');
            
        }

        try{

            $investInfo   = $investDb->getObj($investId);

            if( empty($investInfo) ){

                Log::error('createRecordError',['invest_id' => $investId]);

                return $return;

            }

            $projectInfo = $projectDb->getInfoById( $investInfo->project_id );

            if( empty($projectInfo) ){

                Log::error('createRecordError', [ $investInfo, 'project info is empty']);

                return self::callError('生成回款记录的项目不存在');

            }

            if( !empty($projectInfo['new']) && $projectInfo['new'] ){

                Log::error('createRecordError', [ $projectInfo, 'new project full create refund record' ]);

                return self::callError('新定期满标后生成项目还款计划');

            }

            $refundData   = $incomeModel->getIncome($investInfo->project_id, $investInfo->cash, $investInfo->created_at);

            Log::Info('createRecordInfo',$refundData);

            $records      = $this -> recordListFormat($refundData, $investId, $investInfo->user_id, $investInfo->project_id);

            $res          = $projectModel -> createRefundList($records);

            $return = self::callSuccess();

            Log::Info('createRefundRecordSuccess',['创建回款记录成功',$res]);

        }catch (\Exception $e){

            $return['msg'] = $e->getMessage();

            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode()
            ];

            Log::error('createRefundFail', $log);

        }

        return $return;

    }

    /**
     * @param string $times
     * @desc 拆分回款条数，加入任务队列
     */
    public function splitRefund($times='')
    {

        $return = self::callError();

        $refundDb = new RefundRecordDb();

        $count = $refundDb->getRefundCountByTimes($times);

        $size = 200;

        $page = ceil($count/$size);

        for( $i = 1; $i <= $page; $i++ ){

            $data = [
                'size'  => $size,
                'times' => $times
            ];

            $res = \Queue::pushOn('doRefund',new ProjectJob($data));

            if( !$res ){

                //短信报警
                $return['msg'] = '定期回款拆分加入队列失败';

                $return['data'] = $data;

                RefundLogic::splitRefundProjectWarning($return);

            }else{

                Log::info('splitRefundSuccess',$data);

            }

        }

        return self::callSuccess();

    }

    /**
     * @param string $times
     * @desc 检测定期项目是否回款失败
     */
    public function CheckProjectRefund($times = ''){

        $times = $times ? $times : ToolTime::dbDate();

        $refundDb = new RefundRecordDb();

        $count = $refundDb->getRefundCountByTimes($times);

        if( $count > 0 ){

            $return['msg'] = $times.'定期未回款,请工程师紧急处理';

            RefundLogic::CheckProjectRefund($return);

        }

    }



    /**
     * @param string $times
     * @return array
     * @desc 执行回款
     */
    public function doRefund($times='', $size=200)
    {

        $return  = self::callError();

        self::beginTransaction();

        $refundProject = new ProjectModel();

        $userFundModel = new UserFundModel();

        $times = $times ? $times : ToolTime::dbDate();

        try{

            //获取还款列表
            $refundList = $refundProject->getRefundList($times, $size);

            //更新账户
            foreach( $refundList as $val ){

                $userFundModel->increaseUserBalance($val['user_id'], $val['cash'], FundHistoryDb::PROJECT_REFUND, '项目 '.$val["project_id"].' 回款');

            }

            //标记回款状态
            $ids = ToolArray::arrayToIds($refundList, 'id');

            $refundProject->updateRefundSuccessByIds($ids);

            self::commit();

            $return = self::callSuccess();

            $log = [
                'times' => $times,
                'msg'   => '还款成功'
            ];

            Log::info('doRefundSuccess',$log);

            $projectIds = ToolArray::arrayToIds($refundList, 'project_id');

            $eventData = [
                'project_ids'   => $projectIds,
                'end_time'      => $times
            ];

            //触发回款成功事件
            \Event::fire('App\Events\Refund\ProjectSuccessEvent', [$eventData]);
            
        }catch (\Exception $e) {

            self::rollback();

            //短信报警
            $return['msg'] = '回款失败，失败原因：'.$e->getMessage();

            $warningMsg = "时间:【{$times}】, 拆分SIZE:【{$size}】, 错误信息:".$e->getMessage().", errorCode:".$e->getCode();

            Log::error('doRefundError', [$warningMsg]);

            RefundLogic::doRefundProjectWarning($warningMsg);

        }

        return $return;

    }

    /**
     * @param $recordList
     * @param $investId
     * @param $userId
     * @param $projectId
     * @return mixed
     * @desc 格式化回款记录
     */
    public function recordListFormat($recordList, $investId, $userId, $projectId){

        foreach($recordList as $key => $value){

            $recordList[$key]['invest_id']  = $investId;

            $recordList[$key]['user_id']    = $userId;

            $recordList[$key]['project_id'] = $projectId;

            $recordList[$key]['type'] = RefundRecordDb::TYPE_COMMON;

            //$recordList[$key]['status']     = RefundRecordDb::STATUS_ING;

        }

        return $recordList;

    }

    /**
     * @return bool
     * @desc 获取明日回款列表,分页拆分加入队列,等待执行
     */
    public function splitRefundToJob()
    {

        $refundDb = new RefundRecordDb();

        $date = ToolTime::getDateAfterCurrent();

        $refundUserList = $refundDb->getRefundListByDate($date);

        if( !empty($refundUserList) ){

            $refundUserList = array_chunk($refundUserList, 100);

            foreach( $refundUserList as $key => $refundInfo ){

                $res = \Queue::pushOn('doSendRefundNotice',new SendNoticeJob($refundInfo));

                if( !$res ){

                    Log::Error(__METHOD__.'Error', ['key' => $key, 'data' => $refundInfo]);

                    return false;

                }
                
            }

        }

    }
    
    

}