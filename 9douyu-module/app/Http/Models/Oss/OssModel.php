<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2016/12/19
 * Time: 下午7:13
 */

namespace App\Http\Models\Oss;

use App\Http\Models\Model;
use App\Http\Dbs\Oss\OssFilePathDb;
use App\Lang\LangModel;

class OssModel extends Model
{

    /**
     * @desc 添加对应信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function setInfo($data)
    {

        $db = new OssFilePathDb();

        $result = $db->addInfo($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ADD_PICTURE_PATH'), self::getFinalCode('addInfo'));
        }

        return $result;
    }

    /**
     * @desc 获取oss文件路径
     * @param $crc
     * @return array
     * @throws \Exception
     */
    public function getFileUrl($crc)
    {

        $db     = new OssFilePathDb();

        $result = $db->getByCrc($crc);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_GET_PICTURE_PATH'), self::getFinalCode('getFileUrl'));
        }

        return $result->toArray();

    }

    /**
     * @desc 目录上传信息批量添加
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function setAllInfo($data)
    {
        $db = new OssFilePathDb();

        $result = $db->infoInsert($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ADD_DIRECTORY_PATH'), self::getFinalCode('setAllInfo'));
        }

        return $result;
    }

}