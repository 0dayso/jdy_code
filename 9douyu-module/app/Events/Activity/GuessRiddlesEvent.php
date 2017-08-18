<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 17/01/23
 * Time: 19:02 PM
 * Desc: 成功事件
 */
namespace App\Events\Activity;

use App\Events\Event;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Activity\GuessRiddlesModel;
use App\Http\Logics\Bonus\UserBonusLogic;

use Log;

class GuessRiddlesEvent extends Event{

    /**
     * @var array 传入event参数
     */
    public $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data['data'];

        Log::info('GuessData：' . json_encode($this->data));
    }

    /**
     * @desc 获取用户id
     * @return mixed
     */
    public function getUserId(){
        return $this->data['user_id'];
    }
    /**
     * @desc 获取活动配置奖励
      */
    public function getAwardConfig(){

        $riddlesModel = new GuessRiddlesModel();

        $config = $riddlesModel->getActivityConfigByKey($this->data['activity_key']);

        $awardConfig = $config['ACTIVITY_AWARD_BONUS'];
        //检测是否多个id
        if(stripos($awardConfig,',')){
            $awardConfig = explode(',', $awardConfig);
        }else{
            $awardConfig = [$awardConfig];

        }
        return $awardConfig;

    }

    /**
     * @desc 执行发送红包或加息券
     * @param $userId
     * @param $bonusArr
      * @return bool
      */
    public function sendBonus($userId, $bonusArr){

        $userLogic = new UserBonusLogic();

        $userLogic->doSendBonusByUserId($userId, $bonusArr);
    }

}
