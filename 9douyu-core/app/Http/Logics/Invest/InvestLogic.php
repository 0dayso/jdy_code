<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/26
 * Time: 上午11:14
 */

namespace App\Http\Logics\Invest;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Tools\ToolArray;

class InvestLogic extends Logic
{

    /**
     * @param $id
     * @return mixed
     * @desc 获取投资记录信息
     */
    public function getInfoById($id)
    {

        $investDb = new InvestDb();

        return $investDb->getInfoById($id);

    }


    public function getListByUserId($userId)
    {



    }

    /**
     * @param int $size
     * @return mixed
     * @desc 获取最新的投资记录
     */
    public function getInvestNew($size = 0){

        $size     = $size>0 ? $size : 30;

        $investDb = new InvestDb();

        $list     = $investDb->getInvestNew($size);

        return $list;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取投资记录总额
     */
    public function getInvestAmountByDate($start = '',$end = ''){

        $investDb = new InvestDb();

        $list     = $investDb->getInvestAmountByDate($start,$end);

        return $list;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据时间段获取投资总额
     */
    public function getInvestTermTotal($start = '', $end = ''){

        $db   = new InvestDb();

        $res  = $db->getInvestTermTotal($start,$end);

        $cash = empty($res) ? 0 : $res['cash'];

        return $cash;
    }


    /**
     * @param $projectIds
     * @return mixed
     * 获取指定项目的投资记录
     */
    public function getInvestListByProjectIds($projectIds){
        
        $db = new InvestDb();
        return $db->getInvestListByProjectIds($projectIds);
    }

    /**
     * @desc 获取多个投资ID的投资记录投资列表
     * @param $investIds
     * @return mixed
     */
    public function getInvestListByIds($investIds){

        $investId = explode(',', $investIds);

        $investDb = new InvestDb();

        return $investDb->getInvestByIds($investId);
    }


    /**
     * @param $userIds
     * @param $allUserIds
     * 获取合伙人邀请人待收明细
     */
    public function getPartnerPrincipal($cash,$allUserIds){

        if(!$allUserIds){

            return self::callError('用户不存在');
        }
        
        $allUserIds = explode(',',$allUserIds);


        //定期还款计划列表
        $refundDb   = new RefundRecordDb();
        $refundData = $refundDb->getRefundByUserIds($allUserIds);

        $refundList = ToolArray::arrayToKey($refundData,'user_id');

        //活期账户信息
        $currentDb  = new CurrentAccountDb();
        $currentData = $currentDb->getByUserIds($allUserIds);

        $currentList = ToolArray::arrayToKey($currentData,'user_id');

        
        //定期总待收
        $refundCash = $refundDb->getRefundTotalByUserIds($allUserIds);
        //活期账户总金额
        $currentCash = $currentDb->getTotalCashByUserIds($allUserIds);

        $totalPrincipal = $currentCash + $refundCash['total_cash'];

        $inviteNum = 0;

        foreach($allUserIds as $id){

            $principal = 0;
            if(isset($refundList[$id])){
                $principal += $refundList[$id]['total_cash'];

            }

            if(isset($currentList[$id])){
                $principal += $currentList[$id]['cash'];
            }

            $list[$id] = $principal;

            if($principal > $cash){
                $inviteNum ++;
            }
        }

        $result['list'] = $list;
        $result['principal'] = $totalPrincipal;
        $result['inviteNum']  = $inviteNum;

        return self::callSuccess($result);
    }
    /**
     * @param $projectIds
     * @return mixed
     * 获取指定项目的投资记录
     */
    public function getNormalInvestListByProjectIds($projectIds){

        $projectIds = explode(',', $projectIds);

        $db     = new InvestDb();

        return $db->getNormalInvestListByProjectIds($projectIds);
    }
    /**
     * @param string $projectIds
     * @return array
     * @desc 从核心获取最后一次投资的数据(不包含原项目债转的记录)
     */
    public function getLastInvestTimeByProjectId($projectIds = '' )
    {
        $projectIds     =   explode(",",$projectIds);

        if( empty($projectIds) ){

            return [];
        }

        $investDb       =   new InvestDb();

        return $investDb->getLastInvestTimeByProjectId($projectIds);
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 投资记录
     */
    public function getInvestListByUserId($userId, $page, $size)
    {

        if( empty($userId) )
        {
            return [];
        }

        $investDb = new InvestDb();

        $result = $investDb->getInvestListByUserId($userId, $page, $size);

        return self::callSuccess($result);

    }

    /**
     * @param $userId
     * @return array
     * @desc 根据用户Id获取该用户投资记录（用来判断用户是否投资）
     */
    public function getUserInvestDataByUserId($userId){

        $investDb = new InvestDb();

        $result = $investDb->getUserInvestDataByUserId($userId);

        return self::callSuccess($result);

    }
    
}