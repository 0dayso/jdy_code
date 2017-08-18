<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\ContractFile;


use App\Events\CommonEvent ;
use App\Http\Logics\Logic;

/**
 * 静默方式自动生成保全合同
 * Class CreateContractListeners
 * @package App\Listeners\User\ContractFile
 */
class CheckContractListeners
{


    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     *
     * @param  RegisterSuccessEvent  $event
     * @return void
     */
    public function handle(CommonEvent $event){

        $data       =   $event->getDataByKey('contract') ;

        try{

            $params['contract']     =   [
                'apply_no'  =>  $data['apply_no'] ,
                'real_name' =>  $data['real_name'] ,
                'phone'     =>  $data['phone'] ,
                'identity'  =>  $data['identity'] ,
                'project_id'=>  $data['project_id'] ,
                'invest_id' =>  $data['invest_id'] ,
                'title'     =>  $data['title'],
                'event_name'=>  'App\Events\User\CheckContractDownUrlEvent',
                'event_desc'=>  '检测合同的生成状态',
                'email'     =>  isset($data['email']) && !empty($data['email']) ? $data['email'] : '',
                'outPutFile'=>  $data['outPutFile'],
                'limit_time'=>  isset( $data['limit_time'] ) && !empty($data['limit_time']) ? $data['limit_time'] : '15' ,
            ];

            \Log::info(__METHOD__,$params);

            \Event::fire( new \App\Events\User\CheckContractDownEvent($params) );

        } catch (\Exception $e) {

            \Log::error (__CLASS__, [$e->getMessage ()]);

            Logic::callError ($e->getMessage ());
        }
    }
}
