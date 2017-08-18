<?php

namespace App\Http\Models\SystemConfig;
use App\Http\Dbs\SystemConfig\SystemConfigDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Lang\LangModel;
use Log;
use Cache;
use Config;
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/13
 * Time: 下午3:20
 */
class SystemConfigModel extends Model
{

    public static $codeArr = [

        'checkUniqueKey'    => 1,
        'create'            => 2,
        'update'            => 3,
        'delete'            => 4,
        'checkIsExist'      => 5,
        'checkIsExistByKey' => 6,
        'doCreate'          => 7,
        'doUpdate'          => 8,
        'doDelete'          => 9,
        'getConfigById'     => 10,
        'doUpdateByKey'     => 11,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_SYSTEM_CONFIG;

    public static $systemConfigDb;  //数据对象

    public function __construct(){
        self::$systemConfigDb  =  self::getInstance();
        return self::$systemConfigDb;
    }

    /**
     * @return SystemConfigSFDb
     * @desc 单列模式
     */
    public static function getInstance(){

        if(!(self::$systemConfigDb instanceof self)){
            self::$systemConfigDb = new SystemConfigDb();
        }

        return self::$systemConfigDb;
    }

    /**
     * @param $key
     * @return bool
     * @throws \Exception
     * @desc 检测键值是否为唯一
     */
    public static function checkUniqueKey($key){

        $db = self::getInstance();

        $res = $db -> getConfigByKey($key);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_KEY_UNIQUE'), self::getFinalCode('checkUniqueKey'));
        }

        return true;

    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 创建配置
     */
    public function create($data){

        $db = self::$systemConfigDb;

        $res = $db -> add($data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_CREATE'), self::getFinalCode('create'));
        }

        return true;

    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 更新配置
     */
    public function update($id, $data){

        $db = self::$systemConfigDb;

        $res = $db -> edit($id, $data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_EDIT'), self::getFinalCode('update'));
        }

        return true;

    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 删除配置
     */
    public function delete($id){

        $db = self::$systemConfigDb;

        $res = $db -> del($id);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_DELETE'), self::getFinalCode('delete'));
        }

        return true;

    }

    /**
     * @param $page
     * @param $size
     * @param $keyWord
     * @return mixed
     * @desc 获取数据列表
     */
    public function getAllList( $page, $size, $keyWord, $configType=''){

        if( $configType == SystemConfigDb::TYPE_CORE ){

            $api  = Config::get('coreApi.moduleConfig.getConfigList');

            $return = HttpQuery::corePost($api);

            if( $return['code'] == Logic::CODE_SUCCESS ){

                return $return['data'];

            }else{

                return [];

            }

        }
        //服务端的配置
        if($configType == SystemConfigDb::TYPE_SERVICE){

            $api  = Config::get('serviceApi.moduleConfig.getConfigList');

            $return = HttpQuery::serverPost($api);

            if( $return['code'] == Logic::CODE_SUCCESS ){

                return $return['data'];

            }else{

                return [];

            }

        }

        $db = self::$systemConfigDb;

        $res = $db -> getAllList($page, $size, $keyWord);

        return $res;

    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     * @desc 通过id检测数据是否存在
     */
    public function checkIsExist( $id ){

        $db = self::$systemConfigDb;

        $res = $db -> getById($id);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_NOT_FIND'), self::getFinalCode('checkIsExist'));
        }

        return $res;

    }

    /**
     * @param $id
     * @param string $configType
     * @return bool
     * @throws \Exception
     * @desc 通过id获取config信息
     */
    public function getConfigById($id, $configType='')
    {

        if( !$id ){ return false; }

        if( $configType == SystemConfigDb::TYPE_CORE ){

            $api  = Config::get('coreApi.moduleConfig.getConfig');

            $data = ['id' => $id];

            $return = HttpQuery::corePost($api, $data);

            if( !isset($return['data']) || empty($return['data']) ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_NOT_FIND'), self::getFinalCode('getConfigById'));

            }

            return $return['data'];

        }
        //服务端
        if($configType == SystemConfigDb::TYPE_SERVICE){
            $api  = Config::get('serviceApi.moduleConfig.getConfigById');

            $data = ['id'=> $id];

            $return = HttpQuery::serverPost($api, $data);

            if( !isset($return['data']) || empty($return['data']) ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_NOT_FIND'), self::getFinalCode('getConfigById'));

            }

            return $return['data'];
        }

        $db = self::$systemConfigDb;

        $res = $db->getById($id);

        if( empty($res) ){

            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_NOT_FIND'), self::getFinalCode('getConfigById'));

        }

        return $res;

    }

    /**
     * @param $key
     * @param $id
     * @return array
     * @throws \Exception
     * @desc 通过id检测数据是否存在
     */
    public function checkIsExistByKey( $key, $id='' ){

        $db = self::$systemConfigDb;

        $res = $db -> getByKey($key, $id);

        if($res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_KEY_UNIQUE'), self::getFinalCode('checkIsExistByKey'));
        }

        return $res;

    }

    /**
     * @param $key
     * @return mixed
     * @desc 通过Key获取数据
     */
    public function getByKey($key){

        $db = self::$systemConfigDb;

        $res = $db -> getByKey($key);

        return $res;

    }

    /**
     * @param $data
     * @return static
     * @throws \Exception
     * @desc 创建配置
     */
    public function doCreate( $data ){

        if( isset($data['config_type']) && $data['config_type'] == SystemConfigDb::TYPE_CORE ){

            $api  = Config::get('coreApi.moduleConfig.addConfig');

            $return = HttpQuery::corePost($api, $data);

            if( $return['code'] == Logic::CODE_ERROR ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_CREATE'), self::getFinalCode('doCreate'));

            }

            return $return['data'];

        }
        //服务配置
        if( isset($data['config_type']) && $data['config_type'] == SystemConfigDb::TYPE_SERVICE ){

            $api  = Config::get('serviceApi.moduleConfig.addSystemConfig');

            $return = HttpQuery::serverPost($api, $data);

            if( $return['code'] == Logic::CODE_ERROR ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_CREATE'), self::getFinalCode('doCreate'));

            }

            return $return['data'];

        }


        //检测试Key是否已存在
        $this->checkIsExistByKey($data['key']);

        $db =  self::$systemConfigDb;

        $res = $db -> add( $data );

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_CREATE'), self::getFinalCode('doCreate'));
        }

        Cache::put($data['key'], $data['value'], 30);

        return $res;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 配置更新
     */
    public  function doUpdate($id, $data){

        if( isset($data['config_type']) && $data['config_type'] == SystemConfigDb::TYPE_CORE ){

            $api  = Config::get('coreApi.moduleConfig.editConfig');

            $data['id'] = $id;

            $return = HttpQuery::corePost($api, $data);

            if( $return['code'] == Logic::CODE_ERROR ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_EDIT'), self::getFinalCode('doUpdate'));

            }

            return true;

        }
        //服务配置
        if(isset($data['config_type']) && $data['config_type'] == SystemConfigDb::TYPE_SERVICE){
            $api = Config::get('serviceApi.moduleConfig.editSystemConfig');

            $data['id'] = $id;

            $return = HttpQuery::serverPost($api, $data);

            if( $return['code'] == Logic::CODE_ERROR ){

                throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_EDIT'), self::getFinalCode('doUpdate'));

            }

            return true;
        }

        //检测试Key是否已存在
        $this->checkIsExistByKey($data['key'], $id);

        //检测试Id是否存在
        $this->checkIsExist($id);

        $db = self::$systemConfigDb;

        $res = $db -> edit($id, $data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_EDIT'), self::getFinalCode('doUpdate'));
        }

        $cacheRes = Cache::get($data['key']);

        if( !empty($cacheRes) ){

            $cacheResult = Cache::forget($data['key']);

            if( !$cacheResult ){

                WarningLogic::doSmsWarning('【九斗鱼】updateSystemConfigError('.$data['key'].')');

            }

        }

        return $res;

    }

    /**
     * @param $key
     * @param string|array $value
     * @return mixed
     * @throws \Exception
     * @desc 通过key值更新配置
     */
    public function doUpdateByKey($key, $value){

        //检测试Key是否已存在
        $res = $this->getByKey($key);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_NOT_FIND'), self::getFinalCode('doUpdateByKey'));
        }

        $data['value'] = serialize($value);

        $db = self::$systemConfigDb;

        $res = $db -> editValueById($res['id'], $data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_SYSTEM_CONFIG_EDIT'), self::getFinalCode('doUpdateByKey'));
        }

        $cacheRes = Cache::get($key);

        if( !empty($cacheRes) ){

            $cacheResult = Cache::forget($key);

            if( !$cacheResult ){

                WarningLogic::doSmsWarning('【九斗鱼】updateSystemConfigError('.$key.')');

            }

        }

        return $res;

    }


    /**
     * @param $key
     * @param string $configType ''/'core' 模块配置/核心配置
     * @return array|mixed
     * @desc 获取配置的值
     */
    public static function getConfig( $key, $configType='' ){

        if(empty($key)) return null;

        if( $configType == SystemConfigDb::TYPE_CORE ){

             $api  = Config::get('coreApi.moduleConfig.getConfig');

            $data = ['key' => $key];

             $return = HttpQuery::corePost($api, $data);

             if( $return['code'] == Logic::CODE_SUCCESS ){

                 return $return['data'];

             }

             return false;

        }

        //取二级下的Key A.A1
        $keyArr = explode('.',$key);

        $key    = $keyArr[0];

        $keyValue = Cache::get($key);

        if(empty($keyValue)){

            $db = self::getInstance();

            $res = $db -> getConfig($key);

            if( empty($res) || !is_array($res) ) return [];

            Cache::put($key, $res['value'], 30);//3个月

            $keyValue = $res['value'];

        }

        $keyValue = unserialize($keyValue);

        if(isset($keyArr[1])){

            return $keyValue[$keyArr[1]];

        }

        return $keyValue;

    }

    /**
     * 防止对象被复制
     */
    public function __clone(){
        trigger_error('Clone is not allowed !');
    }

}