<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/27
 * Time: 下午5:41
 */

namespace App\Listeners\Award\Activity;


use App\Events\Activity\LotteryEvent;
use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Logics\Activity\LotteryConfigLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\ServiceApi\SmsModel;
use App\Lang\LangModel;
use Config;
use Log;

class LotteryTransferListener
{

    public function handle( LotteryEvent $event)
    {
        $lotteryId  =   $event->data['prizes_id'];

        $phone      =   $event->data['phone'];

        $logic      =   new LotteryConfigLogic();

        $lottery    =   $logic->getById($lotteryId);

        switch ($lottery['type']){

            case LotteryConfigDb::LOTTERY_TYPE_CURRENT:

                $msg =  sprintf(LangModel::getLang('LOTTERY_ENVELOPE_MESSAGE'),$lottery['name']);

                break;
            case LotteryConfigDb::LOTTERY_TYPE_ENTITY:

                $msg =  sprintf(LangModel::getLang('LOTTERY_ENTITY_MESSAGE'),$lottery['name']);

                break;
            case LotteryConfigDb::LOTTERY_TYPE_ENVELOPE:

                $msg =  sprintf(LangModel::getLang('LOTTERY_ENVELOPE_MESSAGE'),$lottery['name']);

                break;
            case LotteryConfigDb::LOTTERY_TYPE_TICKET:

                $msg =  sprintf(LangModel::getLang('LOTTERY_ENVELOPE_MESSAGE'),$lottery['name']);


                break;
            default:
                $msg = '';
                break;
        }

        if( $msg && $lottery['type'] !=LotteryConfigDb::LOTTERY_TYPE_EMPTY){

            $postData   = [
                'phone' => $phone,
                'msg'   => $msg
            ];
            $return     =    SmsModel::sendNotice($phone,$msg);

            if( $return['code'] == Logic::CODE_ERROR ){

                Log::info('sendLotterySuccessMsgError',$postData);

            }

        }
    }
}