<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\RegisterSuccess;

use App\Events\User\RegisterSuccessEvent;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Media\ChannelLogic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Tools\ToolStr;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 推广渠道注册对应关系
 * Class InviteRelationshipListener
 * @package App\Listeners\User\RegisterSuccess
 */
class ActivityAwardListener
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
    public function handle(RegisterSuccessEvent $event){

        $userId = $event->getUserId();

        //兼容 两种方式获取渠道信息  渠道名称  渠道编号
        $name = $event->getChannelName();
        
        $channelId = $event->getChannelId();

        $logic = new ChannelLogic();

        $config = $logic->getAwardConfig($name,$channelId);

        if($config){

            $this->sendAward($userId,$config);
        }

    }

    /**
     * @param $userId
     * @param $config
     * 发送渠道奖励,应用于新用户注册,且关系到站内信
     */
    private function sendAward($userId,$config){

        $bonusArr = [];

        $currentBonusArr = [];

        $newBonusArr = [];

        //定期红包及加息券
        if(isset($config['REGULAR_PACKETS']) && $config['REGULAR_PACKETS']){
            
            $bonusArr = explode(',',$config['REGULAR_PACKETS']);

        }

        //零钱计划加息券
        if(isset($config['CURRENT_PACKETS']) && $config['CURRENT_PACKETS']){

            $currentBonusArr = explode(',',$config['CURRENT_PACKETS']);
        }

        //新系统红包及加息券奖励(零钱计划 定期统一发放)
        if(isset($config['BONUS_IDS']) && $config['BONUS_IDS']){

            $newBonusArr = explode(',',$config['BONUS_IDS']);

        }
        //合并加息券及红包
        $bonusList = array_merge($bonusArr,$currentBonusArr,$newBonusArr);

        if($bonusList){

            //$result = $this->doSendBonus($userId,$bonusList);
            $this->doIncomingQueueBonus($userId,$bonusList);

        }


        //判断是否应该发送体验金
        if(isset($config['EXPERIENCE_CASH']) && $config['EXPERIENCE_CASH'] > 0){

            $this->doSendExperienceCash($userId,$config['EXPERIENCE_CASH']);
        }
    }


    /**
     * @param $userId
     * @param $bonusArr
     * 发送红包及加息券
     */
    private function doSendBonus($userId,$bonusArr){

        $logic = new UserBonusLogic();

        return $logic->doSendBonusByUserId($userId,$bonusArr);

    }

    /**
     * @param $userId
     * @param $cash
     * 发送体验金奖励
     */
    private function doSendExperienceCash($userId,$cash){
        //逻辑待完善

    }
    /**
     * @param $fileContent
     * @return array
     * @desc 新手红包入队列
     */
    private static function doIncomingQueueBonus($userId , $bonusArr = [])
    {
        if( empty($bonusArr) ){

            return [];
        }

        try{

            foreach ($bonusArr as $key => $bonusId){

                $params = [
                    'event_name'        => 'App\Events\User\NoviceRewardsEvent',
                    'event_desc'        => '新手奖励红包',
                    'user_id'           =>  $userId,            //用户id
                    'bonus_id'          =>  $bonusId,        //红包id
                    'ticket_id'         =>  ToolStr::getRandTicket()
                ];

                \Log::info(__METHOD__,$params);

                \Event::fire(new \App\Events\User\NoviceRewardsEvent($params));
            }

        } catch (\Exception $e){

            \Log::error(__CLASS__, [$e->getMessage()]);

            return Logic::callError($e->getMessage());
        }

        //发放红包成功的站内信
        $data['notice'] = [
            'title'     => NoticeDb::TYPE_REGISTER,
            'user_id'   => $userId,
            'message'   => NoticeLogic::getMsgTplByType(NoticeDb::TYPE_REGISTER),
            'type'      => NoticeDb::TYPE_REGISTER
        ];

        \Event::fire(new \App\Events\User\ActivityAwardSuccessEvent($data));

        return Logic::callSuccess();
    }

}