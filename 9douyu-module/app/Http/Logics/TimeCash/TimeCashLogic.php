<?php
/**
 * @desc    与快金对接LOGIC
 * @date    2017-03-02
 */

namespace App\Http\Logics\TimeCash;

use App\Http\Dbs\TimeCash\TimeCashLoanDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\WarningLogic;

class TimeCashLogic extends Logic
{

    /**
     * @param   $data
     * @return  bool
     * @desc    添加借款信息
     */
    public static function doAddLoan( $data=[] )
    {
        #借款额度
        $loanAmountArr  = TimeCashLogic::getLoanAmount();
        #借款期限
        $loanTimeArr    = TimeCashLogic::getLoanTime();
        #还款方式
        $refundTypeArr  = TimeCashLogic::getRefundType();

        $data['loan_amount']= isset($loanAmountArr[$data['loan_amount']])?$loanAmountArr[$data['loan_amount']]:"";
        $data['loan_time']  = isset($loanTimeArr[$data['loan_time']])?$loanTimeArr[$data['loan_time']]:"";
        $data['refund_type']= isset($refundTypeArr[$data['refund_type']])?$refundTypeArr[$data['refund_type']]:"";

        if(empty($data['name'])){
            return self::callError( '请填写借款人姓名~');
        }
        if(empty($data['phone'])){
            return self::callError( '请填写借款人手机号~');
        }
        $pattern        = '/^(13\d|14[57]|15[012356789]|18\d|17[01678])\d{8}$/';
        if(!preg_match($pattern, $data['phone'])) {
            return self::callError( '请填写正确手机号~');
        }
        if(empty($data['loan_amount'])){
            return self::callError( '请填写借款金额~');
        }
        if(empty($data['loan_time'])){
            return self::callError( '请填写借款期限~');
        }
        if(empty($data['refund_type'])){
            return self::callError( '请填写借款类型~');
        }

        #获取手机号-每日限制次数
        $configData = WarningLogic::getConfigDataByKey('TIMECASH_LOAN_RECEIVE_ADMIN');
        $dayLimit   = isset($configData['value']['DAY_LIMIT']) ? $configData['value']['DAY_LIMIT'] : 10;

        #获取手机号当日申请次数
        $statTime   = date('Y-m-d');
        $db         = new TimeCashLoanDb();
        $phoneLimit = $db->getLoanCountByPhone($data['phone'], $statTime);
        if($phoneLimit >= $dayLimit){

            return self::callError( '当日申请次数已上限！');
        }
        $result = $db->addLoan($data);

        if(!$result){

            return self::callError( '借款申请失败~');
        }

        return self::callSuccess();

    }

    /**
     * @desc    获取要借款信息-给快金发邮件
     * @return  mixed
     *
     */
    public static function getLoanRecord(){
        $startTime = date('Y-m-d', strtotime(' -1 day'));
        $endTime   = date('Y-m-d');

        $db = new TimeCashLoanDb();

        $resData = $db->getLoanRecord($startTime, $endTime);

        if(!empty($resData)){
            $sendMsg   = "<table border='1' cellspacing='0' cellpadding='0'><tr><td>借款人姓名</td><td>借款人手机号</td><td>借款额度</td><td>借款期限</td><td>还款方式</td></tr>";
            foreach ($resData as $kk=>$value){
                $sendMsg    .= "<tr><td>".$value["name"]."</td><td>".$value["phone"]."</td><td>".$value["loan_amount"]."</td><td>".$value["loan_time"]."</td><td>".$value["refund_type"]."</td></tr>";
            }
            $sendMsg .="</table>";

            #echo $sendMsg;exit;

            #获取邮件接收者
            $configData     = WarningLogic::getConfigDataByKey('TIMECASH_LOAN_RECEIVE_ADMIN');

            $arr['subject'] = $sendMsg;

            $arr['title']   = '【我要借款】-统计日期：'.$startTime." 借款信息";

            WarningLogic::doSendEmail($configData, $arr);
        }


    }


    /**
     * @desc    借款额度
     **/
    public static function getLoanAmount(){
        return [
            1 =>'3000元以下',
            2 =>'3000-1万元',
            3 =>'1万-5万元',
            4 =>'5万-10万元',
            5 =>'10万元以上',
        ];

    }

    /**
     * @desc    借款期限
     **/
    public static function getLoanTime(){
        return [
            1 => '一周以内',
            2 => '1个月以内',
            3 => '1-3个月',
            4 => '3-6个月',
            5 => '6个月以上',
        ];

    }

    /**
     * @desc    还款方式
     **/
    public static function getRefundType(){
        return [
            1 => '等额本息',
            2 => '到期还本息',
            3 => '先息后本',
        ];

    }

}