<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:10
 * Desc: 提现短信批量发送
 */
namespace App\Jobs\Order;

use App\Jobs\Job;
use App\Http\Logics\Order\OperateLogic;

class BatchSendWithdrawMsgJob extends Job{

    protected  $data = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logic = new OperateLogic();
        $logic->batchSendWithdrawMsg($this->data['id']);

    }
}