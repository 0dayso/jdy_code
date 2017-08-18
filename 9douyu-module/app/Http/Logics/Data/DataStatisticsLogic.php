<?php
/**
 * create by phpstorm
 * @author lgh
 * Date 16/09/02 Time 14:48 PM
 * @desc 数据统计Logic
 */
namespace App\Http\Logics\Data;

use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\Recharge\PayLimitLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Current\FundModel;
use App\Http\Models\User\UserInfoModel;
use App\Http\Models\User\UserModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Log;

class DataStatisticsLogic extends Logic{

    /**
     * @desc 获取当日数据统计
     * @author lgh
     * @return array
     */
    public function SendDayWebData(){

        $data = [];

        //数据统计当日的开始时间和结束时间
        $startTime = date("Y-m-d", strtotime("-1 day"));
        $endTime   = date("Y-m-d");

        $param = [
            'start_time'  => $startTime,
            'end_time'    => $endTime,
        ];

        //前一日的时间段
        $preStartDate   = date("Y-m-d 00:00:00", strtotime("-1 day", strtotime($param['start_time'])));
        $preEndDate     = date("Y-m-d 00:00:00", strtotime("-1 day", strtotime($param['end_time'])));

        $preParam = [
            'start_time'  => $preStartDate,
            'end_time'    => $preEndDate,
        ];

        //未来七天的时间
        $weekStartTime  =  date("Y-m-d");
        $weekEndTime    =  date("Y-m-d", strtotime("+7 day"));

        $weekParam = [
            'start_time'  => $weekStartTime,
            'end_time'    => $weekEndTime,
        ];


        //充值统计
        $rechargeStatistics = $this->getDayRechargeData($param, $preParam);
        //提现数据统计
        $withdrawStatistics = $this->getDayWithdrawData($param, $preParam);
        //零钱计划数据统计
        $currentStatistics  = $this->getCurrentStatistics($param);
        //定期数据统计
        $investStatistics   = $this->getInvestStatistics($param, $preParam);
        //用户统计
        $userStatistics     = $this->getUserStatistics($param, $preParam);

        //回款数据统计
        $refundRecordStatistics = $this->getRefundRecordStatistic($param, $preParam,$weekParam);



        $data = array_merge_recursive($rechargeStatistics, $withdrawStatistics, $currentStatistics, $investStatistics, $userStatistics, $refundRecordStatistics);
        $this->SendWebDataEmail($data, $startTime);
    }

    /**
     * @desc  当日充值统计数据
     * @author lgh
     * @param $param 当日参数
     * @param $preParam 前日参数
     * @return mixed
     */
    public function getDayRechargeData($param, $preParam){
        $orderLogic = new OrderLogic();
        /**********************************************[充值统计]****************************************************/
        //数据初始化
        $data['day']['rechargeTotalCash'] = 0.0;

        $data['all']['rechargeTotalCash'] = 0.0;

        $data['PreRechargeTotalCash']     = 0.0;

        foreach(RequestSourceLogic::$clientSource as $key => $val){

            //各来源当日充值数据统计
            $data['day'][$val.'RechargeCash'] = $this->getRechargeReturnCash(array_merge($param, ['app_request' => $val]));

            //各来源充值累计数据
            $data['all'][$val.'RechargeCash'] = $this->getRechargeReturnCash(['app_request' => $val]);

            //各来源前一日充值金额统计
            $data[$val.'PreRechargeCash']     = $this->getRechargeReturnCash(array_merge($preParam, ['app_request' => $val]));

            //各来源充值金额涨幅统计
            $data['increase_rate'][$val.'RechargeCash'] = ($data[$val.'PreRechargeCash'] == 0)? '∞' : round((($data['day'][$val.'RechargeCash'] - $data[$val.'PreRechargeCash'])/$data[$val.'PreRechargeCash'])*100, 2)."%";

            //当日充值数据总计
            $data['day']['rechargeTotalCash'] += $data['day'][$val.'RechargeCash'];

            //累计充值数据总计
            $data['all']['rechargeTotalCash'] += $data['all'][$val.'RechargeCash'];

            //前日累计充值金额总计
            $data['PreRechargeTotalCash'] += $data[$val.'PreRechargeCash'];

            unset($data[$val.'PreRechargeCash']);
        }

        //充值金额总计涨幅统计
        $data['increase_rate']['rechargeTotalCash'] = ($data['PreRechargeTotalCash'] == 0)? '∞' : round((($data['day']['rechargeTotalCash']-$data['PreRechargeTotalCash'])/$data['PreRechargeTotalCash'])*100, 2)."%";
        unset($data['PreRechargeTotalCash']);
        /**********************************************[充值统计]****************************************************/
        //支付渠道
        $payLimitLogic = new PayLimitLogic();

        $payType = $payLimitLogic->getPayTypeName() + $payLimitLogic->getOnlinePayTypeName();
        //支付渠道统计
        foreach($payType as $key=>$val){
            //当日各渠道充值金额
            $data['day'][$val['alias'].'RechargeCash'] = $this->getRechargeReturnCash(array_merge($param, ['pay_type' => $key]));
            //各渠道累计充值金额
            $data['all'][$val['alias'].'RechargeCash'] = $this->getRechargeReturnCash(['pay_type' => $key]);
        }
        //总充值人数
        $data['rechargeNum'] = $orderLogic->getRechargeStatistics([])['rechargeNum'];
        //今日充值人数
        $data['day']['rechargeNum'] = $orderLogic->getRechargeStatistics($param)['rechargeNum'];

        return $data;

    }

    /**
     * @desc 获取当日提现数据统计
     * @author lgh
     * @param $param
     * @param $preParam
     * @return mixed
     */
    public function getDayWithdrawData($param, $preParam){

        $withdrawLogic = new WithdrawLogic();
        //支付渠道
        $payLimitLogic = new PayLimitLogic();

        $payType = $payLimitLogic->getPayTypeName() + $payLimitLogic->getOnlinePayTypeName();
        /**********************************************[提现统计]****************************************************/
        //提现数据初始化
        $data['day']['withdrawTotalCash'] = 0.0;
        $data['all']['withdrawTotalCash'] = 0.0;
        $data['day']['withdrawTotalCashApply'] = 0.0;
        $data['all']['withdrawTotalCashApply'] = 0.0;
        $data['PreWithdrawTotalCash'] = 0.0;
        $data['PreWithdrawTotalCashApply'] = 0.0;

        foreach(RequestSourceLogic::$clientSource as $key => $val){
            /***********************[批准提现数据]***************************************/
            //各来源批准当日提现数据统计
            $data['day'][$val.'WithdrawCash'] =  $this->getWithdrawReturnCash(array_merge($param, ['app_request' => $val, 'status' => 200]));

            //各来源批准前一日提现金额统计
            $data[$val.'PreWithdrawCash']     = $this->getWithdrawReturnCash(array_merge($preParam, ['app_request' => $val, 'status' => 200]));

            //当日批准提现数据总计
            $data['day']['withdrawTotalCash'] += $data['day'][$val.'WithdrawCash'];

            //各来源批准提现金额累计
            $data['all'][$val.'WithdrawCash'] =  $this->getWithdrawReturnCash(['app_request' => $val, 'status' => 200]);

            //各来源批准提现累计金额总计
            $data['all']['withdrawTotalCash'] +=  $data['all'][$val.'WithdrawCash'];

            //各来源前一日批准提现金额总计
            $data['PreWithdrawTotalCash']   += $data[$val.'PreWithdrawCash'];

            //各来源批准提现金额涨幅统计
            $data['increase_rate'][$val.'WithdrawCash'] = ($data[$val.'PreWithdrawCash'] == 0)? '' : round((($data['day'][$val.'WithdrawCash']-$data[$val.'PreWithdrawCash'])/$data[$val.'PreWithdrawCash'])*100, 2)."%";
            /***********************[批准提现数据]***************************************/

            /*****************************[申请提现数据]***************************************/
            //各来源申请当日提现金额统计
            $data['day'][$val.'WithdrawCashApply'] =  $this->getWithdrawReturnCash(array_merge($param, ['app_request' => $val]));

            //各来源申请前一日提现金额统计
            $data[$val.'PreWithdrawCashApply']     = $this->getWithdrawReturnCash(array_merge($preParam, ['app_request' => $val]));

            //当日各来源申请提现数据总计
            $data['day']['withdrawTotalCashApply'] += $data['day'][$val.'WithdrawCashApply'];

            //各来源申请提现金额累计
            $data['all'][$val.'WithdrawCashApply'] =  $this->getWithdrawReturnCash(['app_request' => $val]);

            //各来源申请提现累计金额总计
            $data['all']['withdrawTotalCashApply'] +=  $data['all'][$val.'WithdrawCashApply'];

            //各来源前一日申请提现金额总计
            $data['PreWithdrawTotalCashApply']     += $data[$val.'PreWithdrawCashApply'];

            //各来源申请提现金额涨幅统计
            $data['increase_rate'][$val.'WithdrawCashApply'] = ($data[$val.'PreWithdrawCashApply'] == 0)? '∞' : round((($data['day'][$val.'WithdrawCashApply']-$data[$val.'PreWithdrawCashApply'])/$data[$val.'PreWithdrawCashApply'])*100, 2)."%";

            $data['day']['withdrawTotalNumApply'] = $withdrawLogic->getWithdrawStatistics($param)['withdrawNum'];
            /****************************[申请提现数据]***************************************/
            unset($data[$val.'PreWithdrawCashApply']);
            unset($data[$val.'PreWithdrawCash']);
        }

        //批准提现金额总计涨幅统计
        $data['increase_rate']['withdrawTotalCash'] = ($data['PreWithdrawTotalCash'] == 0)? '∞' : round((($data['day']['withdrawTotalCash']-$data['PreWithdrawTotalCash'])/$data['PreWithdrawTotalCash'])*100, 2)."%";

        //批准提现金额总计涨幅统计
        $data['increase_rate']['withdrawTotalCashApply'] = ($data['PreWithdrawTotalCashApply'] == 0)? '∞' : round((($data['day']['withdrawTotalCashApply']-$data['PreWithdrawTotalCashApply'])/$data['PreWithdrawTotalCashApply'])*100, 2)."%";
        unset($data['PreWithdrawTotalCash']);
        unset($data['PreWithdrawTotalCashApply']);
        /**********************************************[提现统计]****************************************************/
        return $data;
    }

    /**
     * @desc 零钱计划数据统计
     * @param $param
     * @return mixed
     */
    public function getCurrentStatistics($param){
        $currentInvestLogic = new CurrentLogic();
        $currentFundModel = new FundModel();
        //数据初始化
        $data['day']['currentTotalCashIn'] = 0.0;
        $data['day']['currentTotalCashOut'] = 0.0;
        $data['day']['currentTotalCashLeft'] = 0.0;
        $data['all']['currentTotalCashLeft'] = 0.0;

        #零钱计划资产统计
        $data['current_fund'] = $currentFundModel->getFundByDate($param['start_time']);
        foreach(RequestSourceLogic::$clientSource as $key => $val){
            //各平台当日零钱计划转入金额统计
            $data['day'][$val.'CurrentCashIn'] = (int)$currentInvestLogic->getCurrentStatistics(array_merge($param, ['app_request' => $val]),1)['cash'];

            //PC用户主动转入活期的金额
            $data['day']['pcCurrentCashManualIn'] = $currentInvestLogic->getCurrentStatistics(array_merge($param, ['app_request' => 'pc']),3)['cash'];
            //pc回款自动转入活期
            $data['day']['pcCurrentCashAutoIn'] = $currentInvestLogic->getCurrentStatistics(array_merge($param, ['app_request' => 'pc']),4)['cash'];


            //各平台当日零钱计划转入总计
            $data['day']['currentTotalCashIn'] += $data['day'][$val.'CurrentCashIn'];

            //各平台累计零钱计划转入统计
            $data['all'][$val.'CurrentCashIn'] = $currentInvestLogic->getCurrentStatistics(['app_request' => $val], 1)['cash'];

            //各平台当日零钱计划转出金额统计
            $data['day'][$val.'CurrentCashOut'] = (int)$currentInvestLogic->getCurrentStatistics(array_merge($param, ['app_request' => $val]),2)['cash'];

            //各平台当日零钱计划转出总计
            $data['day']['currentTotalCashOut'] += $data['day'][$val.'CurrentCashOut'];

            //各平台累计零钱计划转出统计
            $data['all'][$val.'CurrentCashOut'] = $currentInvestLogic->getCurrentStatistics(['app_request' => $val], 2)['cash'];

            //各平台当日留存资金
            $data['day'][$val.'CurrentCashLeft'] = $data['day'][$val.'CurrentCashIn'] - $data['day'][$val.'CurrentCashOut'];

            //各平台当日留存资金总计
            $data['day']['currentTotalCashLeft'] += $data['day'][$val.'CurrentCashLeft'];

            //各平台累计零钱计划资金留存
            $data['all'][$val.'CurrentCashLeft'] = $data['all'][$val.'CurrentCashIn'] - $data['all'][$val.'CurrentCashOut'];

            //各平台累计零钱计划总留存
            $data['all']['currentTotalCashLeft'] += $data['all'][$val.'CurrentCashLeft'];
        }
        //零钱计划投资总人数
        $data['currentInvestNum'] = $currentInvestLogic->getUserNum();
        //零钱计划转入金额
        $data['currentTotalCashIn'] = $currentInvestLogic->getCurrentStatistics([], 1)['cash'];
        //零钱计划转出金额
        $data['currentTotalCashOut'] = $currentInvestLogic->getCurrentStatistics([], 2)['cash'];
        //零钱计划留存
        $data['currentTotalCashLeft'] = $data['currentTotalCashIn'] - $data['currentTotalCashOut'];

        return $data;
    }

    /**
     * @desc  获取定期投资数据统计
     * @param $param
     * @param $preParam
     * @return mixed
     */
    public function getInvestStatistics($param, $preParam){

        $investLogic = new TermLogic();
        //总计数据初始化
        $data['day']['investTotalCash'] = 0.0;
        $data['preInvestTotalCash'] = 0.0;
        $data['all']['investTotalCash'] = 0.0;
        foreach(RequestSourceLogic::$clientSource as $key => $val){
            //各平台当日投资金额
            $data['day'][$val.'InvestCash'] = $investLogic->getInvestStatistics(array_merge($param, ['app_request' => $val]))['cash'];

            //各平台当日投资金额总计
            $data['day']['investTotalCash'] += $data['day'][$val.'InvestCash'];

            //各平台前一日投资金额
            $data[$val.'PreInvestCash'] = $investLogic->getInvestStatistics(array_merge($preParam, ['app_request' => $val]))['cash'];

            //各平台前一日投资金额总计
            $data['preInvestTotalCash'] += $data[$val.'PreInvestCash'];

            //各平台投资数据涨幅
            $data['increase_rate'][$val.'InvestCash'] = ($data[$val.'PreInvestCash'] == 0) ? '∞' : round((($data['day'][$val.'InvestCash'] - $data[$val.'PreInvestCash'])/$data[$val.'PreInvestCash'])*100, 2)."%";

            //各平台累计投资金额
            $data['all'][$val.'InvestCash'] = $investLogic->getInvestStatistics(['app_request' => $val])['cash'];

            //各平台累计投资总额
            $data['all']['investTotalCash'] +=  $data['all'][$val.'InvestCash'];
            unset($data[$val.'PreInvestCash']);
        }
        //投资累计涨幅
        $data['increase_rate']['investCash'] = ($data['preInvestTotalCash'] == 0) ? '∞' : round((($data['day']['investTotalCash']-$data['preInvestTotalCash'])/$data['preInvestTotalCash'])*100, 2)."%";
        unset($data['preInvestTotalCash']);
        //投资用户总数
        $data['investNum'] = $investLogic->getInvestStatistics([])['investNum'];
        $data['day']['investNum'] = $investLogic->getInvestStatistics($param)['investNum'];
        return $data;
    }

    /**
     * @param $param
     * @param $preParam
     * @param $weekParam
     * @return mixed
     */
    public function getRefundRecordStatistic($param, $preParam,$weekParam){

        $refundRecordModel = new RefundModel();

        //今日回款数据金额
        $data['refund']['today_cash'] = 0.0;
        $todayCash = $refundRecordModel->getRefundTotalGroupByTime( $param );
        if( !empty( $todayCash ) )
        {
            $data['refund']['today_cash'] = $todayCash[0]['cash'];
        }

        //未来七天回款金额
        $data['refund']['week_cash'] = $refundRecordModel->getRefundTotalGroupByTime($weekParam);

        return $data;
    }

    /**
     * @desc 用户相关信息统计
     * @param $param
     * @param $preParam
     * @return mixed
     */
    public function getUserStatistics($param, $preParam){

        $userLogic = new UserLogic();
        $userInfoModel = new UserInfoModel();
        //累计用户信息统计
        $userAll = $userLogic->getUserStatistics([]);

        //注册用户总数
        $data['registerNum'] = $userAll['registerNum'];
        //实名用户总数
        $data['realNameNum'] = $userAll['realNameNum'];
        //当前账户存量余额
        $data['totalBalance'] = $userAll['totalBalance'];
        //余额用户总数
        $data['balanceNum'] = $userAll['balanceNum'];

        //当日用户信息统计
        $userDay = $userLogic->getUserStatistics($param);
        //注册用户总数
        $data['day']['registerNum'] = $userDay['registerNum'];
        //实名用户总数
        $data['day']['realNameNum'] = $userDay['realNameNum'];
        //当前账户存量余额
        $data['day']['totalBalance'] = $userDay['totalBalance'];
        //余额用户总数
        $data['day']['balanceNum'] = $userDay['balanceNum'];

        //平台数据初始化
        $data['day']['userTotalNum'] = 0;
        $data['all']['userTotalNum'] = 0;
        foreach(RequestSourceLogic::$clientSource as $key=>$val){
            //当日各平台用户数
            $data['day'][$val.'UserNum'] = $userInfoModel->getUserInfoStatistics(array_merge($param,['app_request' => $key]))['userNum'];

            //各平台累计用户数
            $data['all'][$val.'UserNum'] = $userInfoModel->getUserInfoStatistics(['app_request' => $key])['userNum'];

            //当日各平台用户数总计
            $data['day']['userTotalNum'] += $data['day'][$val.'UserNum'];

            //各平台累计用户数总计
            $data['all']['userTotalNum'] += $data['all'][$val.'UserNum'];
        }
        return  $data;
    }

    /**
     * @desc 获取充值统计返回的金额
     * @author lgh
     * @param $param
     * @return string
     */
    public function getRechargeReturnCash($param){

        $orderLogic = new OrderLogic();

        $result = $orderLogic->getRechargeStatistics($param);

        if(!empty($result)){
            return $result['cash'];
        }
        return 0.0;
    }

    /**
     * @desc 获取提现统计返回的金额
     * @param $param
     * @return float
     */
    public function getWithdrawReturnCash($param){
        $withdrawLogic = new WithdrawLogic();

        $result = $withdrawLogic->getWithdrawStatistics($param);

        if(!empty($result)){
            return $result['cash'];
        }
        return 0.0;
    }

    /**
     * @desc   格式化发送当日数据统计的格式
     * @param  $data
     * @param  $startTime
     * @return string
     */
    public function SendWebDataEmail($data, $startTime){



        $date  = date("Y年m月d日",strtotime($startTime));
        $title = "九斗鱼统计报表_{$date}";
        $body = "<br/>";
        $body  .= $this->getCss();
        $body  .="<p><h4>".$startTime."当日统计<h4><p>";
        //充值数据
        $body .="<p><h4>充值统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th></tr>
        <tr><td>当日</td><td>".$data['day']['iosRechargeCash']."</td><td>".$data['day']['androidRechargeCash']."</td><td>".$data['day']['wapRechargeCash']."</td><td>".$data['day']['pcRechargeCash']."</td><td>".$data['day']['rechargeTotalCash']."</td></tr>
        <tr><td>涨幅</td><td>".$data['increase_rate']['iosRechargeCash']."</td><td>".$data['increase_rate']['androidRechargeCash']."</td><td>".$data['increase_rate']['wapRechargeCash']."</td><td>".$data['increase_rate']['pcRechargeCash']."</td><td>".$data['increase_rate']['rechargeTotalCash']."</td></tr>
        <tr><td>累计</td><td>".$data['all']['iosRechargeCash']."</td><td>".$data['all']['androidRechargeCash']."</td><td>".$data['all']['wapRechargeCash']."</td><td>".$data['all']['pcRechargeCash']."</td><td>".$data['all']['rechargeTotalCash']."</td></tr>
        </tr></table>
        ";
        //支付渠道统计
        $body .="<p><h4>支付渠道统计：</h4></p>";
        $body .="<table class='table'><tr><th>类型</th><th>宝付支付</th><th>易宝认证支付</th><th>先锋支付</th><th>丰付网银</th><th>丰付支付</th><th>融宝代扣</th><tr><tr><td>当日</td><td>".$data['day']['BFAuthRechargeCash']."</td><td>".$data['day']['YeeAuthRechargeCash']."</td><td>".$data['day']['UCFAuthRechargeCash']."</td><td>".$data['day']['SumaOnlineRechargeCash']."</td><td>".$data['day']['SumaAuthRechargeCash']."</td><td>".$data['day']['ReaWithholdingRechargeCash']."</td></tr><tr><td>累计</td><td>".$data['all']['BFAuthRechargeCash']."</td><td>".$data['all']['YeeAuthRechargeCash']."</td><td>".$data['all']['UCFAuthRechargeCash']."</td><td>".$data['all']['SumaOnlineRechargeCash']."</td><td>".$data['all']['SumaAuthRechargeCash']."</td><td>".$data['all']['ReaWithholdingRechargeCash']."</td></tr></table>";

        //成功提现数据
        $body .="<p><h4>提现成功数据：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th></tr>
        <tr><td>当日</td><td>".$data['day']['iosWithdrawCash']."</td><td>".$data['day']['androidWithdrawCash']."</td><td>".$data['day']['wapWithdrawCash']."</td><td>".$data['day']['pcWithdrawCash']."</td><td>".$data['day']['withdrawTotalCash']."</td></tr>
        <tr><td>涨幅</td><td>".$data['increase_rate']['iosWithdrawCash']."</td><td>".$data['increase_rate']['androidWithdrawCash']."</td><td>".$data['increase_rate']['wapWithdrawCash']."</td><td>".$data['increase_rate']['pcWithdrawCash']."</td><td>".$data['increase_rate']['withdrawTotalCash']."</td></tr>
        <tr><td>累计</td><td>".$data['all']['iosWithdrawCash']."</td><td>".$data['all']['androidWithdrawCash']."</td><td>".$data['all']['wapWithdrawCash']."</td><td>".$data['all']['pcWithdrawCash']."</td><td>".$data['all']['withdrawTotalCash']."</td></tr>
        </tr></table>
        ";

        //申请提现数据
        $body .="<p><h4>申请提现数据：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th><th>当天回款金额</th></tr>
        <tr><td>当日</td><td>".$data['day']['iosWithdrawCashApply']."</td><td>".$data['day']['androidWithdrawCashApply']."</td><td>".$data['day']['wapWithdrawCashApply']."</td><td>".$data['day']['pcWithdrawCashApply']."</td><td>".$data['day']['withdrawTotalCashApply']."</td><td>".$data['refund']['today_cash']."</td></tr>
        <tr><td>涨幅</td><td>".$data['increase_rate']['iosWithdrawCashApply']."</td><td>".$data['increase_rate']['androidWithdrawCashApply']."</td><td>".$data['increase_rate']['wapWithdrawCashApply']."</td><td>".$data['increase_rate']['pcWithdrawCashApply']."</td><td>".$data['increase_rate']['withdrawTotalCashApply']."</td><td></td></tr>
        <tr><td>累计</td><td>".$data['all']['iosWithdrawCashApply']."</td><td>".$data['all']['androidWithdrawCashApply']."</td><td>".$data['all']['wapWithdrawCashApply']."</td><td>".$data['all']['pcWithdrawCashApply']."</td><td>".$data['all']['withdrawTotalCashApply']."</td><td></td></tr>
        </tr></table>
        ";
        //提现统计
        $body .="<p><h4>提现统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>提现笔数</th><th>提现金额</th></tr>
        <tr><td>".$data['day']['withdrawTotalNumApply']."</td><td>".$data['day']['withdrawTotalCashApply']."</td></tr>
        </table>";

        //零钱计划分平台数据统计
        $body .="<p><h4>零钱计划分平台金额统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th></tr>
        <tr><td>当日转入</td><td>".$data['day']['iosCurrentCashIn']."</td><td>".$data['day']['androidCurrentCashIn']."</td><td>".$data['day']['wapCurrentCashIn']."</td><td>".$data['day']['pcCurrentCashIn']."</td><td>".$data['day']['currentTotalCashIn']."</td></tr>
        <tr><td>当日转出</td><td>".$data['day']['iosCurrentCashOut']."</td><td>".$data['day']['androidCurrentCashOut']."</td><td>".$data['day']['wapCurrentCashOut']."</td><td>".$data['day']['pcCurrentCashOut']."</td><td>".$data['day']['currentTotalCashOut']."</td></tr>
        </tr></table>
        ";

        //零钱计划统计
        $body .="<p><h4>零钱计划统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>回款自动转入零钱计划</th><th>转入金额</th><th>转出金额</th><th>总人数</th><th>零钱计划账户总额</th></tr>
        <tr><td>金额</td><td>".(int)$data['day']['pcCurrentCashAutoIn']."</td><td>".(int)$data['current_fund']['total_invest_in']."</td><td>".(int)$data['current_fund']['total_invest_out']."</td><td>".(int)$data['currentInvestNum']."</td><td>".(int)$data['current_fund']['cash']."</td></tr>
        </table>
        ";

        //投资统计
        $body .="<p><h4>投资统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th></tr>
        <tr><td>当日</td><td>".$data['day']['iosInvestCash']."</td><td>".$data['day']['androidInvestCash']."</td><td>".$data['day']['wapInvestCash']."</td><td>".$data['day']['pcInvestCash']."</td><td>".$data['day']['investTotalCash']."</td></tr>
        <tr><td>涨幅</td><td>".$data['increase_rate']['iosInvestCash']."</td><td>".$data['increase_rate']['androidInvestCash']."</td><td>".$data['increase_rate']['wapInvestCash']."</td><td>".$data['increase_rate']['pcInvestCash']."</td><td>".$data['increase_rate']['investCash']."</td></tr>
        <tr><td>累计</td><td>".$data['all']['iosInvestCash']."</td><td>".$data['all']['androidInvestCash']."</td><td>".$data['all']['wapInvestCash']."</td><td>".$data['all']['pcInvestCash']."</td><td>".$data['all']['investTotalCash']."</td></tr>
        </tr></table>
        ";

        //未来七天回款金额
        $body .="<p><h4>未来七天回款金额：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th>";
        $weekData = ToolArray::arrayToKey($data['refund']['week_cash'], 'times');
        foreach($weekData as $key => $val){
            $body.="<th>".$key."</th>";
        }
        $body.= "<th>总计</th></tr><tr><td>金额</td>";
        $totalCash = 0.0;
        foreach($weekData as $key => $val){
            $body.="<td>".$val['cash']."</td>";
            $totalCash += $val['cash'];
        }
        $body.= "<td>".$totalCash."</td></tr></table>";

        //用户统计
        $body .="<p><h4>用户统计：</h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>Ios</th><th>Android</th><th>Wap</th><th>Pc</th><th>总计</th></tr>
        <tr><td>当日</td><td>".$data['day']['iosUserNum']."</td><td>".$data['day']['androidUserNum']."</td><td>".$data['day']['wapUserNum']."</td><td>".$data['day']['pcUserNum']."</td><td>".$data['day']['userTotalNum']."</td></tr>
        <tr><td>累计</td><td>".$data['all']['iosUserNum']."</td><td>".$data['all']['androidUserNum']."</td><td>".$data['all']['wapUserNum']."</td><td>".$data['all']['pcUserNum']."</td><td>".$data['all']['userTotalNum']."</td></tr>
        </tr></table>
        ";
        $body .="<p><h4></h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>注册用户数</th><th>实名用户数</th><th>充值人数</th><th>投资人数</th><th>充值金额</th><th>定期总额</th><th>总额</th></tr>
        <tr><td>当日</td><td>".$data['day']['registerNum']."</td><td>".$data['day']['realNameNum']."</td><td>".$data['day']['rechargeNum']."</td><td>".$data['day']['investNum']."</td><td>".$data['day']['rechargeTotalCash']."</td><td>". $data['all']['investTotalCash']."</td><td>".($data['all']['investTotalCash']+$data['current_fund']['cash'])."</td></tr>
        </table>
        ";
        $body .="<p><h4></h4></p>";
        $body .="<table class='table'>
        <tr><th>类型</th><th>注册用户总数</th><th>实名用户总数</th><th>充值用户总数</th><th>投资用户总数</th><th>累计充值金额</th><th>累计投资金额</th><th>当前账户存量余额</th><th>余额用户总数</th></tr>
        <tr><td>累计</td><td>".$data['registerNum']."</td><td>".$data['realNameNum']."</td><td>".$data['rechargeNum']."</td><td>".$data['investNum']."</td><td>".$data['all']['rechargeTotalCash']."</td><td>".$data['all']['investTotalCash']."</td><td>". $data['totalBalance']."</td><td>". $data['balanceNum']."</td></tr>
        </table>
        ";

        $emailModel = new EmailModel();
        //接受者邮箱
        $receiveEmails = $this->getMailTaskEmailConfig('webData');

        //发送邮件
        $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body);

        if($result['status'] == false){
            Log::info('每日数据统计邮件发送:'.\GuzzleHttp\json_encode($result));
        }else{
            Log::info('每日数据统计邮件发送成功');
        }

    }

    /**
     * @desc 获取表单css
     * @return string
     */
    public function getCss(){

        $css = "<style type='text/css'>
	.table{border-collapse:collapse; }
	.table th,.table td{border:1px solid #ccc;line-height:30px;padding:5px 2px;text-align:center;}
	</style>";

        return $css;
    }

    /**
     * @desc 获取统计数据邮件的接收者
     * @param $key
     * @return array
     */
    public function getMailTaskEmailConfig($key){
        $receiveEmails = [];

        $mailTaskConfig = SystemConfigModel::getConfig('MAILTASK');

        $mailArr = explode(';',$mailTaskConfig[$key]);

        foreach($mailArr as $key=>$val){

            $mail = explode('|', $val);

            $receiveEmails[$mail[0]] = $mail[1];
        }
        return $receiveEmails;
    }




}
