<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/6
 * Time: 15:45
 */

namespace App\Listeners\Api\Order;

use App\Events\ExampleEvent;
use App\Http\Models\Common\EmailModel;
use App\Lang\LangModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawHandleFailedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $data
     * @return void
     */
    public function handle($data)
    {
        $failedList = json_decode($data['failed_order'],true);
        $email = new EmailModel();

        $to = [
            'zhang.shuang@9douyu.com' => '张爽'
        ];
        
        $body = sprintf(LangModel::getLang('WITH_DRAW_ORDER_BATCH_HANDLE_FAILED_LIST'),implode(',',$failedList));
        $email->sendEmail($to,'批量对账处理失败',$body);

        //
        //var_dump("User", $data, "pid:".getmypid());
        //return 'abc';
        #throw new \Exception('');
    }
}
