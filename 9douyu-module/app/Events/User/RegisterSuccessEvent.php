<?php

namespace App\Events\User;

use App\Events\Event;

use App\Http\Dbs\User\InviteDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Logics\Media\ChannelLogic;
use Log;

use Illuminate\Support\Facades\Request;
use App\Http\Models\SystemConfig\SystemConfigModel;

use App\Http\Models\Common\CoreApi\UserModel;

use App\Http\Logics\RequestSourceLogic;
/**
 * 注册成功事件
 * Class RegisterSuccessEvent
 * @package App\Events\User
 */
class RegisterSuccessEvent extends Event
{
    /**
     * @var array 传入event参数
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data['data'];

        Log::info('data：' . json_encode($this->data));
    }

    /**
     * 获取IP
     */
    public function getIp(){
        return isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp();
    }

    /**
     * 获取用户ID
     * @return mixed
     */
    public function getUserId(){
        return $this->data['coreApiData']['id'];
    }

    /**
     * @return int|mixed
     * @desc 获取邀请id
     */
    public function getInviteId()
    {

        if( !empty($this->data['invite_id']) ){

            $userInfo = UserModel::getCoreApiUserInfo($this->data['invite_id']);

            return isset($userInfo['id']) ? $this->data['invite_id'] : 0;

        }

        return 0;
        
    }

    /**
     * 获取来源
     * @return mixed
     */
    public function getSource(){
        $clientSource = RequestSourceLogic::$clientSource;
        $from         = strtolower($this->data['request_source']);
        $key          = array_search($from, $clientSource);
        return (int)$key;
    }

    /**
     * @return mixed
     * @desc 获取邀请用户id
     */
    public function getInviteUserId(){

        //$userId = $this->checkInviteCode();
        $userId   = $this->getInviteUserIdByPhone();

        if($userId){

            return $userId;

        }

        return empty($this->data['user_id']) ? $this->getInviteId() : $this->data['user_id'];
    }

    /**
     * @return mixed
     * @desc 获取邀请类别
     */
    public function getInviteType(){

        //$userId = $this->checkInviteCode();
        $userId   = $this->getInviteUserIdByPhone();

        if($userId){

            return InviteDb::TYPE_PHONE_NUM;
        }

        return empty($this->data['type'])? InviteDb::TYPE_MEDIA :$this->data['type'];
    }

    /**
     * @return mixed
     * @desc 获取邀请来源
     */
    public function getInviteSource(){
        return empty($this->data['source'])?0:$this->data['source'];
    }

    /**
     * @return mixed
     * @desc 获取邀请类型
     */
    public function getInviteUserType(){

        return empty($this->data['user_type'])?InviteDb::USER_TYPE_NORMAL:$this->data['user_type'];

    }


    /**
     * @return array
     * 获取渠道信息
     */
    public function getChannelInfo(){

        $result = [];

        $logic = new ChannelLogic();


        if(isset($this->data['channel']) && $this->data['channel']){
            $name = $this->data['channel'];


            $result = $logic->getByName($name);

        }elseif(isset($this->data['channel_id']) && $this->data['channel_id']){

            $channelId = $this->data['channel_id'];


            $result = $logic->getById($channelId);
        }


        return $result;
    }


    /**
     * @return mixed|string
     * 获取渠道名称
     */
    public function getChannelName(){

        return isset($this->data['channel']) ? $this->data['channel'] : '';
    }


    /**
     * @return mixed|string
     * 获取渠道名称
     */
    public function getChannelId(){

        return isset($this->data['channel_id']) ? $this->data['channel_id'] : '';
    }
    /**
     * @return bool
     * @desc 邀请码邀请
     */
    private function checkInviteCode(){



        if(empty($this->data['invite_code'])){

            return false;

        }
        $inviteCode = $this->data['invite_code'];

        $db = new UserInfoDb();

        $info = $db -> getByInviteCode( $inviteCode );

        if( empty($info) ){

            return false;

        }

        $userId = $info['user_id'];

        return $userId;

    }

    /**
     * @desc 通过填写的邀请的手机号获取邀请用户id
     * Date 16/08/01
     * @return bool
     */
    private function getInviteUserIdByPhone(){
        //邀请手机号为空返回false
        if(empty($this->data['invite_phone'])){

            return false;

        }
        $invitePhone = $this->data['invite_phone'];

        $userInfo  = UserModel::getBaseUserInfo($invitePhone);

        if( empty($userInfo) ){

            return false;

        }

        $userId = $userInfo['id'];

        return $userId;
    }

    /**
     * @return mixed|string
     * 获取注册人的手机号
     */
    public function getUserPhone(){

        return (isset($this->data['phone']) && !empty($this->data['phone'])) ? $this->data['phone'] : '';

    }

}
