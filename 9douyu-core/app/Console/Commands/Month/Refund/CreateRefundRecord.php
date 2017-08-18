<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/15
 * Time: 下午2:51
 * Desc: 新系统上线后执行该脚本生成计划任务
 */

namespace App\Console\Commands\Month\Refund;

use App\Http\Logics\Invest\InvestLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Refund\RefundRecordLogic;
use App\Http\Logics\Refund\ProjectLogic as RefundProjectLogic;
use Illuminate\Console\Command;
use Log;

class CreateRefundRecord extends Command{

    //计划任务唯一标识
    protected $signature = 'CreateRefundRecord';

    //计划任务描述
    protected $description = '新系统上线后执行该脚本生成计划任务.';

    public function handle()
    {

        //1.获取投资中状态，回款状态为先息后本,有投资且未投满 3,6,12的项目信息
        $logic = new ProjectLogic();

        $projectIds = $logic->getUnRefundProject();
        
        if($projectIds){

            //2.查询指定项目已存在回款的项目信息
            $refundLogic = new RefundRecordLogic();

            $refundProjectIds = $refundLogic->getRefundedProjectIdByIds($projectIds);

            //3.筛选已生成回款计划的项目
            if($refundProjectIds){

                $ids = array_diff($projectIds,$refundProjectIds);
            }else{

                $ids = $projectIds;
            }

            if(empty($ids)){

                Log::error(__METHOD__.'Error',['msg' => '没有未生成投资计划的项目']);
                exit();
            }

            //4.获取指定项目的投资记录
            $investLogic = new InvestLogic();

            $investList = $investLogic->getInvestListByProjectIds($ids);

            if($investList){

                $logic = new RefundProjectLogic();

                //5.循环生成相应的还款计划
                foreach ($investList as $invest){

                    $investId = $invest['id'];

                    $result = $logic->createRecord($investId);

                    if($result['status']){

                        Log::info(__METHOD__.'Error',['msg' => '生成回款计划成功','invest_id' => $investId]);

                    }else{
                        Log::error(__METHOD__.'Error',['msg' => '生成回款计划失败','invest_id' => $investId]);

                    }

                }
            }else{

                Log::error(__METHOD__.'Error',['msg' => '没有发现投资记录','data' => $ids]);

            }


        }else{


            Log::error(__METHOD__.'Error',['msg' => '没有发现未生成回款计划的项目']);
        }
        





    }

}