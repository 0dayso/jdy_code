<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/6
 * Time: 下午2:58
 */

namespace App\Http\Logics\User;

use App\Http\Dbs\User\AvatarDb;

use App\Http\Logics\Logic;

use App\Http\Logics\Oss\OssLogic;
use App\Http\Logics\RequestSourceLogic;
use Log;
/**
 * 头像相关
 *
 * Class AvatarLogic
 * @package App\Http\Logics\User
 */
class AvatarLogic extends Logic
{
    /**
     * 获取登陆用户头像
     */
    public static function getAvatar(){
        $userInfo = SessionLogic::getTokenSession();
        if(isset($userInfo['id'])){
            $avatarRecord = AvatarDb::getUserAvatarByUserId($userInfo['id']);
            $return = [];
            if($avatarRecord) {
                $return = [
                    'items' => $avatarRecord,
                ];
            }
            Log::info('avatar::getAvatar - userId:'. $userInfo['id'], $return);
            return self::callSuccess($return);
        }else{
            return self::callError('请先登陆');
        }

    }

    /**
     * @param int $userId
     * @param string $client
     * @param string $version
     * @param array $data
     * @return array
     * @desc 头像上传
     */
    public function upAvatar($userId, $client, $version, $data){

        Log::info('avatar::upAvatar - userId:'. $userId, $data);
        if(empty($data)){
            return self::callError('上传失败');
        }

        try{

            $image = $data["tmp_name"];
            $fp = fopen($image, "r");
            $file = fread($fp, $data["size"]); //二进制数据流

            $bits = array(
                'jpeg' => "\xFF\xD8\xFF",
                'gif' => "GIF",
                'png' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a",
                'bmp' => 'BM',
            );

            $extArr[1] = '';
            $ext       = '';
            $extArr = explode('.', $data['name']);

            foreach($bits as $type => $bit){
                if($type == $extArr[1]){
                    $ext = $type;
                }
            }

            $object = 'uploads/avatar/'.date("Ymd",time())."/".md5(time().mt_rand(10, 99)).'.'.$ext;

            $ossLogic = new OssLogic();
            $ossLogic->writeFile($file,$object);

            $appRequest = RequestSourceLogic::getSourceKey($client);

            $param = [
                'user_id'     => $userId,
                'avatar_url'  => $object,
                'app_request' => $appRequest,
                'status'      => 200,     //todo 默认通过审核,后续完善判断
                'version'     => $version,
            ];
            $avatarDb = new AvatarDb();
            $result = $avatarDb->add($param);

            if($result){
                Log::info('avatar::upAvatar - userId:'. $userId, $param);
                $path = assetUrlByCdn('/'.$object);
                return self::callSuccess($path);
            }

        }catch(\Exception $e){
            Log::error('avatar::getAvatar - userId:'. $userId, $e->getMessage());
            return self::callError($e->getMessage());
        }

    }

}