<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/15
 * Time: 下午1:56
 * Desc: 系统配置相关
 */

namespace App\Http\Logics\Module\SystemConfig;

use App\Http\Dbs\SystemConfigDb;
use App\Http\Logics\Logic;

class SystemConfigLogic extends Logic
{

    /**
     * @return array
     * @desc 获取列表信息
     */
    public function getList()
    {

        $db = new SystemConfigDb();

        $list = $db->getList();

        $list = ['total' => count($list), 'list' => $list];

        return self::callSuccess($list);

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取详情
     */
    public function getInfoById($id)
    {

        $db = new SystemConfigDb();

        $info = $db->getInfoById($id);

        return self::callSuccess($info);

    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 编辑详情
     */
    public function editInfo($id, $data)
    {

        if( !$id || empty($data) ){

            return self::callError('参数不正确');

        }

        $db = new SystemConfigDb();

        $res = $db->editInfo($id, $data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('更新失败');

        }

    }


    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 编辑详情(通过key)
     */
    public function editByKey($key, $data)
    {

        if( !$key || empty($data) ){

            return self::callError('参数不正确');

        }

        $db = new SystemConfigDb();

        $res = $db->editByKey($key, $data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('更新失败');

        }

    }

    /**
     * @param $data
     * @return array
     * @desc 添加信息
     */
    public function addInfo($data)
    {

        $db = new SystemConfigDb();

        $res = $db->addInfo($data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('更新失败');

        }

    }

    /**
     * @param $key
     * @return bool|mixed
     * @desc 获取配置信息
     */
    public static function getConfig($key)
    {

        if( !$key ){

            return false;

        }

        $db = new SystemConfigDb();

        $res = $db->getInfoByKey($key);

        if( !empty($res) ){

            $res['value'] = unserialize($res['value']);

            $res['second_des'] = unserialize($res['second_des']);

        }

        return $res;

    }


}