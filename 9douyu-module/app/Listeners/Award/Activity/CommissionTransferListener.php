<?php

/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/21
 * Time: 上午11:39
 */

namespace App\Listeners\Award\Activity;

use App\Events\Activity\IncreaseTransferEvent;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Activity\AwardRecordModel;
use App\Http\Models\Common\CoreApi\UserModel;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Queue\InteractsWithQueue;

use App\Http\Logics\Logic;
use Log;

/**
 * 获取额外加息结算
 *
 * Class IncreaseRateTransferListener
 * @package App\Listeners\Award\Activity
 */
class CommissionTransferListener implements ShouldQueue
{
    const MAX_EXE_TIMES = 3;//最大调用次数

    use InteractsWithQueue;

    /**
     * 额外加息奖励的入账脚本
     * @param IncreaseTransferEvent $event
     *
     */
    public function handle( IncreaseTransferEvent $event)
    {

        //请求数据组装
        $userId             = $event->data['user_id'];

        $cash               = $event->data['cash'];

        $tradingPassword    = $event->data['trading_password'];

        $ticketId           = $event->data['ticket_id'];

        $note               = $event->data['note'];

        $data               = $event->data['data'];

        $attribute          = $event->data['attribute'];

        $eventId            = $event->data['event_id'];
        
        $attempts           = $this->attempts();    //请求的次数


        //异步执行超过最大次数,直接删除
        if( $attempts >= self::MAX_EXE_TIMES){

            $this->delete();

        }else{

            $userModel      =   new UserModel();
            
            $activityFundModel= new ActivityFundHistoryModel();
            
            try {
                Logic::beginTransaction();

                //更新本地的数据
                AwardRecordModel::doUpdate($data,$attribute);

                //创建模块活动类资金流水,方便数据统计
                $fundData   = [
                    'user_id'   => $userId,
                    'balance_change' => $cash,
                    'source'    => isset($eventId) ? $eventId : ActivityFundHistoryDb::SOURCE_ACTIVITY,
                    'note'      => $note
                ];

                $activityFundModel->doDecrease($fundData);

                //创建核心资金记录，更改余额
                $userModel->doIncBalance($userId, $cash, $tradingPassword, $note, $ticketId);

                Logic::commit();

                $this->delete();//删除任务 已经成功

            }catch (\Exception $e){

                Logic::rollback();

                Log::debug(__METHOD__ , ['msg' => $e->getMessage()]);

                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts >= 1 && $attempts <= self::MAX_EXE_TIMES) {
                    $delay = $attempts * 5;//5秒钟
                    $this->release($delay);
                }
            }
        }

    }
}