<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/1
 * Time: 13:54
 */

namespace App\Http\Dbs\Media;

use App\Http\Dbs\JdyDb;

class InviteDb extends JdyDb{


    protected $table = "media_invite";


    /**
     * @param $userId
     * @param $channelId
     * @param $from
     * @param string $version
     * @return mixed
     * 添加记录
     */
    public function addRecord($userId,$channelId,$from,$version=''){

        $data = [
            'user_id'       => $userId,
            'channel_id'    => $channelId,
            'app_request'   => $from,
            'version'       => $version
        ];

        return self::insert($data);
    }
}