<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/21
 * Time: 上午10:36
 * Desc: 还款成功发送邮件
 */

namespace App\Listeners\Email;

use App\Http\Logics\Refund\RefundRecordLogic;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundSuccessListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {



    }


    /**
     * 接收参数，发送邮件
     */
    public function handle($data)
    {

        $times = $data['end_time'];

        $logic = new RefundRecordLogic();

        $logic->sendRefundSuccessByTime($times);

    }
}