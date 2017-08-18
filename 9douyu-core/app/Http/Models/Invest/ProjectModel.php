<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:33
 * Desc: 定期项目
 */

namespace App\Http\Models\Invest;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\UserDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Http\Models\Model;
use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\RefundRecordDb;
use App\Tools\ToolTime;

class ProjectModel extends Model
{

    public static $codeArr = [
        'invest'                    => 1,
        'checkCanInvestGetObj'      => 2,
        'checkCanInvestCash'        => 3,
        'getById'                   => 4,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVEST_PROJECT;

    /**
     * @param $id
     * @param $cash
     * @return mixed
     * @throws \Exception
     * @desc 投资
     */
    public function invest($id, $cash)
    {

        $db = new ProjectDb();

        $res = $db->invest($id, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_UPDATE_PROJECT'), self::getFinalCode('invest'));

        }

        return $res;

    }

    /**
     * @param $projectId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测项目可投
     */
    public function checkCanInvest($projectId, $cash)
    {

        $db = new ProjectDb();

        $info = $db->getObj($projectId);

        $this->checkProjectExist($info);

        $leftAmount = $info['total_amount'] - $info['invested_amount'];

        $this->checkLeftAmount($leftAmount, $cash);

        $this->checkProjectStatus($info['status']);

        $this->checkInvestTime($info['publish_at'], $info['invest_days'], $info['end_at'], $info['new']);

        return true;

    }

    /**
     * @param array $project
     * @return bool
     * @throws \Exception
     * @desc 检测项目是否为空
     */
    public function checkProjectExist($project=[]){

        if( empty($project) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EXIST'), self::getFinalCode('checkProjectExist'));

        }

        return true;

    }

    /**
     * @param $leftAmount
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测项目的剩余可投金额
     */
    public function checkLeftAmount($leftAmount, $cash){

        if( $leftAmount < abs($cash) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LEFT_AMOUNT'), self::getFinalCode('checkLeftAmount'));

        }

        return true;

    }

    /**
     * @param $status
     * @return bool
     * @throws \Exception
     * @desc 检测项目可投状态
     */
    public function checkProjectStatus($status){

        if( $status != ProjectDb::STATUS_INVESTING ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_STATUS'), self::getFinalCode('checkProjectStatus'));

        }

        return true;

    }

    /**
     * @param $publishAt
     * @param $investDays
     * @param $endAt
     * @param $isNew
     * @return bool
     * @throws \Exception
     * @desc 检测项目的完结时间和融资周期
     */
    public function checkInvestTime($publishAt, $investDays, $endAt, $isNew=0){

        $now = ToolTime::dbNow();

        if( $now > date('Y-m-d H:i:s', strtotime("+".$investDays." day", strtotime($publishAt) )) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVEST_DAYS'), self::getFinalCode('checkInvestTime'));

        }

        if( $now > $endAt && !$isNew){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_END_AT'), self::getFinalCode('checkInvestTime'));

        }

        return true;

    }
    

    /**
     * @return mixed
     * 获取零钱计划\定期的统计数据
     */
    public function getData(){

        //零钱计划总收益
        $db             = new CurrentAccountDb();
        $currentTotalInterest  = $db->getCurrentFundStatistics();

        //定期已回款总收益
        $model          = new RefundRecordDb();
        $refundTotalInterest  = $model->getTotalInterest();

        //总收益
        $totalInterest = $currentTotalInterest['current_interest'] + $refundTotalInterest;
        //零钱计划的待收
        $list['currentCashTotal']=   $currentTotalInterest['current_cash'];

        $list['totalInterest']  = $totalInterest;

        //网站注册用户总数
        $userDb = new UserDb();

        $userTotal = $userDb->getUserTotal();

        $list['userTotal'] = $userTotal;

        //零钱计划投资总金额
        $fundDb = new FundHistoryDb();
        //$currentInvestAmount = $fundDb->getInvestAmount();
        //零钱计划的主动投资金额
        $currentInvestAmountNoAuto = $fundDb->getInvestAmountWithoutAuto();

        $list['currentInvestAmount']    = abs($currentInvestAmountNoAuto);

        //定期投资总额
        $investDb = new InvestDb();
        $list['projectInvestAmount'] = $investDb->getInvestTotalCash();

        //债权转让投资总额
        $creditAssignDb = new CreditAssignDb();

        $list['creditAssignInvestAmount'] = $creditAssignDb->getInvestTotalAmount();

        $list['projectTotal']           =   ProjectDb::getProjectTotal();

        return $list;
    }

    /**
     * @return mixed
     * @desc 获取满标项目的占比书
     */
    public function getProjectTotalHundredPercent()
    {
        $hundredList    =    ProjectDb::getProjectTotalHundredPercent();

        if( empty( $hundredList) ) {

            return [];
        }

        $return         =   [];

        $totalFull      =   array_sum( array_column( $hundredList , 'total' ) ) ;

        foreach ( $hundredList  as $key => $hundred ) {

            $formatLine =   $hundred['product_line'] + $hundred['type'] ;

            $return[$formatLine]    =   [
                'total'     =>  $hundred['total'],
                'hundred'   =>  round($hundred['total'] *100 / $totalFull ,2) ,
                'avg_date'  =>  round($hundred['full_date'] /86400 / $hundred['total'],1),
            ];

        }

        return $return;
    }

}


