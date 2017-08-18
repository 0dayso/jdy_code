<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/26
 * Time: 上午11:17
 * Desc: 提前回款发送的短信
 */

namespace App\Listeners\Refund;

use App\Http\Logics\Refund\RefundRecordLogic;
use Illuminate\Contracts\Queue\ShouldQueue;

class BeforeRefundSmsListener implements ShouldQueue{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {



    }


    /**
     * 接收参数，自动发送提前回款短信
     */
    public function handle($data)
    {

        $projectIds = $data['project_ids'];

        $times = $data['end_time'];

        $logic = new RefundRecordLogic();

        $logic->sendBeforeNoticeListByProjectIdTimes($projectIds, $times);


    }

}