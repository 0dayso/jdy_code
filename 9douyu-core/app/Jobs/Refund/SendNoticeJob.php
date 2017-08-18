<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/23
 * Time: 上午11:11
 * Desc: 发送回款提醒信息
 */

namespace App\Jobs\Refund;

use App\Http\Logics\SMS\SendRefundNoticeLogic;
use App\Jobs\Job;
use Log;

class SendNoticeJob extends Job{


    protected $data = '';

    public function __construct($data)
    {

        $this->data = $data;

    }


    /**
     * @param $data
     * @desc 执行发送短息
     */
    public function handle()
    {

        if( empty($this->data) ){

            Log::Error('SendNoticeJobEmpty');

        }

        $sendRefundNotice = new SendRefundNoticeLogic();

        $sendRefundNotice->doSend($this->data);
        

    }

}