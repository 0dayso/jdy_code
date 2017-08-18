<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:10
 * Desc: 支付回调处理成功 绑卡 + 记录成功充值金额 + 充值金额进零钱计划
 */
namespace App\Jobs\Order;

use App\Http\Logics\BankCard\RechargeLogic as RechargeCardLogic;
use App\Http\Logics\Invest\CurrentLogic;
use App\Jobs\Job;

class RechargeSuccessJob extends Job{

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
        $cash   = $this->data['cash'];

        //绑卡
        $cardLogic = new RechargeCardLogic();
        $cardLogic->bindCard($orderId,$userId);

        /*已迁移至模块
        //更新用户成功充值记录
        $rechargeLogic = new RechargeLogic();
        $rechargeLogic->updateUserRechargeRecord($orderId,$userId,$cash);
        */
        /*
        //充值金额转零钱计划
        $currentLogic = new CurrentLogic();
        $currentLogic->invest($userId,0,$cash);
        */
    }
}