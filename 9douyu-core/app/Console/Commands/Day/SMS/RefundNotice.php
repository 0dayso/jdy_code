<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/23
 * Time: 上午10:30
 * Desc: 回款短信通知
 */

namespace  App\Console\Commands\Day\SMS;


use App\Http\Logics\Refund\ProjectLogic;
use Illuminate\Console\Command;

class RefundNotice extends Command{


    //计划任务唯一标识
    protected $signature = 'RefundNotice';

    //计划任务描述
    protected $description = '每天上午10点发送【明日】回款提醒短信';


    public function handle()
    {

        $refundLogic = new ProjectLogic();

        $refundLogic->splitRefundToJob();

    }



}