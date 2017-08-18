<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/18
 * Time: 17:11
 */

namespace App\Events\User;

use App\Events\Event;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Support\Facades\Request;
use Log;

/**
 * @desc 登录成功事件
 * Class LoginSuccessEvent
 * @package App\Events\User
 */
class LoginSuccessEvent extends Event{

    /**
     * @var array @desc 传入的event参数
     */
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = $data['data'];

        Log::info('loginSuccessData：'.json_encode($this->data));
    }

    /**
     * 获取用户ID
     * @return mixed
     */
    public function getUserId(){
        //登录成功获取用户userId
        if(!empty($this->data['userInfo'])){
            return $this->data['userInfo']['id'];
        }
        return '';
    }

    /**
     * 获取用户登录ip
     * @return mixed
     */
    public function getLoginIp(){
        return isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp();
    }

    /**
     * @desc 获取登录来源
     * @return array
     */
    public function getLoginSource(){

        $clientSource = RequestSourceLogic::$clientSource;
        $from         = strtolower($this->data['request_source']);
        $key          = array_search($from, $clientSource);
        return (int)$key;
    }

    /**
     * @return string
     * @desc客户端的版本号
     */
    public function getAppVersion()
    {
        if( !empty($this->data['client'])){

            return $this->data['client']['version'];
        }

        return '';
    }

    /**
     * @return string
     * @desc 获取客户端传递的设备信息
     */
    public function getClientVersion()
    {
        if( !empty($this->data['client'])){

            return $this->data['client']['client_version'];
        }

        return '';
    }

    /**
     * @return string
     * @desc 获取客户端的 uuid
     */
    public function getUuid()
    {
        if( !empty($this->data['client'])){

            return $this->data['client']['uuid'];
        }

        return '';
    }

    /**
     * @return string
     * @desc 设备型号
     */
    public function getClientType()
    {
        if( !empty($this->data['client'])){

            return $this->data['client']['client_type'];
        }

        return '';
    }

    /**
     * @return array
     * @desc 根据访问来源输出设备信息
     */
    public function getBrowserMessage()
    {
        $source = $this->data['request_source'];

        if( $source == RequestSourceLogic::SOURCE_ANDROID || $source == RequestSourceLogic::SOURCE_IOS){

            return [
                'client'    =>  self::getClientType(),
                'version'   =>  self::getClientVersion(),
                'message'   =>  $source."客户端版本号:".self::getAppVersion(),
                'uuid'      =>  self::getUuid()
            ];
        }

        if( $source == RequestSourceLogic::SOURCE_WAP || $source == RequestSourceLogic::SOURCE_PC ){

            return RequestSourceLogic::getSourceBrowserString();
        }
        //其他未知
        return [
            'client'    =>  'unknown Client',
            'version'   =>  'unknown version',
            'message'   =>  'unknown message',
        ];

    }

}