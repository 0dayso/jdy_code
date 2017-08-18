<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/1
 * Time: 上午10:06
 */

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Events\Invest\ProjectSuccessEvent;

use App\Http\Dbs\User\UserInfoDb;

use App\Http\Logics\ThirdApi\JyfLogic;

use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolTime;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

use App\Http\Models\Common\ServiceApi\EmailModel;

use Log;

use Config;
/**
 * 【九一地推活动 外部系统增加积分】
 *
 * Class JyActivityListener
 * @package App\Listeners\Invest\ProjectSuccess
 */
class JyActivityListener implements ShouldQueue
{
    const MAX_EXE_TIMES = 5;//最大调用次数

    use InteractsWithQueue;

    /**
     * 投资成功 检测通知站外系统条件
     *
     * @param ProjectSuccessEvent $event
     * @return bool
     * @throws \Exception
     */
    public function handle(CommonEvent $event)
    {
        //开关
        if (!Config::get('ymf.open')) {
            return true;
        }

        $data = $event->getDataByKey('invest');

        //请求数据组装
        $param['user_id']       = $data['user_id'];
        $param['original_cash'] = $data['original_cash'];
        //获取可用的openId
        Log::debug(__METHOD__ , ['param'=>$param]);
        $openId = JyfLogic::getCanAddAmountOpenId($param);
        Log::debug(__METHOD__ , ['openId'=>$openId]);

        if ($openId === false) {
            return true;
        }

        $userInfo = UserModel::getCoreApiUserInfo($param['user_id']);

        \Log::info(__METHOD__, $userInfo);

        if(!empty($userInfo['created_at'])){
            $activityStart = strtotime(Config::get('ymf.start'));
            $created_at    = strtotime($userInfo['created_at']);
            \Log::info(__METHOD__, [Config::get('ymf.start'), $userInfo['created_at']]);
            if($created_at < $activityStart){
                return true;
            }
        }

        $attempts = $this->attempts();

        $msg      = '当前执行次数：' . $attempts;

        // 通知self::MAX_EXE_TIMES 次 删除该job
        if ($attempts > self::MAX_EXE_TIMES) {
            $this->delete();
            Log::alert('需要跟进处理 给一码付增加额度达到了最大执行次数：',[$data]);
            //预警
            $emailModel = new EmailModel();
            $receiveEmails = Config::get('email.monitor.ymf');
            $title = '【Warning】给一码付加额度预警 监控';
            $emailModel->sendHtmlEmail($receiveEmails,$title, json_encode($data));

        } else {
            $return = JyfLogic::RequestAddAmount($openId);
            Log::debug(__METHOD__ . $msg, ['open_id'=> $openId, 'return' => $return]);

            //接到通知删除任务 并标记该用户为一码付用户
            if(!empty($return['code']) && $return['code'] == 200){
                $this->delete();//删除任务 已经成功

                $userInfoDb = new UserInfoDb;
                $userInfoDb->updateInfo($param['user_id'], ['third_icon_code'=> UserInfoDb::YMF_CODE]);
            }elseif(!empty($return['code']) && $return['code'] == 300){
                $this->delete();//删除任务 已经成功
            }else{
                $delay = null;
                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts > 1 && $attempts <= self::MAX_EXE_TIMES) {
                    $delay = $attempts * 60;//1分钟
                }
                $this->release($delay);
            }
        }
    }

}