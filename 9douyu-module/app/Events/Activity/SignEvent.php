<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/25
 * Time: 18:19 PM
 * Desc: 签到成功事件
 */
namespace App\Events\Activity;


use App\Events\Event;
use App\Http\Dbs\Activity\ActivitySignDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Models\Activity\ActivitySignModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Log;

class SignEvent extends Event{

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

        Log::info('Signdata：' . json_encode($this->data));
    }

    /**
     * @desc 连续签到3天
     * @param $signNum
     * @return bool
     */
    public function checkSignContinueThree($signNum){

        if($signNum ==ActivitySignDb::ACTIVITY_CONTINUE_THREE){
            return true;
        }
        return false;
    }

    /**
     * @desc 连续签到5天
     * @param $signNum
     * @return bool
     */
    public function checkSignContinueFive($signNum){

        if($signNum == ActivitySignDb::ACTIVITY_CONTINUE_FIVE){
            return true;
        }
        return false;
    }

    /**
     * @desc 连续签到7天
     * @param $signNum
     * @return bool
     */
    public function checkSignContinueSeven($signNum){

        if($signNum == ActivitySignDb::ACTIVITY_CONTINUE_SEVEN){
            return true;
        }
        return false;
    }

    /**
     * @desc 执行发送红包或加息券
     * @param $userId
     * @param $bonusArr
     */
    public function sendBonus($userId, $bonusArr){

        $userLogic = new UserBonusLogic();

        $userLogic->doSendBonusByUserId($userId, $bonusArr);
    }
}