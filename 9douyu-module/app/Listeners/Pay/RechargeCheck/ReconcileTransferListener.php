<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/9
 * Time: 下午7:34
 */

namespace App\Listeners\Pay\RechargeCheck;

use App\Events\Pay\RechargeCheckEvent;
use App\Http\Dbs\Order\CheckOrderRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Recharge\CheckOrderLogic;
use App\Http\Logics\Recharge\CheckOrderRecordLogic;
use App\Http\Models\Common\CoreApi\OrderModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class ReconcileTransferListener implements ShouldQueue
{


    const MAX_EXE_TIMES = 3;//最大调用次数

    use InteractsWithQueue;

    public function handle( RechargeCheckEvent $event)
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
        $orderId            = $event->data['order_id'];

        $cash               = $event->data['cash'];

        $ticketId           = $event->data['ticket_id'];

        $time               = $event->data['time'];

        $channel            = $event->data['channel'];

        $attempts           = $this->attempts();    //请求的次数

        if( $attempts >= self::MAX_EXE_TIMES ){

            $this->delete();
            
        }else{

            $checkLogic         =   new CheckOrderLogic();

            $orderRecordLogic   =   new CheckOrderRecordLogic();

            try {
                
                Logic::beginTransaction();

                $orderInfo          =   OrderModel::getOrderInfo($orderId);

                //验证订单数据
                $checkInfo          =   $checkLogic->doVerification( $orderId ,$cash,$time,$channel,$orderInfo );

                //记录异常订单
                if( empty($checkInfo) || $checkInfo['status'] != true){

                    $recordData =   [
                        'order_id'      =>  $orderId,
                        'user_id'       =>  isset($orderInfo['user_id']) ? $orderInfo['user_id'] : "0",
                        'pay_channel'   =>  $channel,
                        'cash'          =>  $cash,
                        'is_check'      =>  CheckOrderRecordDb::CHECK_STATUS_PENDING,
                        'note'          =>  $checkInfo['msg']? $checkInfo['msg'] : "订单信息不正确",
                    ];

                    $orderRecordLogic->doAdd($recordData);

                }
               
                Logic::commit();

                $this->delete();//删除任务 已经成功

            }catch (\Exception $e){

                Logic::rollback();

                Log::debug(__METHOD__ , ['msg' => $e->getMessage()]);

                //throw new \Exception($e->getMessage());

                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts >= 1 && $attempts <= self::MAX_EXE_TIMES) {
                    $delay = $attempts * 5;//5秒钟
                    $this->release($delay);
                }
            }
        }
    }
}