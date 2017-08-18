<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 下午2:17
 */

namespace App\Http\Logics\Notice;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Logic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\NoticeModel;

class NoticeLogic extends Logic{


    /**
     * @param string $title
     * @param int $userId
     * @param string $message
     * @param int $type
     * @return array
     * @desc 发送单个站内信
     */
    public static function sendNoticeByType($title='', $userId=0, $message='', $type=NoticeDb::TYPE_DEFAULT){

        if( !$type || empty($title) || empty($message) || $userId<1 ){
            
            return self::callError('参数有误');

        }

        try{

            NoticeModel::doSend(['title' => $title, 'user_id' => $userId, 'message' => $message, 'type' => $type]);

            return self::callSuccess();

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param string $title
     * @param array $userIds
     * @param string $message
     * @param int $type
     * @return array
     * @desc 针对用户发送站内信
     */
    public static function batchSend($title='', $userIds=[], $message='', $type=NoticeDb::TYPE_DEFAULT){

        if( !$type || empty($title) || empty($message) || empty($userIds) ){

            return self::callError('参数有误');

        }

        $data = [];

        foreach ($userIds as $userId){

            $data[] = [
                'user_id'   => $userId,
                'title'     => $title,
                'message'   => $message,
                'type'      => $type
            ];

        }

        try{

            NoticeModel::doSend($data);

            return self::callSuccess();

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param string $title
     * @param string $message
     * @param int $type
     * @return array
     * @desc 发送系统站内信
     */
    public static function sendSystemNotice($title='', $message='', $type = NoticeDb::TYPE_SYSTEM){

        if( empty($title) || empty($message) ){

            return self::callError('参数有误');

        }

        try{

            NoticeModel::doSend(['title' => $title, 'user_id' => 0, 'message' => $message, 'type' => $type]);

            return self::callSuccess();

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param string $type
     * @return array|mixed|string
     * @desc 通过type获取站内信的模板信息
     */
    public static function getMsgTplByType($type=''){

        if( empty($type) ){

            return '';

        }

        $result = SystemConfigLogic::getConfig('NOTICE_TPL.TYPE_'.$type);

        return empty($result)?'':$result;

    }

    /**
     * @param $userId
     * @return array
     * @desc 批量更新用户站内信
     */
    public static function batchUpdateReadByUserId($userId){

        try{

            NoticeModel::batchUpdateReadByUserId($userId);

            return self::callSuccess();

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $userId
     * @param $noticeId
     * @return array
     * @desc 系统站内信、公告
     */
    public static function readSystemMsg($userId, $noticeId){

        try{

            NoticeModel::readSystemMsg($userId, $noticeId);

            return self::callSuccess();

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $userId
     * @param int $page
     * @param int $size
     * @param int $type
     * @return mixed
     * @desc 通过userid和type获取列表
     */
    public function getListByUserIdType($userId, $page=1, $size=7, $type=''){

        if( $type == NoticeDb::TYPE_SITE_NOTICE ){

            $data = $this->getSiteNoticeListByUserId($userId, $page, $size);

        }else{

            $data = $this->getNoticeListByUserId($userId, $page, $size);

        }

        return $this->formatList($data);

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化数据
     */
    private function formatList($data){

        if( empty($data) ){

            return [];

        }

        $returnData = [];

        foreach( $data as $item ){

            $returnData[] = [
                'id'            => $item->id,
                'title'         => $item->title,
                'user_id'       => $item->user_id,
                'message'       => $item->message,
                'is_read'       => $item->is_read,
                'created_at'    => $item->created_at,
                'url'           => env('APP_URL_WX').'/Article/index/'.$item->title
            ];

        }

        return $returnData;

    }

    /**
     * @param $userId
     * @param int $page
     * @param int $size
     * @return mixed
     * @throws \Exception
     * @desc 获取站内公告
     */
    public function getSiteNoticeListByUserId($userId, $page=1, $size=7){

        $model = new NoticeModel();

        return $model->getSiteNoticeListByUserId($userId, $page, $size);

    }

    /**
     * @param $userId
     * @param int $page
     * @param int $size
     * @return mixed
     * @throws \Exception
     * @desc 获取站内信
     */
    public function getNoticeListByUserId($userId, $page=1, $size=7){

        $model = new NoticeModel();

        $data = $model->getNoticeListByUserId($userId, $page, $size);

        $this->batchUpdateReadByUserId($userId);

        return $data;

    }

    /**
     * @param $userId
     * @return bool
     * @desc 通过userid获取notice的tip显示
     */
    public function checkIsShowNoticeTip($userId){

        $model = new NoticeModel();

        //站内公告
        $unReadSiteList = $model->getUserUnReadSiteNoticeList($userId);

        if( !empty($unReadSiteList) ){

            return true;

        }

        return false;

    }

}