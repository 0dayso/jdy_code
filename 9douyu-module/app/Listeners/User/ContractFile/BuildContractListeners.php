<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\ContractFile;

use App\Http\Logics\Logic;
use App\Events\User\BuildContractFileEvent;
use Log;
use App\Tools\ToolStr;

/**
 * 推广渠道注册对应关系
 * Class InviteRelationshipListener
 * @package App\Listeners\User\RegisterSuccess
 */
class BuildContractListeners
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
     * @param  BuildCrontractFileEvent  $event
     * @return void
     */
    public function handle(BuildContractFileEvent $event){

        $data       =   $event->getDataByKey ('contract') ;

        try{

            $investId   =   $data['invest_id'] ;

            $projectId  =   $data['project_id'] ;

            $userId     =   $data['user_id'] ;

            $cash       =   $data['cash'] ;

            $created    =   $data['created_at'] ;

            $params = [
                'event_name'        => 'App\Events\User\CreateContractFileEvent' ,
                'event_desc'        => '生成保全合同文件',
                'invest_id'         =>  $investId,            //投资id
                'user_id'           =>  $userId,        //用户id
                'ticket_id'         =>  ToolStr::getRandTicket() ,
                'project_id'        =>  $projectId ,    //项目id
                'cash'              =>  $cash ,     //投资金额
                'created_at'        =>  $created ,  //投资时间
            ];

            \Log::info(__METHOD__,$params);

            \Event::fire( new \App\Events\User\CreateContractFileEvent($params) );

        } catch (\Exception $e){

            \Log::error(__CLASS__, [$e->getMessage()]);

            Logic::callError($e->getMessage());
        }
    }
}
