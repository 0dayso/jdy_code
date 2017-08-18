<?php
/**
 * create by Phpstorm
 * User: linguanghui
 * Date: 17/01/22
 * Time: 19:03 PM
 * Desc: 猜灯谜活动Model层
 */

namespace App\Http\Models\Activity;

use App\Http\Models\Model;
use App\Http\Dbs\Activity\GuessRiddlesDb;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;


class GuessRiddlesModel extends Model{

    protected $db;

    const ACTIVITY_NO_START =1,//活动未开始
          ACTIVITY_IS_END   =3,//活动已结束
          ACTIVITY_DOING    = 2,//活动进行中
          RIDDLES_KEY       = 'LANTERN_RIDDLES',//灯谜配置key

          CODE_GUESS_IS     = 501, //灯谜已经猜过
          CODE_GUESS_ERROR  = 502, //灯谜猜错了
          CODE_RIDDLES_ADD_ERROR  = 503, //灯谜纪录失败
          //活动类型
          ACTIVITY_TYPE_LANTERN = 100;//元宵节活动


    public function __construct(){

        $this->db = new GuessRiddlesDb();
    }

    /**
     * @desc 添加猜中灯谜的纪录
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function addLantern($data){

        $res = $this->db->addLantern($data);

        if(!$res){
            throw new \Exception('添加灯谜纪录失败', self::getFinalCode('addLantern'));
        }
        return $res;
    }

    /**
     * @desc 检测是否猜过灯谜
     * @param $userId
     * @param $type
     * @param $riddlesId
     * @return bool
     * @throws \Exception
     */
    public function checkIsGuessRiddles($userId, $type, $riddlesId){

        $res = $this->db->getUserLanttern($userId, $riddlesId, $type);
        if(!empty($res)){
            throw new \Exception('您已经猜过这个灯谜了', self::CODE_GUESS_IS);
        }
        return true;
    }

    /**
     * @desc 获取活动相关配置
     * @param $activityKey
     * @return array|mixed
     */
    public function getActivityConfigByKey($activityKey){
        $activityConfig = ActivityConfigModel::getConfig($activityKey);
        return $activityConfig;
    }

    /**
     * @desc 获取灯谜答案
     * @param $riddlesId
     * @return string
     */
    public function getRiddlesAnswer($riddlesId){
        $answer = '';
        $riddlesList = $this->getRiddlesList();

        if(empty($riddlesList)){
            return $answer;
        }
        if(isset($riddlesList[$riddlesId])){
            $answer = $riddlesList[$riddlesId]['answer'];
        }
        return $answer;
    }

    /**
     * @desc 检测猜灯谜的答案是否正确
     * @param $riddlesId
     * @param $answer
     * @return bool
     * @throws \Exception
     */
    public function checkAnswerIsTrue($riddlesId, $answer){
        if(empty($riddlesId) || empty($answer)){
            throw new \Exception('灯谜答案不能为空', self::CODE_GUESS_ERROR);
        }
        $riddlesAnswer = $this->getRiddlesAnswer($riddlesId);
        if($answer != $riddlesAnswer){
            throw new \Exception('灯谜答案不正确', self::CODE_GUESS_ERROR);
         }
        return true;
    }


    /**
     * @desc 获取灯谜配置信息处理列表
     * @param $userId
     * @param string $type
     * @return array
     */
    public function getRiddlesList($userId = 0, $type =''){
        $riddlesList = [];


        $riddlesConfig = $this->getActivityConfigByKey(GuessRiddlesModel::RIDDLES_KEY);
        if(empty($riddlesConfig)){
            return $riddlesList;
        }
        $userGuessList = [];
        $userGuessData = $this->db->getUserGuessRiddles($userId, $type);
        if(!empty($userGuessData)){
            $userGuessList = ToolArray::arrayToKey($userGuessData, 'riddles_id');
        }

        foreach($riddlesConfig as $key=>$value){
            $riddlesArr = explode('|',$value);

            $riddlesList[$key]['is_guess'] = 0;
            
            if($userId == 0 || $type=='' || empty($userGuessList)){
                $riddlesList[$key]['is_guess'] = 0;
            }
            if(isset($userGuessList[$key])){
                $riddlesList[$key]['is_guess'] = 1;
            }
            $riddlesList[$key]['question'] = $riddlesArr[0];
            $riddlesList[$key]['answer'] = $riddlesArr[1];
        }

        return $riddlesList;
    }

}
