<?php
/**
 * create by Vim
 * Author:  linguanghui
 * Date: 17/05/02 Time 12:04 AM
 * Desc: 每天09:30统计当日充值及提现的总金额的数据
 */

namespace App\Http\Logics\Data;

use App\Http\Logics\Logic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\Order\WithdrawLogic;
use App\Tools\ToolTime;
use App\Http\Models\Common\ServiceApi\EmailModel;
use Log;

class RechargeAndWithdrawDayDataLogic extends Logic
{

    /**
     * @desc 获取当日数据充值体现的数据信息
     * @return array
     */
    public function sendRechargeWithdrawData( )
    {

        $param = [
            'start_time'  => ToolTime::dbDate(),
            ];

        $date = date( 'Y年m月d日', strtotime( ToolTime::dbNow() ) );

        $title =  $date.'-平台充值成功及申请提现的金额统计';

        $orderLogic = new OrderLogic();

        //充值数据
        $rechargeData = $orderLogic->getRechargeStatistics($param);

        $withdrawLogic = new WithdrawLogic();

        //提现的数据
        $withdrawData = $withdrawLogic->getWithdrawStatistics( $param );

        $formatEmaliData = $this->formatRechargeWithdrawEmail( $rechargeData, $withdrawData , $title );

        $this->sendRechargeWithdrawDayEmail( $title, $formatEmaliData );
    }

    /**
     * @desc 格式化充值提现的邮件内容
     * @param $rechargeData array 充值数据
     * @param $withdrawData array 提现数据
     * @param $title string 邮件标题
     * @return array
     */
    public function formatRechargeWithdrawEmail( $rechargeData, $withdrawData, $title='' )
    {

        $dataStatisticsLogic = new DataStatisticsLogic();

        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<p><h4>".$title."</h4></p>";
        $body .="<table class='table'><tr><th>今日充值成功金额</th><th>申请提现总金额</th></tr>";
        $body .="<tr><td>{$rechargeData['cash']} 元</td><td>{$withdrawData['cash']} 元</td></tr>";
        $body .="</table>";

        return $body;
    }

    /**
     * @desc 每日充值提现金额统计执行邮件发送
     * @param $title
     * @param $body
     * @param  $savePath
     */
     public function sendRechargeWithdrawDayEmail($title,$body, $savePath=''){

         $emailModel =  new EmailModel();

         $dataStatisticsLogic = new DataStatisticsLogic();
         $emailModel = new EmailModel();
         //接受者邮箱
         $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('rechargeWithdrawData');
         //发送邮件
         $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body );

         if($result['status'] == false){
             Log::info('每日充值提现数据邮件发送:'.\GuzzleHttp\json_encode($result));
         }else{
             Log::info('每日充值提现数据邮件发送成功');
         }
     }
}
