<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/2/23
 * Time: 下午6:31
 **/

namespace App\Http\Controllers\AppApi\V4_0\Notice;

use App\Http\Controllers\App\AppController;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Notice\NoticeLogic;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;

class NoticeController extends AppController{

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/notice",
     *   tags={"APP-Home"},
     *   summary="公告 [Notice\NoticeController@getNotice]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=true,
     *      type="string",
     *      default="5",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
   public function getNotice(Request $request){

       $page = $request->input('page',1);

       $size = $request->input('size',5);

       $noticeLogic = new NoticeLogic();

       $userId = $this->getUserId();
       
       $data = $noticeLogic->getListByUserIdType($userId, $page, $size, NoticeDb::TYPE_SITE_NOTICE);

       return AppLogic::callSuccess($data);

   }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/site_notice",
     *   tags={"APP-Home"},
     *   summary="站内信 [Notice\NoticeController@getSiteNotice]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=true,
     *      type="string",
     *      default="5",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function getSiteNotice(Request $request){

        $page = $request->input('page',1);

        $size = $request->input('size',5);

        $noticeLogic = new NoticeLogic();

        $userId = $this->getUserId();

        $data = $noticeLogic->getListByUserIdType($userId, $page, $size);

        return AppLogic::callSuccess($data);

    }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/read_notice",
     *   tags={"APP-Home"},
     *   summary="标记站内公告为已读 [Notice\NoticeController@readNotice]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="公告id",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败。",
     *   )
     * )
     */
    public function readNotice(Request $request){

        $id = $request->input('id');

        $noticeLogic = new NoticeLogic();

        $userId = $this->getUserId();

        $noticeLogic->readSystemMsg($userId, $id);

        return AppLogic::callSuccess();

    }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/check_is_show_notice_tip",
     *   tags={"APP-Home"},
     *   summary="检测首页站内信是否显示小红点 [Notice\NoticeController@checkIsShowNoticeTip]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败。",
     *   )
     * )
     */
    public function checkIsShowNoticeTip(){

        $noticeLogic = new NoticeLogic();

        $userId = $this->getUserId();

        $result = $noticeLogic->checkIsShowNoticeTip($userId);

        return AppLogic::callSuccess($result ? ['is_show' => 1] : ['is_show' => 0]);

    }



}
