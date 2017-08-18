<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/10
 * Time: 上午11:11
 */

namespace App\Listeners\Pay\RechargeCheck;


use App\Events\Pay\RechargeBatchEvent;
use App\Http\Dbs\Order\CheckBatchDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Recharge\CheckBatchLogic;
use App\Http\Logics\Recharge\CheckOrderLogic;
use Log;

class CheckBatchTransferListener
{

    public function handle( RechargeBatchEvent $event)
    {
        //请求数据组装
        /**
         * $formatItem[]=[
            'order_id'  => $statistics[$i][$payChannel['order_id']],
            'cash'      => $statistics[$i][$payChannel['cash']],
            'time'      => $statistics[$i][$payChannel['time']],
            'channel'   => $payChannel['type'],
            'channel_name'=> $payChannel['name'],
        ];
         */
        $batchId     = $event->data['batch_id'];

        $logic      =   new CheckBatchLogic();

        $checkLogic =   new CheckOrderLogic();

        $batchInfo  =   $logic->getAdoptBatchById($batchId);

        if( !empty($batchInfo) ){

            $result     =   $checkLogic->checkBathEntrance($batchInfo);

            if( $result['status'] == true){

                $batchLogic =   new CheckBatchLogic();

                $batchLogic->doEdit($batchId,['status'=>CheckBatchDb::STATUS_SUCCESS] );
            }else{

                Log::info(__CLASS__.'error', ["msg"=>"当前对账文件: ".$result['msg']." 入队列失败"] );
            }

        }else{

            Log::info(__CLASS__.'error', ["msg"=>"当前对账文件: ".$batchId." 不存在或者,状态不符合"]);
        }



    }


}