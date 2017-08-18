<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 14:56
 */

namespace App\Http\Logics\Media;

use App\Http\Dbs\Media\GroupDb;
use App\Http\Logics\Logic;
use App\Http\Dbs\Media\ChannelDb;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Log;
use Cache;

class ChannelLogic extends Logic{

    //默认注册奖励KEY
    const
        DEFAULT_KEY = 'DEFAULT_REGISTER_AWARD',
        CHANNEL_CACHE_KEY   =   'CHANNEL_CACHE_KEY' ,

        END =   true
    ;

    /**
     * 获取自媒体列表页
     */
    public function getList($params=array()){

        $db = new ChannelDb();
        
        $list = $db->getList($params);

        return $list;
    }


    /**
     * @param $id
     * 根据id获取渠道数据
     */
    public function getById($id){

        $db = new ChannelDb();

        return $db->getById($id);
    }

    /**
     * @param $id
     * @param $name
     * @param $desc
     * 保存编辑
     */
    public function doEdit($data){

       $id = $data['id'];

        unset($data['id'],$data['_token']);

        $db = new ChannelDb();

        $result = $db->doEdit($id,$data);

        if($result){

            return self::callSuccess();
        }else{

            return self::callError('编辑渠道失败');
        }
    }

    /**
     * @param $name
     * @param $desc
     * @return array
     * 添加渠道
     */
    public function create($data){

        unset($data['_token']);
        $db = new ChannelDb();

        $result = $db->addRecord($data);

        if($result){

            return self::callSuccess();
        }else{

            return self::callError('添加渠道失败');
        }
    }


    /**
     * @param $id
     * @return array
     * 删除指定渠道
     */
    public function delete($id){

        $db = new ChannelDb();

        $result = $db->deleteRecord($id);
        if($result){

            return self::callSuccess();
        }else{

            return self::callError('删除失败');
        }

    }

    /**
     * @return mixed
     * 获取渠道列表
     */
    public function getGroupList(){
        
        $db = new GroupDb();
        
        return $db->getAll();
    }

    /**
     * @param $name
     * @return mixed
     * 根据渠道名称获取相应的渠道信息
     */
    public function getByName($name){

        $db = new ChannelDb();

        return $db->getByName($name);

    }

    /**
     * @param $channel
     * @return mixed
     * @desc  获取推广信息 加缓存
     */
    public function getByChannel( $channel )
    {
        $channel    =   trim ($channel) ;

        if( empty($channel)) {
            return ['status' => false];
        }

        $cacheKey   =   self::CHANNEL_CACHE_KEY ;

        $channelInfo=   Cache::get($cacheKey);

        if( !empty($channelInfo) ) {

            return  json_decode($channelInfo, true) ;
        }

        $db = new ChannelDb();

        $channelInfo    =   $db->getByName($channel);

        if( empty($channelInfo) ){

            $channelInfo['status'] = false ;
        } else {
            $channelInfo['status'] = true ;
        }

        Cache::put($cacheKey,json_encode($channelInfo), 60) ;

        return $channelInfo;
    }


    /**
     * 获取推广包名
     */
    public function getPackage($name){

        $today = date('Y-m-d');

        $channel = $this->getByChannel($name);
        $package = '';

        //存在推广渠道
        if($channel['status'] == true ){

            //推广期内
            if($today >= $channel['start_date'] && $today <= $channel['end_date']){

                return $this->setAppDownloadPackage($channel['package']);
            }
        }

        $key = self::DEFAULT_KEY;

        //默认奖品配置
        //$config = SystemConfigModel::getConfig($key);
        $config = ActivityConfigModel::getConfig($key);

        if($config){

            //活动期内,发送默认奖品
            if($today >= $config['START_TIME'] && $today <= $config['END_TIME']) {

                return $this->setAppDownloadPackage($config['PACKAGE']);
            }
        }

        return $this->setAppDownloadPackage($package);
    }

    /**
     * @param $package
     * @return string
     * @desc  生成客户端下载的最终链接
     */
    protected function setAppDownloadPackage( $package )
    {
        $clineAgent     = strtolower($_SERVER['HTTP_USER_AGENT']);

        //统计APP下载配置
        $config         = SystemConfigModel::getConfig("APP_DOWNLOAD");

        //$clineAgent = 'android';

        //先判断微信内置浏览器
        if( strpos($clineAgent, 'micromessenger') ){

            $packageResult    =     $config['APPSTORE_URL'];

        }elseif( strpos($clineAgent , 'iphone') !== false ){

            $packageResult    =     $config['IOS_IPA'];

        }elseif( strpos($clineAgent, 'android') !== false ){

            $packageResult    =     env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com')."/app/dl/".$package;

        }else{

            $packageResult    =     '/zt/appguide.html';
        }

        return $packageResult;
    }



    /**
     * @return array|mixed
     * 获取奖励配置
     */
    public function getAwardConfig($name,$channelId){

        $key = self::DEFAULT_KEY;
        $today = date('Y-m-d');

        $channel = [];
        if($name){
            $channel = $this->getByName($name);
        }elseif($channelId){

            $channel = $this->getById($channelId);
        }

        //存在推广渠道
        if($channel){

            //推广期内
            if($today >= $channel['start_date'] && $today <= $channel['end_date']){

                $key = $channel['award_key'];

                $config = ActivityConfigModel::getConfig($key);
                //$config = SystemConfigModel::getConfig($key);

                //推广奖品配置存在,开始执行发送逻辑
                if($config){

                    return $config;
                }

            }

        }

        //默认奖品配置
        //$config = SystemConfigModel::getConfig($key);
        $config = ActivityConfigModel::getConfig($key);

        if($config){

            //活动期内,发送默认奖品
            if($today >= $config['START_TIME'] && $today <= $config['END_TIME']) {

                return $config;

            }else{

                $log = [
                    'msg' => '不在活动期间内',
                ];

                Log::error(__METHOD__.'Error',$log);
            }

        }else{

            $log = [
                'msg' => '奖励配置信息不存在',
            ];

            Log::error(__METHOD__.'Error',$log);

        }

        return [];
    }


}