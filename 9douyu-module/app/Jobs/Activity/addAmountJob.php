<?php

namespace App\Jobs\Activity;

use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Config;
/**
 * 一码付给用户增加余额
 *
 * Class addAmountJob
 * @package App\Jobs\Activity
 */
class addAmountJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    const MAX_EXE_TIMES          = 5; //最大调用次数

    protected $userId            = null;
    protected $cash              = null;
    protected $tradingPassword   = null;
    protected $note              = null;
    protected $ticket            = null;

    /**
     * @param $userId
     * @param $cash
     * @param $tradingPassword
     * @param $note
     * @param $ticket
     */
    public function __construct($userId, $cash, $tradingPassword, $note, $ticket)
    {
        $this->userId          = $userId;
        $this->cash            = $cash;
        $this->tradingPassword = $tradingPassword;
        $this->note            = $note;
        $this->ticket          = $ticket;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $attempts = $this->attempts();
        Log::info('run start');

        $msg      = '当前执行次数：' . $attempts;
        // 通知self::MAX_EXE_TIMES 次 删除该job
        if ($attempts > self::MAX_EXE_TIMES) {
            $this->delete();
            $logData = [$this->userId, $this->cash, $this->tradingPassword, $this->note, $this->ticket];
            Log::alert('需要跟进处理：', $logData);
            //预警
            $emailModel = new EmailModel();
            $receiveEmails = Config::get('email.monitor.ymf');
            $title = '【Warning】一码付转余额预警 监控';
            $emailModel->sendHtmlEmail($receiveEmails,$title, json_encode($logData));
        } else {
            try {
                $apiReturn = UserModel::doIncBalance($this->userId, $this->cash, $this->tradingPassword, $this->note, $this->ticket);

                Log::info(__METHOD__ . '调用核心返回：', [$apiReturn]);
                //接到通知删除任务 并标记该用户为一码付用户
                if(!empty($apiReturn['fund_id'])){
                    Log::info(__METHOD__ . '删除任务 执行成功', [$apiReturn]);
                    $this->delete();//删除任务 已经成功
                }else {
                    $delay = null;
                    // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                    if ($attempts > 1 && $attempts <= self::MAX_EXE_TIMES) {
                        $delay = $attempts * 60;//1分钟
                    }
                    $this->release($delay);
                }

            }catch (\Exception $e){
                Log::alert(__METHOD__ . '调用核心接口异常',[$this->userId, $this->cash, $this->tradingPassword, $this->note, $this->ticket, [$e->getMessage(),$e->getCode(), $e->getLine()]]);

                $delay = null;
                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts > 1 && $attempts <= self::MAX_EXE_TIMES) {
                    $delay = $attempts * 60;//1分钟
                }
                $this->release($delay);
            }
        }
        Log::info('run end');
    }
}
