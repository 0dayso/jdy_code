<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/27
 * Time: 上午11:01
 */

namespace App\Listeners\User\RegisterSuccess;


use App\Events\User\NoviceRewardsEvent;
use App\Events\User\RegisterSuccessEvent;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Notice\NoticeLogic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class NoviceAwardListener implements ShouldQueue
{

    const MAX_EXE_TIMES = 3;//最大调用次数

    use InteractsWithQueue;

    /**
     * @param RegisterSuccessEvent $event
     * @desc 注册奖励信息如队列
     */
    public function handle(NoviceRewardsEvent $event)
    {
        //请求数据组装
        $userId             = $event->data['user_id'];

        $bonusId            = $event->data['bonus_id'];

        $attempts           = $this->attempts();    //请求的次数

        if( $attempts >= self::MAX_EXE_TIMES ){

            $this->delete();

        }else{

            //记录异常订单
            $bonusLogic     =   new UserBonusLogic();

            $recordData =   [
                'bonus_id'      =>  $bonusId,
                'user_id'       =>  $userId,
            ];

            $return = $bonusLogic->doSendBonusByUserIdWithBonusId($userId,$bonusId);

            if($return['status'] == false){

                Log::error(__METHOD__.'Error',['data'=>'新手红包发送失败','msg' => $recordData]);

                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts >= 1 && $attempts <= self::MAX_EXE_TIMES) {

                    $delay = $attempts * 5;//5秒钟

                    $this->release($delay);
                }
                $this->delete();//删除任务 已经成功
            }
        }
    }
}