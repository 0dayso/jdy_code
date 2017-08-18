<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:10
 * Desc: 提现申请提交至银行，给用户发送通知短信
 */
namespace App\Jobs\Order;

use App\Jobs\Job;

class WithdrawSubmitToBankSuccessJob extends Job{

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
        $orderId = $this->data['order_id'];//订单号
        $userId = $this->data['user_id'];//用户ID

        var_dump($this->data);

    }
}