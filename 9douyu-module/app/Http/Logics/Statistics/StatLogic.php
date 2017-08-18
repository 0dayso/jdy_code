<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/14
 * Time: 上午11:06
 * Desc: 统计数据
 */

namespace App\Http\Logics\Statistics;

use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Credit\CreditThirdDetailDb;
use App\Http\Dbs\Current\InvestDb;
use App\Http\Dbs\Fund\FundHistoryDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Logics\Credit\CreditThirdDetailLogic;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Common\CoreApi\FundHistoryModel;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\CoreApi\StatisticsModel;
use App\Http\Models\Common\DbKvdbModel;
use App\Http\Models\Credit\CreditAllModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Cache;
use JDY\DB\CurrentInvestDb;
use JDY\Model\CurrentInvestModel;
use Log;

class StatLogic extends Logic
{

    /**
     * @return array|mixed
     * @desc 获取APP首页信息
     */
    public function getAppV3HomeData()
    {

        //$data = Cache::get('APP_V3_INDEX');

        $data = [];

        $randNum = rand(0,2);

        if( !empty($data) ){

            return isset($data[$randNum]) ? $data[$randNum] : ['type' => 1, 'word'=> '请重新获取'];

        }

        //交易总额=零钱计划交易和+定期交易和、注册人数、已安全返还本息

        $returnData = StatisticsModel::getStatistics();

        $data = [
            [
                'type'  => 1,
                //'word'  => '交易总额: '.number_format($returnData['currentInvestAmount'] + $returnData['projectInvestAmount'] + $returnData['creditAssignInvestAmount']).'元'
                'word'  => '心安财有余'
            ],
            [
                'type'  => 2,
                'word'  => '注册人数: '.$returnData['userCount'].'人'
            ],
            [
                'type'  => 3,
                'word'  => '已安全返还本息: '.number_format($returnData['refundAmount']).'元'
            ],

        ];

        return $data[$randNum];

    }


    /**
     * @desc    定时脚本-账户金额统计
     * 统计信息
     * 账户总金额   totalAmount
     * 再投本金     currentAmount
     * 累计收益
     * 留存资金
     * 今日投资
     * 今日充值
     * 今日体现
     * 今日回款
     * 今日合伙人奖励 activity_partner
     **/
    public function fundStatisticsWithDay(){

        $rawkey     = "FUND_STATISTICS";
        #获取数据
        $fundStat   = CoreApiUserModel::getCoreApiFundStatistics();

        $model  = new DbKvdbModel();

        try{
            $activityFundModule =   new ActivityFundHistoryModel();

            $startTime  = date('Y-m-d',strtotime(" -1 day"));

            $endTime    = date("Y-m-d");

            $partnerTotal=   $activityFundModule->getActivityFundHistorySumCashByStat($startTime ,$endTime ,ActivityFundHistoryDb::TYPE_IN ,ActivityFundHistoryDb::SOURCE_PARTNER );

            $currentInvestData  =   self::getInvestCurrentTotalCashByTime($startTime,$endTime);

            $fundStat['activity_partner'] = $partnerTotal['cash'];
            $fundStat['current_invest_in']  = $currentInvestData['current_invest_in'];
            $fundStat['current_invest_out'] = $currentInvestData['current_invest_out'];
            $result = $model->addData($rawkey,$fundStat);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return $result;
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     * @desc 零钱计划转入转出金额
     */
    public static function getInvestCurrentTotalCashByTime($start = '',$end = '')
    {
        $currentInvestDB    =   new InvestDb();

        $totalCurrentData   =   $currentInvestDB->getInvestCurrentTotalCashByTime($start,$end);

        $totalCurrentData   =   ToolArray::arrayToKey($totalCurrentData,'type');

        $investCash         =   0;
        $investOutCash      =   0;

        if($totalCurrentData){

            $investCurrentEvent = FundHistoryDb::INVEST_CURRENT;
            $investOutEvent     = FundHistoryDb::INVEST_OUT_CURRENT;
            $investAutoEvent    = FundHistoryDb::INVEST_CURRENT_AUTO;

            if(isset($totalCurrentData[$investCurrentEvent])){

                $investCash     += $totalCurrentData[$investCurrentEvent]['cash'];

            }

            if(isset($totalCurrentData[$investAutoEvent])){

                $investCash     += $totalCurrentData[$investAutoEvent]['cash'];

            }

            if(isset($totalCurrentData[$investOutEvent])){

                $investOutCash  += $totalCurrentData[$investOutEvent]['cash'];
            }
        }

        return [
            'current_invest_in'     =>  $investCash,
            'current_invest_out'    =>  $investOutCash,
        ];
    }

    /**
     * @return  array|mixed
     * @desc    获取账户资金统计记录
     * 只有一个用处-后台-资金管理-账户资金统计
     */
    public function getFundStatList( $rawkey, $page, $size, $startTime='', $endTime=''){

        $model  = new DbKvdbModel();

        $result = $model->getDbKvdbList($rawkey, $page, $size, $startTime, $endTime);

        $resData= [];
        $resData["total"]   = $result["total"];
        $resData["list"]    = [];
        if($result["list"]){

            foreach ($result["list"] as $key=>$value){

                $resData['list'][$key]    = json_decode($value['val'] , true);;
            }
        }

        return $resData;

    }

    //格式化数据
    public function doFormatExportFundStatList( $fundStatList = array() )
    {
        if( empty($fundStatList) ){

            return [];
        }

        $formatList =   [];

        //页面输出数据
        $formatTitle[] = ['统计日期', '账户资金','账户余额', '累计收益',
            '定期收益','活期收益', '在投本金', '定期本金','定期满标金额',
            '活期本金','今日投资','活动奖励','今日回款','活期昨日收益', '今日活期转入','今日活期转出',
            '总充值','当日充值', '总提现','当日提现'];

        foreach ($fundStatList as $key => $fundStat){

            $formatList[$key]["stat_date"]     = $fundStat["stat_date"];    //统计日期
            $formatList[$key]["total_cash"]    = $fundStat["total_cash"];   //账号总金额
            $formatList[$key]["total_balance"] = $fundStat["total_balance"];    //账号余额
            $formatList[$key]["total_interest"]= $fundStat["total_interest"];   //累计收益
            $formatList[$key]["investRefundInterest"]= $fundStat["investRefundInterest"];   //定期收益
            $formatList[$key]["current_interest"]= $fundStat["current_interest"];   //活期收益
            $formatList[$key]["investing_cash"]= $fundStat["investing_cash"];       //再投本金(活期+定期)
            $formatList[$key]["regular_cash"]= $fundStat["regular_cash"];           //定期再投本金
            $formatList[$key]["full_scale_cash"]= isset($fundStat["full_scale_cash"]) ? $fundStat["full_scale_cash"]: '0.00';           //定期再投本金
            $formatList[$key]["current_cash"]= $fundStat["current_cash"];           //活期余额
            $formatList[$key]["invert_today"]= $fundStat["invert_today"];           //金额投资
            $formatList[$key]["activity"]    = isset($fundStat["activity_partner"]) ? $fundStat["activity_partner"] : "0";       //今日活动奖励
            $formatList[$key]["refund"]      = isset($fundStat["refund_today"]) ? $fundStat["refund_today"] : "0";           //今日回款总额
            $formatList[$key]["yesterday_interest"] = isset($fundStat["yesterday_interest"]) ? $fundStat["yesterday_interest"] : "0";           //零钱计划昨日收益
            $formatList[$key]["current_invest_in"]  = isset($fundStat["current_invest_in"]) ? $fundStat["current_invest_in"] : "0";           //零钱计划今日转入
            $formatList[$key]["current_invest_out"] = isset($fundStat["current_invest_out"]) ? $fundStat["current_invest_out"] : "0";           //零钱计划今日转出
            $formatList[$key]["total_recharge"]= $fundStat["total_recharge"];       //累计充值
            $formatList[$key]["today_recharge"]= $fundStat["today_recharge"];       //今日充值
            $formatList[$key]["total_withdraw"]= $fundStat["total_withdraw"];        //累计提现
            $formatList[$key]["today_withdraw"]= $fundStat["today_withdraw"];       //今日提现
        }

        return array_merge($formatTitle,$formatList);
    }

    /**
     * @param string $date
     * @return array
     * @desc 根据事件类型分组进行数据统计
     */
    public static function addStatisticsFundHistoryGroupByEventId( $date = "")
    {
        $dbRawKey   = "FUND_HISTORY_STATISTICS";

        try{
            $model      =   new DbKvdbModel();

            $result     =   FundHistoryModel::getChangeCashGroupByEventId($date);

            $params     =   [
                'stat_date' =>  ToolTime::getDateBeforeCurrent(),
                'stat_data' =>  $result,
            ];

            $result     =   $model->addData($dbRawKey,$params);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param array $listArr
     * @return array
     * @desc 格式化数据
     */
    public static function doFormatStatisticsFundHistory($listArr = array())
    {
        if( empty($listArr) ){

            return [];
        }

        $statisticsFundHistory  =   [];

        $eventWord  =   FundHistoryLogic::getEventIdToExplain();

        unset($eventWord['500']);

        foreach($listArr as $key => $item ){

            $statisticsFundHistory[$key]['date']    =   $item['stat_date'];

            foreach ($eventWord as $eventId => $word ){

                $eventIdValue   =   "0.00";

                if( isset($item['stat_data'][$eventId]['balance_change']) ) {

                    $eventIdValue   =   $item['stat_data'][$eventId]['balance_change'];
                }

                $statisticsFundHistory[$key][$eventId]    =   $eventIdValue;
            }

        }

        return $statisticsFundHistory;
    }

    /**
     * @param array $title
     * @param array $list
     * @return array
     * @desc  格式化导出数据
     */
    public static function doFormatExportStatisticsFundHistory($title = array(),$list = array())
    {
        if( empty($list) ){

            return [];
        }

        $statDate   =   ["统计时间"];

        $title   =   array_merge($statDate,$title);

        $newTitle[] =   $title;

        return array_merge($newTitle,$list);
    }

    /**
     * @desc 获取App4.0首页统计数据
     * return array
     */
    public function getV4HomeStatistics(){

        $statisticsData = [];

        $returnData = StatisticsModel::getStatistics();

        if(!empty($returnData)){

           $statisticsData = [
                //'userCountNote' => $returnData['userCount'].'投资人的信赖之选',
                //'tradeAmount' => number_format( ((int)$returnData['currentInvestAmount'] + (int)$returnData['projectInvestAmount'] + (int)$returnData['creditAssignInvestAmount']), 2 )."元累计交易金额",
                'userCountNote' => '耀盛中国旗下品牌',
                'tradeAmount'   => '江西银行资金存管',
             ];

        }

        return $statisticsData;
    }

    /**
     * @return array
     * @desc 获取借款的相关信息
     */
    public static function getBorrowingData()
    {
        $cacheKey       =   'BORROW_CACHE';

        $borrowData     =   Cache::get($cacheKey);

        if( !empty($borrowData) ) {

            return json_decode($borrowData,true);
        }
        $currentDb      =   new InvestDb();
        //总出借人数
        $investNumber   =   $currentDb->getInvestNumber() ;
        //零钱计划的笔数
        $currentTotal   =   $currentDb->getInvestTotalNoAuto();

        $termLogic    =   new TermLogic();

        $investInfo     =   $termLogic->getInvestStatistics([]);
        //交易总笔数
        $investTotal    =   $currentTotal   +   $investInfo['investTotal'];

        $thirdDb        =   new CreditThirdDetailDb();

        $thirdTotal     =   $thirdDb->getCreditTotal();

        $borrowInfo     =  ['investTotal' => $investTotal , 'investNumber' => $investNumber['total'] , 'thirdTotal' => $thirdTotal + 20000 ];

        Cache::put($cacheKey, json_encode($borrowInfo), 60*2);

        return $borrowInfo;
    }


    /**
     * @desc    后台数据统计
     * 统计信息
     *  用户注册数
     *  昨日注册
     *  今日注册
     *
     *  今日充值金额
     *  今日提现金额
     *
     *  今日定期投资金额
     *  用户活期投资金额
     *
     *  今日回款金额
     *  明日回款金额
     *
     *  三天内回款金额
     *
     *  近15日投资数/回款数
     *  投资来源四端比例
     *  用户注册四端比例
     *
     **/
    public static function homeStatData(){
        $key    = 'JDY_HOME_STAT';
        $expire = 30;
        $data   = \Cache::get($key);
        #$data  = [];
        if(!empty($data)){
           return json_decode($data,true);
        }else{
            $resData        = StatisticsModel::homeStatData();
            #dd($resData);
            $uinfoDb        = new UserInfoDb();
            $userSource     = $uinfoDb->getUserSource();
            $investDb       = new \App\Http\Dbs\Invest\InvestDb();
            $investSource   = $investDb->getInvestSource();
            $formatData     = self::homeStatDataFormat($resData,$investSource,$userSource);
            \Cache::put($key, json_encode($formatData), $expire);
            return $formatData;

        }

    }

    public static function  homeStatDataFormat($data,$investSource,$userSource){
        $fiftenDayInvest    = $data['fiftenDayInvest'];
        $fiftenDayRefund    = $data['fiftenDayRefund'];
        #dd($data);
        #图表最大金额
        $maxVal             = 0;
        $fiftenInvestStr1   = "";
        $fiftenRefundtStr1  = "";
        $fiftenDateStr1     = "";

        $fiftenDate         = self::getFiftenDate();

        $fiftenDayInvestNew = [];
        foreach ($fiftenDayInvest as $key1=>$val1){
            $showTime1      = date('Ymd',strtotime($val1['date']));
            $fiftenDayInvestNew[$showTime1] =    $val1;
            if($val1['cash'] > $maxVal){
                $maxVal = $val1['cash'];
            }

        }
        $fiftenDayRefundNew = [];
        foreach ($fiftenDayRefund as $key2=>$val2){
            $showTime2   = date('Ymd',strtotime($val2['times']));
            $fiftenDayRefundNew[$showTime2] = $val2;
            if($val2['cash'] > $maxVal){
                $maxVal = $val2['cash'];
            }
        }

        foreach ($fiftenDate as $key=>$ffval){
            $invest = isset($fiftenDayInvestNew[$ffval])?$fiftenDayInvestNew[$ffval]['cash']:"0";
            $refund = isset($fiftenDayRefundNew[$ffval])?$fiftenDayRefundNew[$ffval]['cash']:"0";
            $fiftenInvestStr1   .= "[".$key.','.$invest.'],';
            $fiftenRefundtStr1  .= "[".$key.','.$refund.'],';
            $ffval1 = date('ymd',strtotime($ffval));
            $fiftenDateStr1     .= "[".$key.','.$ffval1.'],';

        }
        $fiftenDateStr1     = trim($fiftenDateStr1,',');
        $fiftenDateStr      = '['.$fiftenDateStr1.']';

        $fiftenInvestStr1   = trim($fiftenInvestStr1,',');
        $fiftenInvestStr    = '['.$fiftenInvestStr1.']';

        $fiftenRefundtStr1  = trim($fiftenRefundtStr1,',');
        $fiftenRefundtStr   = '['.$fiftenRefundtStr1.']';
        $maxVal = ($maxVal + 1000000);

        #投资来源比例
        $investTotal    = $investSource['totalNum'];
        $investPc       = $investSource['pcNum'];
        $investPcBL     = round(($investPc / $investTotal )*100);
        $investPcBL     = $investPcBL > 0 ? $investPcBL : 1;
        $investIos      = $investSource['iosNum'];
        $investIosBL    = round(($investIos / $investTotal )*100);
        $investIosBL    = $investIosBL > 0 ? $investIosBL:1;
        $investWap      = $investSource['wapNum'];
        $investWapBL    = round(($investWap / $investTotal )*100);
        $investWapBL    = $investWapBL > 0 ? $investWapBL:1;
        $investAndroid  = $investSource['androidNum'];
        $investAndroidBL= 100-$investPcBL-$investIosBL-$investWapBL;
        $investSourceArr=    [
            "sourcePc"      => $investPcBL.'%',
            "sourceIos"     => $investIosBL.'%',
            "sourceAndroid" => $investAndroidBL.'%',
            "sourceWap"     => $investWapBL.'%',
        ];

        #注册来源比例

        $userTotal      = $userSource['totalNum'];
        $userPc         = $userSource['pcNum'];
        $userPcBL       = round(($userPc / $userTotal ) * 100);
        $userPcBL       = $userPcBL > 0 ? $userPcBL:1;
        $userIos        = $userSource['iosNum'];
        $userIosBL      = round(($userIos / $userTotal ) * 100);
        if($userIos > 0 and $userIosBL < 1){
            $userIosBL  = "1";
        }
        $userWap        = $userSource['wapNum'];
        $userWapBL      = round(($userWap / $userTotal ) * 100);
        $userWapBL       = $userWapBL > 0 ? $userWapBL:1;
        $userAndroid    = $userSource['androidNum'];
        $userAndroidBL  = 100-$userPcBL-$userIosBL-$userWapBL;
        $userSourceArr  = [
            "sourcePc"        => $userPcBL,
            "sourceIos"       => $userIosBL,
            "sourceAndroid"   => $userAndroidBL,
            "sourceWap"       => $userWapBL,

        ];

        #近15日充值、提现趋势图
        $fiftenDayOrder = [];
        if($data["fiftenDayOrder"]){
            foreach ($data['fiftenDayOrder'] as &$foval ){
                $foval['date']  = date('Y-m-d',strtotime($foval['date']));
                $foval['withdrawCash']  = round($foval['withdrawCash']);
            }
            $fiftenDayOrder     = json_encode($data["fiftenDayOrder"]);
        }


        $resData = [
            "userTotalNum"   => ToolMoney::moneyFormat($data['userTotalNum'],0),
            "userYesterDay"  => $data['userYesterDay'],
            "userToday"      => $data['userToday'],
            "todayRecharge"  => ToolMoney::moneyFormat($data['todayRecharge'],0),
            "todayWithdraw"  => ToolMoney::moneyFormat($data['todayWithdraw'],0),
            "invertToday"    => ToolMoney::moneyFormat($data['invertToday'],0),
            "currentCash"    => ToolMoney::moneyFormat($data['currentCash'],0),
            "todayRefundCash"   => ToolMoney::moneyFormat($data['todayRefundCash'],0),
            "tomorrowRefundCash"=> ToolMoney::moneyFormat($data['tomorrowRefundCash'],0),
            "threeDayRefundCash"=> ToolMoney::moneyFormat($data['threeDayRefundCash'],0),
            "fiftenDayInvest"   => $fiftenInvestStr,
            "fiftenDayRefund"   => $fiftenRefundtStr,
            "fiftenDateStr"     => $fiftenDateStr,
            "investSourceArr"   => $investSourceArr,
            "userSourceArr"     => $userSourceArr,
            "maxVal"            => $maxVal,
            "fiftenDayOrder"    => $fiftenDayOrder,
        ];
        #dd($resData);
        return $resData;
    }


    /**
     * @desc    获取近十五天日期
     **/
    public static function getFiftenDate(){
        $currentDate    = date("Ymd");
        $fiftenDate     = [];

        for($i = 14; $i>0 ;$i--){
            $fiftenDate[]   = date("Ymd",strtotime("-".$i." day"));
        }
        $fiftenDate[]   = $currentDate;

        return $fiftenDate;

    }


    /**
     * @desc
     *  1、借款余额--待收本金
     *  2、未偿还利息--待收利息
     *  3、借款人数      = 还款中的保理，房抵，信贷项目个数+未到期的第三方债权行数
     *      还款中的保理，房抵，信贷项目个数
     *      未到期的第三方债权个数
     *  4、出借人数 = 去重（还款中的定期投资人数，活期账户余额大于0的用户数）
     *  5、平均借款期限    =(期限1+期限2……期限6)/借款人数
     *      期限1、保理一月期
     *      期限2、二月期、三月、六月、12月
     *      期限3、第三方借款项目-借款人数
     *
     *  6、平均借款额度    = 借款余额/借款人数
     *  7、企业及自然人平均借款额度
     *      企业/法人平均借款额度= 企业借款余额/企业借款人数
     *      自然人平均借款额度   =（借款余额-企业借款余额）/（借款人数-企业借款人数）
     *  8、平均借款利率 得到的是两位小数，换算成百分比即可
     *
     **/
    public function investStatWithDay(){

        $rawkey     = "INVEST_STATISTICS";
        #获取数据
        $investStat = CoreApiUserModel::getCoreApiInvestStat();
        \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $investStat );
        $model          = new DbKvdbModel();

        try{

            $currentTime        = date("Y-m-d");
            #借款余额
            $surplusPrincipal   = isset($investStat["surplusPrincipal"])? $investStat["surplusPrincipal"] :0;
            #未偿还利息
            $surplusInterest    = isset($investStat["surplusInterest"]) ? $investStat["surplusInterest"] :0;
            #出借-投资人数
            $investUserNum      = isset($investStat["investUserNum"])   ? $investStat["investUserNum"]  :0;
            #平均借款利率
            $loanAvgRate        = isset($investStat["loanAvgRate"])     ? $investStat["loanAvgRate"]  :0;

            $insertData["stat_date"]        = $currentTime;
            $insertData["surplusPrincipal"] = $surplusPrincipal;
            $insertData["surplusInterest"]  = $surplusInterest;
            $insertData["investUserNum"]    = $investUserNum;

            #投资、还款中项目ID
            $projectIdArr       = isset($investStat["projectIds"])     ? $investStat["projectIds"]  :[];
            $projectIds         = "";
            if($projectIdArr){
                $projectIds         = ToolArray::arrayToIds($projectIdArr, 'id');
                \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $projectIds );
            }

            #借款人数
            #还款中的保理、放抵、信贷项目个数
            $bl_fd_xd_num       = 0;
            $creditAllDb        = new CreditAllDb();
            if($projectIds){
                $bl_fd_xd_num   = $creditAllDb->getCreditInvestProjectNum($projectIds);
            }
            #未到期第三方债权个数
            $thirdCreditDb      = new CreditThirdDetailDb();
            $thirdCreditInfo    = $thirdCreditDb->getCreditThirdIngNum();
            $thirdCreditNum     = isset($thirdCreditInfo["thirdCreditNum"])?$thirdCreditInfo["thirdCreditNum"]:0;
            $loanUserNum        = $bl_fd_xd_num + $thirdCreditNum;
            $insertData["loanUserNum"]      = $loanUserNum;
            #平均借款期限
            $oneMonthProjectIds     = isset($investStat["oneMonthProjectIds"])?$investStat["oneMonthProjectIds"]:[];
            $oneMonth               = 0;
            if($oneMonthProjectIds){
                $projectIds1        = ToolArray::arrayToIds($oneMonthProjectIds, 'id');
                \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $projectIds1 );
                $oneMonth           = $creditAllDb->getCreditInvestFactorNum($projectIds1);

            }
            $twoMonth               = isset($investStat["twoMonth"])    ? $investStat["twoMonth"]:0;
            $moreMonth              = isset($investStat["moreMonth"])   ? $investStat["moreMonth"]:0;
            $loanAvgTime            = 0;
            #平均借款额度
            $loanAvgPrincipal       = 0;
            if($loanUserNum > 0){
                $loanAvgTime            = round(($oneMonth + $twoMonth + $moreMonth + $thirdCreditNum)/$loanUserNum ,2);
                $loanAvgPrincipal       = round($surplusPrincipal/$loanUserNum, 2);
            }
            $insertData["loanAvgTime"]      = $loanAvgTime;
            $insertData["loanAvgPrincipal"] = $loanAvgPrincipal;
            $insertData["loanAvgRate"]      = round($loanAvgRate,2);

            #投资、还款中项目信息
            $companyAvgPrincipal    = 0;
            $personAvgPrincipal     = 0;

            $companyPrincipal       = 0;
            $companyNum             = 0;
            $refundProjectStat      = isset($investStat["refundProjectStat"])?$investStat["refundProjectStat"]:[];
            if($refundProjectStat){
                $refundProjectStatNew   = ToolArray::arrayToKey($refundProjectStat, 'project_id');
                $projectIds2        = ToolArray::arrayToIds($refundProjectStat, 'project_id');
                $creditProjectArr   = $creditAllDb->getCreditInvestProject($projectIds2);
                \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $refundProjectStatNew );
                \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $creditProjectArr );
                if($creditProjectArr){

                    $companyNum         = count($creditProjectArr);
                    foreach ($creditProjectArr as $cpkey=>$cpval){
                        $projectId      = $cpval["project_id"];
                        $companyPrincipal   += $refundProjectStatNew[$projectId]["principal"];
                    }
                    $companyAvgPrincipal    = round($companyPrincipal/$companyNum , 2);

                }
            }
            $personAvgPrincipal     = round( ($surplusPrincipal-$companyPrincipal )/($loanUserNum-$companyNum),2);
            $insertData["companyAvgPrincipal"]  = $companyAvgPrincipal;
            $insertData["personAvgPrincipal"]   = $personAvgPrincipal;

            \Log::info(__METHOD__. ' : '.__LINE__ . ' INVEST-STAT ', $insertData );
            $result = $model->addData($rawkey,  $insertData);

        }catch (\Exception $e){

            \Log::error(__METHOD__." : ".__LINE__.' -ERROR- ',['msg' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return $result;
    }



    //格式化数据
    public function doFormatExportInvestRefundStat( $fundStatList = array() )
    {
        if( empty($fundStatList) ){

            return [];
        }

        $formatList =   [];

        //页面输出数据
        $formatTitle[] = ['统计日期', '借款余额','未偿还利息', '借款人数',
            '出借人数','平均借款期限', '平均借款额度', '企业平均借款额度','自然人平均借款额度',
            '平均借款利率'];

        foreach ($fundStatList as $key => $fundStat){

            $formatList[$key]["stat_date"]          = $fundStat["stat_date"];           //统计日期
            $formatList[$key]["surplusPrincipal"]   = $fundStat["surplusPrincipal"];    //借款余额
            $formatList[$key]["surplusInterest"]    = $fundStat["surplusInterest"];     //未偿还利息
            $formatList[$key]["loanUserNum"]        = $fundStat["loanUserNum"];         //借款人数
            $formatList[$key]["investUserNum"]      = $fundStat["investUserNum"];       //出借人数

            $formatList[$key]["loanAvgTime"]        = $fundStat["loanAvgTime"];         //平均借款期限
            $formatList[$key]["loanAvgPrincipal"]   = $fundStat["loanAvgPrincipal"];    //平均借款额度

            $formatList[$key]["companyAvgPrincipal"]= $fundStat["companyAvgPrincipal"]; //企业平均借款额度
            $formatList[$key]["personAvgPrincipal"] = $fundStat["personAvgPrincipal"];  //自热人平均借款额度
            $formatList[$key]["loanAvgRate"]        = $fundStat["loanAvgRate"];         //平均借款利率

        }

        return array_merge($formatTitle,$formatList);
    }



}
