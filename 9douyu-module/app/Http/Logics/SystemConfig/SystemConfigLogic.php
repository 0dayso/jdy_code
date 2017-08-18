<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/13
 * Time: 下午6:25
 */
namespace App\Http\Logics\SystemConfig;

use App\Http\Logics\Logic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\AdminUser;

class SystemConfigLogic extends Logic
{

    /**
     * @param $page
     * @param $size
     * @param $keyWord
     * @return mixed
     * @desc 获取列表
     */
    public function getAllList( $page, $size, $keyWord, $configType=''){

        $model = new SystemConfigModel();

        $list = $model -> getAllList( $page, $size, $keyWord, $configType);

        return $list;
    }

    /**
     * @param $id
     * @return array
     * @desc 通过Id获取数据
     */
    public function getConfigById($id, $configType=''){

        $model = new SystemConfigModel();

        try{

            $result = $model->getConfigById($id, $configType);

            $result     = $this -> _formatKeyValue($result);

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
    public function doCreate($data){

        $data = $this -> _filterParams($data);

        try{

            $model = new SystemConfigModel();

            $result = $model -> doCreate($data);

        }catch (\Exception $e){

            return self::callError($e -> getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 更新配置信息
     */
    public function doUpdate($id, $data)
    {

        $data = $this -> _filterParams($data);

        try{

            $model      = new SystemConfigModel();

            $result = $model -> doUpdate($id, $data);


        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化数据
     */
    public function _filterParams($data){

        $data = array(
            'name'          => trim($data['name']),
            'key'           => trim($data['key']),
            'value'         => serialize($this->_getKeyValue($data)),
            'second_des'    => serialize($this->_getKeyValue($data,'second_key','second_des'))?:'',
            'user_id'       => AdminUser::getAdminUserId(),
            'status'        => trim($data['status']),
            'config_type'   => $data['config_type']
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
    private function _getKeyValue($data, $key='second_key',$valueKey = 'second_value'){
        if(isset($data[$key]) && isset($data[$valueKey])){
            $keyArr = $data[$key];
            $valueArr = $data[$valueKey];
            $value = array_combine($keyArr, $valueArr);
        }else{
            $value = $data['value'];
        }
        return $value;
    }

    /**
     * @param $data
     * @return mixed
     * @desc 解序列化值
     */
    private function _formatKeyValue($data){

        $value      = unserialize($data['value']);

        $second_des = unserialize($data['second_des']);

        $valArr = [];

        if (is_array($value)) {
            foreach ($value as $key =>$item) {
                $valArr[$key]['value'] = $item;
                $valArr[$key]['second_des'] = isset($second_des[$key])?$second_des[$key]:'';
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
    public static function getConfig( $key,$configType='')
    {
        return SystemConfigModel::getConfig($key,$configType);
    }

}