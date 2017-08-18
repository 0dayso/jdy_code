<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天9:30以邮件形式发送提现邮件
 */
namespace App\Console\Commands\Day\Withdraw;
use Illuminate\Console\Command;
use App\Http\Logics\Order\OperateLogic;

class SendWithdrawEmail extends Command{

    //计划任务唯一标识
    protected $signature = 'SendWithdrawEmail';

    //计划任务描述
    protected $description = '每天08:10或15:10创建提现数据';

    
    public function handle(){

        $hour = date('G');
        $startTime = $endTime = '';

        if($hour>=8 && $hour <15){

            $startTime = date('Y-m-d 15:00:00',strtotime("-1 day"));
            $endTime   = date('Y-m-d 08:00:00');

        }elseif($hour >= 15 and $hour <= 23){

            $startTime   = date('Y-m-d 08:00:00');
            $endTime     = date('Y-m-d 15:00:00');

        }

        if($startTime && $endTime){

            $logic = new OperateLogic();
            //$logic->sendWithdrawEmail($startTime,$endTime);
            $logic->addWithdrawRecord($startTime, $endTime);

        }




    }
}