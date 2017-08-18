<?php

/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2016/12/19
 * Time: 下午2:10
 */

namespace App\Http\Dbs\Oss;

use App\Http\Dbs\JdyDb;

class OssFilePathDb extends JdyDb
{

    protected $table = "oss_file_path";

    /**
     * @param $data
     * @return bool
     * @desc 添加数据
     */
    public function addInfo($data)
    {

        $this->oss_path = $data['oss_path'];

        $this->crc32_path = $data['crc32_path'];

        $this->bucket_name = $data['bucket_name'];

        $this->type = $data['type'];

        return $this->save();

    }

    /**
     * @param $crcPath
     * @return mixed
     * @desc 通过crc32码获取文件在oss上的path
     */
    public function getByCrc($crcPath)
    {

        return $this->select('oss_path','type')
            ->where('crc32_path', $crcPath)
            ->get()
            ->first();

    }

    /**
     * @param $data
     * @return mixed
     * @desc 批量添加数据
     */
    public function infoInsert($data)
    {
        return $this->insert($data);
    }

}