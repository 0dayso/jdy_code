<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:53
 * Desc: 实名认证
 */
namespace App\Http\Controllers\App\DownLoad;

use App\Http\Controllers\App\AppController;

use App\Http\Logics\Version\VersionLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
/**
 * 下载
 *
 * Class DownLoadController
 * @package App\Http\Controllers\App\DownLoad
 */
class DownLoadController extends AppController{

    /**
     * @SWG\Post(
     *   path="/invite_user_down",
     *   tags={"APP-Version"},
     *   summary="邀请好友下载 [Download\DownLoadController@getDown]",
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
     *     description="版本检测 -> 成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="版本检测 -> 失败。",
     *   )
     * )
     */
    /**
     * 移植自老系统
     */
    public function getDown(){
        //邀请码的这个需要去掉(因为这个是合伙人活动之后才有的)
        $result = ['status' => true];
        // todo http://wx.9douyu.com/zt/appguide.html 移植
        // todo 图片移植
        $result['data']['items']['weixin']['share_title'] = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['weixin']['share_desc']  = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['weixin']['share_url']   = 'http://wx.9douyu.com/zt/appguide.html?from=singlemessage&isappinstalled=1';
        $result['data']['items']['weixin']['share_image'] = env("WEB_URL") . "/static/images/new/share.png";

        $result['data']['items']['friend']['share_title'] = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['friend']['share_desc']  = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['friend']['share_url']   = 'http://wx.9douyu.com/zt/appguide.html?from=singlemessage&isappinstalled=1';
        $result['data']['items']['friend']['share_image'] = env("WEB_URL") . "/static/images/new/share.png";

        $result['data']['items']['qq']['share_title']     = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['qq']['share_desc']      = '九斗鱼，心有所安，财有所余';
        $result['data']['items']['qq']['share_url']       = 'http://wx.9douyu.com/zt/appguide.html?from=singlemessage&isappinstalled=1';
        $result['data']['items']['qq']['share_image']     = env("WEB_URL") . "/static/images/new/share.png";

        //不判断客户端,把Android和iOS的都返回过去

        $result['data']['items']['down_url']              = env("WEB_URL") . "/static/images/new/footer-code-new.png";

        $this->appReturnJson($result);
    }
}