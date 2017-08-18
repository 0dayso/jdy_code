<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/1/19
 * Time: 下午3:14
 */

namespace App\Http\Models\Activity;


use App\Http\Dbs\Activity\ActivityConfigDb;
use App\Http\Models\Model;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Common\HttpQuery;
use App\Lang\LangModel;
use Log;
use Cache;
use Config;

class ActivityConfigModel extends Model
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

    public static $activityConfigDb;  //数据对象


    public function __construct(){
        
        self::$activityConfigDb  =  self::getInstance();

        return self::$activityConfigDb;
    }

    /**
     * @return $ActivityConfigDb
     * @desc 单列模式
     */
    public static function getInstance()
    {
        if(!(self::$activityConfigDb instanceof self)){

            self::$activityConfigDb = new ActivityConfigDb();
        }

        return self::$activityConfigDb;
    }

    /**
     * @param $key
     * @return bool
     * @throws \Exception
     * @desc 检测键值是否为唯一
     */
    public static function checkUniqueKey($key)
    {
        $configDb = self::getInstance();

        $return   = $configDb->getConfigByKey($key);

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_KEY_UNIQUE'), self::getFinalCode('checkUniqueKey'));
        }

        return true;

    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 删除配置
     */
    public function delete($id)
    {
        $configDb = self::getInstance();

        $return   = $configDb->del($id);

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_DELETE'), self::getFinalCode('delete'));
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
    public function getAllList( $page, $size, $keyWord)
    {
        $configDb = self::getInstance();

        $return   = $configDb->getAllList($page, $size, $keyWord);

        return $return;

    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     * @desc 通过id检测数据是否存在
     */
    public function checkIsExist( $id )
    {
        $configDb = self::getInstance();

        $return   = $configDb->getById($id);

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_NOT_FIND'), self::getFinalCode('checkIsExist'));
        }

        return $return;

    }

    /**
     * @param $id
     * @param string $configType
     * @return bool
     * @throws \Exception
     * @desc 通过id获取config信息
     */
    public function getConfigById( $id )
    {
        if( !$id ){ return false; }

        $configDb = self::getInstance();

        $return   = $configDb->getById($id);

        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_NOT_FIND'), self::getFinalCode('getConfigById'));

        }

        return $return;

    }

    /**
     * @param $key
     * @param $id
     * @return array
     * @throws \Exception
     * @desc 通过id检测数据是否存在
     */
    public function checkIsExistByKey( $key, $id='' )
    {

        $configDb = self::getInstance();

        $return   = $configDb->getByKey($key, $id);

        if( $return ){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_KEY_UNIQUE'), self::getFinalCode('checkIsExistByKey'));
        }

        return true;

    }

    /**
     * @param $key
     * @return mixed
     * @desc 通过Key获取数据
     */
    public function getConfigByKey($key)
    {
        $configDb = self::getInstance();

        return $configDb->getByKey($key);
    }

    /**
     * @param $data
     * @return static
     * @throws \Exception
     * @desc 创建配置
     */
    public function doCreate( $data ){

        //检测试Key是否已存在
        $this->checkIsExistByKey($data['key']);

        $configDb =  self::getInstance();

        $return   = $configDb->add( $data );

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_CREATE'), self::getFinalCode('doCreate'));
        }

        return $return;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 配置更新
     */
    public  function doUpdate($id, $data)
    {
        //检测试Key是否已存在
        $this->checkIsExistByKey($data['key'], $id);

        //检测试Id是否存在
        $this->checkIsExist($id);

        $configDb = self::getInstance();

        $return   = $configDb->edit($id, $data);

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_CONFIG_EDIT'), self::getFinalCode('doUpdate'));
        }

        $cacheKey   =   self::setActivityConfigCacheKey($data['key']);

        Cache::forget($cacheKey);

        return true;

    }
    /**
     * @param $key
     * @param string $configType ''/'core' 模块配置/核心配置
     * @return array|mixed
     * @desc 获取配置的值
     */
    public static function getConfig( $key ){

        if(empty($key)) return null;

        //取二级下的Key A.A1
        $keyArr     = explode('.',$key);

        $cacheKey   = self::setActivityConfigCacheKey($keyArr[0]);

        $keyValue   = Cache::get($cacheKey);

        if( empty($keyValue) ){

            $configDb = self::getInstance();

            $return   = $configDb->getConfig($key);

            if( empty($return) || ! is_array($return) ) return [];

            Cache::put($cacheKey, $return['value'], 30);//3个月

            $keyValue = $return['value'];

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

    /**
     * @param $key
     * @return string
     * @desc 缓存中活动配置的key
     */
    public static function setActivityConfigCacheKey( $key )
    {
        return ActivityConfigDb::CONFIG_CACHE_PREFIX.$key;
    }
}