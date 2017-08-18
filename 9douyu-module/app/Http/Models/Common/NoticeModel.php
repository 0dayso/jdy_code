<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 下午12:01
 * Desc: 站内信
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Models\Model;
use App\Lang\LangModel;

class NoticeModel extends Model{

    public static $codeArr            = [
        'doSend'            => 1,
        'getListByUserId'   => 2,
        'batchUpdateReadByUserId'   => 3,
        'getNoticeListByUserId' => 4,
        'getSiteNoticeListByUserId' => 5
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_NOTICE;


    /**
     * @param $data
     * @param int $type
     * @return mixed
     * @throws \Exception
     * @desc 执行发送(单个)
     */
    public static function doSend($data){

        $res = \DB::table('notice')->insert($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_NOTICE_COMMON'), self::getFinalCode('doSend'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @desc 根据user_id批量更新用户未读消息
     */
    public static function batchUpdateReadByUserId($userId){

        $res = \DB::table('notice')
            ->where('user_id', $userId)
            ->where('is_read', NoticeDb::UNREAD)
            ->update(array('is_read' => NoticeDb::READ));

        if( $res === false ){

            throw new \Exception(LangModel::getLang('ERROR_NOTICE_COMMON'), self::getFinalCode('batchUpdateReadByUserId'));

        }

        return $res;
    }

    /**
     * @param $userId
     * @param $noticeId
     * @return mixed
     * @throws \Exception
     * @desc 阅读系统消息
     */
    public static function readSystemMsg($userId, $noticeId){

        $res = \DB::table('notice_read')->insert(
            [
                'user_id'   => $userId,
                'notice_id' => $noticeId
            ]
        );

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_NOTICE_COMMON'), self::getFinalCode('readSystemMsg'));

        }

        return $res;

    }

    /**
     * @param int $userId
     * @return mixed
     * @throws \Exception
     * @desc 查询站内信,不含站内公告
     */
    public function getNoticeListByUserId($userId=0, $page=1, $size=7){

        $dbPrefix = env('DB_PREFIX');

        $offset = ( max(0, $page -1) ) * $size;

        //上线一个月,通过数据分析,站内信的阅读量是1%,把sql直接简单化

        $sql = "select * from {$dbPrefix}notice where user_id = {$userId} and is_read = ".NoticeDb::UNREAD." order by id desc limit {$offset}, {$size}";

        /*$sql = "select * from {$dbPrefix}notice where user_id = {$userId} and is_read = ".NoticeDb::UNREAD."
union
select * from {$dbPrefix}notice where user_id = 0 and is_read = ".NoticeDb::UNREAD." and type <> ".NoticeDb::TYPE_SITE_NOTICE." and id not in (
	select notice_id from {$dbPrefix}notice_read where user_id = {$userId}
)
union
select * from {$dbPrefix}notice where user_id = {$userId} and is_read = ".NoticeDb::READ."
union
select id, title, user_id, message, 1 as is_read, type, created_at, updated_at from {$dbPrefix}notice where type <> ".NoticeDb::TYPE_SITE_NOTICE." and id in (
	select notice_id from {$dbPrefix}notice_read where user_id = {$userId}
) order by  `is_read`,  id desc limit {$offset}, {$size}";*/

        return app('db')->select($sql);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户未读的公告
     */
    public function getUserUnReadSiteNoticeList($userId){

        $dbPrefix = env('DB_PREFIX');

        $sql = "select * from {$dbPrefix}notice where user_id = {$userId} and is_read = ".NoticeDb::UNREAD."
union 
select * from {$dbPrefix}notice where user_id = 0 and is_read = ".NoticeDb::UNREAD." and type = ".NoticeDb::TYPE_SITE_NOTICE." and id not in (     
	select notice_id from {$dbPrefix}notice_read where user_id = {$userId} 
)";

        return app('db')->select($sql);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户未读站内信
     */
    public function getUserUnReadNoticeList($userId){

        $dbPrefix = env('DB_PREFIX');

        $sql = "select * from {$dbPrefix}notice where user_id = {$userId} and is_read = ".NoticeDb::UNREAD;

        return app('db')->select($sql);

    }



    /**
     * @param $userId
     * @param int $page
     * @param int $size
     * @return mixed
     * @throws \Exception
     * @desc 获取站内公告列表
     */
    public function getSiteNoticeListByUserId($userId=0, $page=1, $size=7){

        $dbPrefix = env('DB_PREFIX');

        $offset = ( max(0, $page -1) ) * $size;

        $sql = " 
select * from {$dbPrefix}notice where user_id = 0 and type = ".NoticeDb::TYPE_SITE_NOTICE." and id not in (      	
	select notice_id from {$dbPrefix}notice_read where user_id = {$userId}  
)   
union  
select id, title, user_id, message, 1 as is_read, type, created_at, updated_at from {$dbPrefix}notice where user_id = 0 and type = ".NoticeDb::TYPE_SITE_NOTICE." and id in (      	
	select notice_id from {$dbPrefix}notice_read where user_id = {$userId}  
) order by  `is_read`,  id desc limit {$offset}, {$size}";

        return app('db')->select($sql);

    }







}