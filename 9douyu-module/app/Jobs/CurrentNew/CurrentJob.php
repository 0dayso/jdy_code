<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/31
 * Time: 下午7:21
 */

namespace App\Jobs\CurrentNew;


use App\Http\Logics\CurrentNew\ProjectLogic;
use App\Jobs\Job;

class CurrentJob extends Job
{

    const   TYPE_SPLIT_INVEST_OUT   = 1,    //分拆申请转出数量
            TYPE_DO_INVEST_OUT      = 2;    //执行转出操作

    protected  $data = null;

    /**
     * Create a new job instance.
     * CurrentJob constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * 统一入口
     */
    public function handle()
    {

        if( isset($this->data['type']) && $this->data['type'] == CurrentJob::TYPE_SPLIT_INVEST_OUT ){

            $this->doSplitInvestOut($this->data['data']);

        }elseif( $this->data['type'] == CurrentJob::TYPE_DO_INVEST_OUT ){

            $this->doInvestOut($this->data['data']);

        }

    }

    /**
     * @param $date
     * @desc 拆分转出
     * @ php
     */
    public function doSplitInvestOut( $date ){

        $logic = new ProjectLogic();

        $logic->doSplitInvestOut($date);

    }

    /**
     * @param $data
     * @desc 执行
     * @ artisan queue:listen --queue=doCurrentNewInvestOut
     */
    public function doInvestOut( $data ){

        $logic = new ProjectLogic();

        $logic->doCurrentNewInvestOut( $data );

    }

}