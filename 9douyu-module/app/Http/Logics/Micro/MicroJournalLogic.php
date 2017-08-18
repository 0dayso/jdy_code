<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/19
 * Time: 上午11:00
 */

namespace App\Http\Logics\Micro;


use App\Http\Dbs\Micro\MicroJournalDb;
use App\Http\Dbs\Picture\PictureDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Oss\OssLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Micro\MicroJournalModel;
use App\Http\Models\Picture\PictureModel;

class MicroJournalLogic extends Logic
{
    /**
     * @param $id
     * @return mixed
     * @desc 获取数据
     */
    public function getById( $id )
    {
        $db     =   new MicroJournalDb();

        $result =   $db->getById($id);

        return self::doAnalyticData($result);
    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * @desc 列表数据
     */
    public function getMicroJournalList( $page, $size)
    {
        $db     =   new MicroJournalDb();

        $result =   $db->getList($page, $size);

        if( !empty( $result['list']) ){

            foreach ($result['list'] as $key => $item ){

                $result['list'][$key]   =  self::doAnalyticData($item);
            }
        }

        return $result;
    }
    /**
     * @param $data
     * @return array
     * @desc 添加数据
     */
    public function doAdd( $data )
    {
        $data         =   self::doFormatFilterParams( $data );

        $pictureModel = new PictureModel();

        $oss          = new OssLogic();

        try{

            self::beginTransaction();

            if(!empty($_FILES['img']) ){

                $upload             = $oss->putFile($_FILES['img'],'resources/images');

                $imgPath = substr($upload['data']['path'],strpos($upload['data']['path'],'/')+1).'/'.$upload['data']['name'];

                $result = $pictureModel -> doCreate($imgPath);

                $data['picture_id'] = $result;

            }

            unset($data['img']);

            MicroJournalModel::doAdd($data);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess();
    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 更新数据
     */
    public function doEdit( $id, $data )
    {
        $data         =   self::doFormatFilterParams( $data );

        $pictureModel = new PictureModel();

        $oss          = new OssLogic();

        try{

            self::beginTransaction();

            MicroJournalModel::doVerifyId($id);

            if(!empty($_FILES['img']) ){

                $upload             = $oss->putFile($_FILES['img'],'resources/images');

                $imgPath = substr($upload['data']['path'],strpos($upload['data']['path'],'/')+1).'/'.$upload['data']['name'];

                $result = $pictureModel -> doCreate($imgPath);

                $data['picture_id'] = $result;

            }

            unset($data['img']);

            MicroJournalModel::doEdit($id,$data);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }
    }

    /**
     * @param $id
     * @return array
     * @desc 删除信息
     */
    public function doDelete( $id )
    {

        try{

            self::beginTransaction();

            MicroJournalModel::doVerifyId($id);

            MicroJournalModel::doDelete($id);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }
    }

    /**
     * @param $data
     * @return array
     * @desc 格式化数据
     */
    protected static function doFormatFilterParams( $data )
    {

        $content    =   [
            'title'         =>  isset($data['title']) ? $data['title'] : '',
            'link'          =>  isset($data['link']) ? $data['link'] : '',
            'content'       =>  isset($data['content']) ? $data['content'] : "",
        ];

        return [
            'date'          =>  isset($data['date']) ? $data['date'] : '',
            'img'           =>  isset($data['img']) ? $data['img'] : '',
            'picture_id'    =>  isset($data['picture_id']) ? $data['picture_id'] : '0',
            'params'        =>  json_encode($content),
            'status'        =>  isset($data['status']) ? $data['status'] : MicroJournalDb::RELEASE_STATUS_IS_OPEN,
        ];
    }

    /**
     * @param $data
     * @return array
     * @desc 解析数据
     */
    protected static function doAnalyticData( $data )
    {
        if( empty($data) ){

            return [];
        }

        $params             =   json_decode($data['params'],true);

        $data['title']      =   $params['title'];

        $data['link']       =   $params['link'];

        $data['content']    =   $params['content'];

        return $data;
    }

    /**
     * @return array
     * @desc 按照刊号的降续排列获取最新的微刊信息
     */
    public function getLastMicroByDate()
    {
        $db     =   new MicroJournalDb();

        $result =   $db->getLastMicroByDate();

        return self::doAnalyticData($result);
    }

    /**
     * @param int $id
     * @return bool|string
     * @desc 读取图片内容
     */
    public function getPictureById( $id = 0)
    {
        if( $id == 0) {

            return '';
        }

        $pictureDB  =   new PictureDb();

        return $pictureDB->getPicturePath($id);

    }

}
