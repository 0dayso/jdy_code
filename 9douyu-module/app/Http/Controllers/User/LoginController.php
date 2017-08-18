<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/18
 * Time: 下午3:49
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;

use App\Http\Logics\RequestSourceLogic;
use Illuminate\Support\Facades\Redirect;

/**
 * 登陆模块
 * Class LoginController
 * @package App\Http\User
 */
class LoginController extends UserController
{
    /**
     * @SWG\Post(
     *   path="/login/doLogin",
     *   tags={"JDY-Api"},
     *   summary="登陆【pc\wap】 -> 创建 [User\LoginController@doLogin]",
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
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap"}
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
     *      name="factor",
     *      in="formData",
     *      description="加密因子[pc/wap 取ip]",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="username",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="13632403818",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="密码",
     *      required=true,
     *      type="string",
     *      default="admin123",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="登陆 -> 登陆成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="登陆 -> 登陆失败。",
     *   )
     * )
     */
    public function doLogin(Request $request){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/');
        }
        $data   =[
            'factor'     => $request->input('factor'),  // 非browser 的 客户端 传入的加密 token的因子
            'username'   => $request->input('username'),
            'password'   => $request->input('password'),
        ];

        $LoginLogic = new LoginLogic();
        $data       = $LoginLogic->in($data);

        // 如果浏览器访问 写入 cookie
        if($data['status']) {
            $LoginLogic->handleFrom($data['data']);
            //跳转到实名认证
            return redirect('/user');
        }
        return self::returnJson($data);
    }

    /**
     * 退出
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function out(Request $request){
        $data = LoginLogic::destroy();
        if($request->isMethod('POST')){
            return self::returnJson($data);
        }else{
            return redirect('/');
        }
    }

    /**
     * 延长会话
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    protected function prolongSession(Request $request){
        //已经登陆的用户
        if(SessionLogic::getTokenSession()) {
            return redirect('/');
        }

        $data   =[
            'factor'          => $request->input('factor'),           //  客户端 传入的加密 token的因子
            'refresh_token'   => $request->input('refresh_token'),
        ];

        $LoginLogic   = new LoginLogic();
        $data         = $LoginLogic->prolongSession($data);
        // 如果浏览器访问 写入 cookie
        if($data['status']) {
            $LoginLogic->handleFrom($data['data']);
        }
        return self::returnJson($data);
    }


    /**
     * todo test pc/wap 登陆页面
     *
     */
    public function index($openid = 0){

        return view('pc.user/login');

        /*echo '来源';
        echo 'ios：'. md5('ios')."<br/>";
        echo 'wap：'. md5('wap')."<br/>";
        echo 'pc：'. md5('pc')."<br/>";
        echo 'android：' . md5('android')."<br/>";
        echo '<br/>';
        echo '<pre> 授权登陆客户端：clients：';
        echo '<br/>';
        echo '9e304d4e8df1b74cfa009913198428ab NULL      password refresh_token NULL   NULL
bc54f4d60f1cec0f9a6cb70e13f2127a   NULL      password refresh_token NULL   NULL
c31b32364ce19ca8fcd150a417ecce58   NULL      password refresh_token NULL   NULL
ca4d8c5af3036c2f6d8f533a054457fd   NULL      password refresh_token NULL   NULL';

        echo '<br/>';
        echo '登陆后获取的数据 ：session：'.print_r(SessionLogic::getTokenSession(), true);

        echo '<br/>';

        echo '来源：'.RequestSourceLogic::getSource();
        echo '<br/>';

        echo '请求token后获取的数据 回传已获得登陆状态：token【oauth token】、tokenKey【根据factor加密的字符串】、factor【加密因子】、request_source【来源】';
        echo '
            <form method="post" action="/login/doLogin">
                <label> 登陆 </label><br />
                <input type="hidden" name="_token" value="'.csrf_token().'" />
                <input type="hidden" name="openid" value="'.$openid.'" />
                <input type="text" name="username" value="15201594661" />
                <input type="text" name="password" value="admin123" />
                <input type="submit" name="authorized" value="登陆">
            </form>'
        ;*/

    }

    /**
     * todo test 刷新token
     */
    public function prolongSessionIndex(){
        exit('
            <form method="post" action="/login/prolongSession">
                <label> 刷新token </label><br />
                <input type="text" name="refresh_token" value="866316d27683ce14061bca74b3770ac300624689" />
                <input type="submit" name="authorized" value="刷新token">
            </form>'
        );

    }

}