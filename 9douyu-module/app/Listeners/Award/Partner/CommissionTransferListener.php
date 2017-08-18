<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/1
 * Time: 上午10:06
 */

namespace App\Listeners\Award\Partner;

use App\Events\Award\PartnerCommissionTransferEvent;

use App\Http\Logics\Logic;
use App\Http\Models\User\PartnerModel;

use App\Http\Models\Activity\ActivityFundHistoryModel;

use App\Jobs\Job;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

use App\Lang\LangModel;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Models\Common\CoreApi\UserModel;

use Log;
/**
 * 【合伙人佣金转出】
 *
 * Class PartnerInvestOutListener
 * @package App\Listeners\Award
 */
class CommissionTransferListener implements ShouldQueue
{
    const MAX_EXE_TIMES = 3;//最大调用次数

    use InteractsWithQueue;

    /**
     * 合伙人收益转出
     *
     * @param PartnerCommissionTransferEvent $event
     * @return bool
     * @throws \Exception
     */
    public function handle(PartnerCommissionTransferEvent $event)
    {
        //请求数据组装
        $userId             = $event->data['user_id'];
        $cash               = $event->data['cash'];
        $tradingPassword    = $event->data['trading_password'];
        $ticketId           = $event->data['ticket_id'];
        
        $attempts = $this->attempts();

        Log::debug(__METHOD__ , ['param'=>$event->data]);

        $msg      = '当前执行次数：' . $attempts;

        // 通知self::MAX_EXE_TIMES 次 删除该job
        if ($attempts > self::MAX_EXE_TIMES) {
            $this->delete();
        } else {

            $model                     = new PartnerModel();
            $activityFundModel         = new ActivityFundHistoryModel();
            $userModel                 = new UserModel();

            try {
                Logic::beginTransaction();
                //更新佣金金额
                $model->delCash($userId, $cash);

                //创建核心资金记录，更改余额
                $userModel->doIncBalance($userId, $cash, $tradingPassword, LangModel::PARTNER_OUT,$ticketId);

                //创建模块活动类资金流水,方便数据统计
                $fundData = [
                    'user_id' => $userId,
                    'balance_change' => $cash,
                    'source' => ActivityFundHistoryDb::SOURCE_PARTNER,
                    'note' => '收益转出'
                ];

                $activityFundModel->doDecrease($fundData);

                Logic::commit();

                $this->delete();//删除任务 已经成功

                \Cache::forget($userId.'_I_O_C');

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

            /*
            //接到通知删除任务 并标记该用户为一码付用户
            if(!empty($return['code']) && $return['code'] == 200){
                $this->delete();//删除任务 已经成功

            }else {
                throw new \Exception($msg);
            }
            */
        }


    }

}