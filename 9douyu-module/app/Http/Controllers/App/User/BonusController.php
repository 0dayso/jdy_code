<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午3:10
 * Desc: 红包加息券相关
 */
namespace App\Http\Controllers\App\user;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Http\Request;

class BonusController extends UserController{

    /**
     * @SWG\Post(
     *   path="/user_bonus",
     *   tags={"APP-Bonus"},
     *   summary="用户红包加息券列表 [User\BonusController@index]",
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
     *      default="2.2.3",
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
     *      name="type",
     *      in="formData",
     *      description="红包\加息券可用状态 1-可用 2-已过期",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="1",
     *      enum={"1","2"}
     *   ),
     *     @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *     @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *      default="10",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户红包加息券列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户红包加息券列表失败。",
     *   )
     * )
     */
    public function index( Request $request ){

        $userId = $this->getUserId();

        $type = (int)$request->input('type', 2);
        $page = (int)$request->input('p', 1);
        $size = (int)$request->input('size', 2);

        $logic = new UserBonusLogic();

        $res = $logic -> getAppBonus($userId, $page, $size, $type);

        return self::appReturnJson($res);

    }


    /**
     * @SWG\Post(
     *   path="/user_current_bonus_list",
     *   tags={"APP-Bonus"},
     *   summary="零钱计划加息券信息接口 [User\BonusController@userCurrentAbleBonusList]",
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
     *      default="2.2.3",
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
     *     description="获取零钱计划加息券信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划加息券信息失败。",
     *   )
     * )
     */
    public function userCurrentAbleBonusList( Request $request ){

        $userId = $this->getUserId();

        $client = $this -> client;

        $logic = new UserBonusLogic();

        $res   = $logic -> getAppUserCurrentAbleBonus($userId, $client);

        return self::appReturnJson($res);

    }

    //53. 投资页面-获取优惠劵接口	user_usable_bonus


    /**
     * @SWG\Post(
     *   path="/user_usable_bonus",
     *   tags={"APP-Bonus"},
     *   summary="投资页面-获取优惠劵接口 [User\BonusController@userProjectAbleBonusList]",
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
     *      default="2.2.3",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取投资页面-获取优惠劵接口成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取投资页面-获取优惠劵接口失败。",
     *   )
     * )
     */
    public function userProjectAbleBonusList( Request $request ){

        $projectId = $request->input('project_id');

        $userId = $this->getUserId();

        $client = $this -> client;

        $logic = new UserBonusLogic();

        $res   = $logic -> getAppUserUsableBonus($userId, $projectId, $client);

        $res['data']['list'] = empty($res['data']['list'])?[[]]:$res['data']['list'];

        return self::appReturnJson($res);

    }

    /**
     * @SWG\Post(
     *   path="/user_do_current_bonus",
     *   tags={"APP-Current"},
     *   summary="使用零钱计划加息券 [User\BonusController@doUserCurrentBonus]",
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
     *      default="2.2.3",
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
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="bonus_id",
     *      in="formData",
     *      description="加息券ID",
     *      required=true,
     *      type="integer",
     *      default="2",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="使用零钱计划加息券成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="使用零钱计划加息券失败。",
     *   )
     * )
     */
    public function doUserCurrentBonus(Request $request){

        $data                    = $request->all();
        $data['user_id']         = $this->getUserId();
        $data['from']            = RequestSourceLogic::getSource();

        //app 3.1.0 版本以上(包括3.1.0) 零钱计划转出取消交易密码的验证
        $isTrade            = true;

        if($this->compareVersion($data['version'], self::THIS_NEW_FIX_VERSION)){

            $isTrade        = false;

        }

        $logic  = new UserBonusLogic();

        $result = $logic->doUserCurrentBonus($data, $isTrade);

        return self::appReturnJson($result);
    }

}