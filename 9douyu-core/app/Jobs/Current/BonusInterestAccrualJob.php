<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:10
 * Desc: 零钱计划加息券计息任务
 */
namespace App\Jobs\Current;

use App\Jobs\Job;
use App\Http\Logics\Module\Current\BonusLogic;

class BonusInterestAccrualJob extends Job{

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

        $logic = new BonusLogic();
        $logic->batchInterestAccrual($this->data);

    }
}