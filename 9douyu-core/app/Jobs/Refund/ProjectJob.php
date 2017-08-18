<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/23
 * Time: 下午12:47
 */

namespace App\Jobs\Refund;

use App\Http\Logics\Refund\ProjectLogic;
use App\Jobs\Job;
use Log;

class ProjectJob extends Job{

    protected $data = '';

    const   TYPE_DO_REFUND      = 100,  //执行回款
            TYPE_CREATE_REFUND  = 200;  //生成回款记录


    public function __construct($data)
    {

        $this->data = $data;

    }

    /**
     * 执行回款
     */
    public function handle()
    {

        $data = $this->data;

        if( empty($data) ){

            \Log::Error('JobRefundProjectError',$data);

            return false;

        }

        $size = $data['size'];

        $times = $data['times'];

        $logic = new ProjectLogic();

        $logic->doRefund($times, $size);

    }


}