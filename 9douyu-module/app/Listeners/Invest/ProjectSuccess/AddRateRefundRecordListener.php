<?php

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Events\Invest\ProjectSuccessEvent;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Config;

class AddRateRefundRecordListener
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
     *
     * Handle the event.
     * @param ProjectSuccessEvent $event
     * @throws \Exception
     */
    public function handle(CommonEvent $event)
    {
        //add ***
        $event = $event->getDataByKey('add_rate');

        try {
            $projectModel = new ProjectModel();

            $investId   = $event['invest_id'];

            $rate       = (float)$event['rate'];

            if( $rate > 0 ){

                $projectModel->doCreateBonusRefundRecord($investId, $rate);

            }

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', [
                'code'      => $e->getCode(),
                'msg'       => $e->getMessage(),
                'invest_id' => $investId,
                'rate'      => $rate
            ]);

            $receiveEmails = Config::get('email.monitor.accessToken');

            $title = '【Warning】加息券回款生成失败';

            $msg = '投资ID:'.$investId.'; 利率:'.$rate;

            $model = new EmailModel();

            $model->sendHtmlEmail($receiveEmails, $title, $msg);

            throw new \Exception($e->getMessage());
            
        }
    }
}
