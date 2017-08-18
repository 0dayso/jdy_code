<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/8
 * Time: 11:14
 */


namespace App\Listeners\Api\Current;

use App\Events\ExampleEvent;
use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use App\Http\Logics\Warning\CurrentLogic;
use App\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\EmailModel;
use App\Lang\LangModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Lumen\Application;

class RefundAutoInvestListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $data
     * @return void
     */
    public function handle($data)
    {
        $refundList = json_decode($data['auto_invest_list'],true);

        $userNumber = 0;
        $totalCash = 0;

        foreach($refundList as $val){

            $userNumber ++;
            $totalCash += $val['cash'];
            //$list[] = '用户id : '.$val['user_id'].';回款金额:'.$val['cash'].'元';
        }

        $str= '今日回款进零钱计划人数:'.$userNumber."人;\r\n".
            '今日回款进零钱计划总金额:'.$totalCash.'元';


        CurrentLogic::doRefundToCurrentSuccessNotice($str);


    }
}