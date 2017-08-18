<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/26
 * Time: 下午2:26
 * Desc: 零钱计划计息
 */

namespace App\Jobs\Refund;

use App\Http\Logics\CurrentNew\ProjectLogic;
use App\Jobs\Job;

class CurrentJob extends Job
{

    const   TYPE_SPLIT_REFUND   = 1,    //分拆回款计息数量
            TYPE_DO_REFUND      = 2;    //执行计息

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
     * 统一入口
     */
    public function handle()
    {

        if( isset($this->data['type']) && $this->data['type'] == CurrentJob::TYPE_SPLIT_REFUND ){

            $this->doSplitRefund($this->data['rate']);

        }elseif( $this->data['type'] == CurrentJob::TYPE_DO_REFUND ){

            $this->doRefund($this->data['data']);

        }

    }

    /**
     * @param $rate
     * @desc 拆分回款
     * @ artisan queue:listen --queue=doSplitRefund
     */
    public function doSplitRefund($rate)
    {

        $logic = new ProjectLogic();

        $logic->splitRefund($rate);

    }

    /**
     * @param
     * @desc 执行计息
     * @ artisan queue:listen --queue=doRefundCurrent
     */
    public function doRefund( $data )
    {

        $logic = new ProjectLogic();

        $logic->doRefund($data);

    }



}

