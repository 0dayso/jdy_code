<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/1/20
 * Time: 上午11:20
 */

namespace App\Http\Logics\Activity;


use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;

class ActivityConfigLogic extends Logic
{
    /**
     * @param $page
     * @param $size
     * @param $keyWord
     * @return mixed
     * @desc 获取列表
     */
    public static function getAllList( $page, $size, $keyWord)
    {
        $model  = new ActivityConfigModel();

        $list   = $model->getAllList( $page, $size, $keyWord);

        return $list;
    }

    /**
     * @param $id
     * @return array
     * @desc 通过Id获取数据
     */
    public static function getConfigById($id)
    {
        $model = new ActivityConfigModel();

        try{

            $result = $model->getConfigById($id);

            $result =self::_formatKeyValue($result);

        }catch( \Exception $e){

            return self::callError($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $data
     * @return array
     * @desc 执行创建
     */
    public static function doCreate($data)
    {
        $data = self::_filterParams($data);

        try{

            self::beginTransaction();

            $model = new ActivityConfigModel();

            $result = $model->doCreate($data);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 更新配置信息
     */
    public static function doUpdate($id, $data)
    {
        $data = self::_filterParams($data);

        try{

            self::beginTransaction();

            $model  = new ActivityConfigModel();

            $result = $model->doUpdate($id, $data);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }

    /**
     * @param $data
     * @return array
     * @desc 格式化数据
     */
    public static function _filterParams($data)
    {
        $data = array(
            'name'          => trim($data['name']),
            'key'           => trim($data['key']),
            'value'         => serialize(self::_getKeyValue($data)),
            'second_desc'   => serialize(self::_getKeyValue($data,'second_key','second_desc'))?:'',
            'admin_id'      => $data['manage_id'],
            'status'        => trim($data['status']),
        );

        return $data;
    }

    /**
     * @param $data
     * @param string $key
     * @param string $valueKey
     * @return array
     * @desc 组合需要序列化的值
     */
    private static function _getKeyValue($data, $key='second_key',$valueKey = 'second_value')
    {
        if(isset($data[$key]) && isset($data[$valueKey])){

            $keyArr     = $data[$key];

            $valueArr   = $data[$valueKey];

            $value      = array_combine($keyArr, $valueArr);

        }else{

            $value      = $data['value'];
        }
        return $value;
    }

    /**
     * @param $data
     * @return mixed
     * @desc 解序列化值
     */
    private  static function _formatKeyValue($data)
    {
        $value      = unserialize($data['value']);

        $second_des = unserialize($data['second_desc']);

        $valArr = [];

        if (is_array($value)) {

            foreach ($value as $key =>$item) {

                $valArr[$key]['value'] = $item;

                $valArr[$key]['second_desc'] = isset($second_des[$key])?$second_des[$key]:'';
            }

            $data['value'] = $valArr;

        } else {

            $data['value'] = $value;
        }

        return $data;
    }

    /**
     * @param $key
     * @param string $configType
     * @return array|mixed
     * @desc 获取配置文件
     */
    public static function getConfig( $key)
    {
        return ActivityConfigModel::getConfig($key);
    }
}