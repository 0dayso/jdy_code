<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Dbs\User\OAuthAccessTokenDb;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolDomainCookie;
use Illuminate\Http\Request;
use App\Http\Logics\User\TokenLogic;
use Log;
use Cache;
use Redirect;
/**
 * App模块基础类
 * Class AppController
 * @package App\Http\Controllers\Pc
 */
class AppController extends BaseController
{

    /**
     * @todo 不同的业务场景，可以定义不同code
     */
    const
        CODE_SUCCESS                = 2000,  //服务端返回正常数据
        CODE_ERROR                  = 4000,  //服务端异常
        CODE_TRADING_PASSWORD       = 4009,  //交易密码输入错误
        CODE_LOGIN_EXPIRE           = 4010,  //登录超时
        CODE_PHONE_NOT_ACTIVATION   = 6001,  //手机号未激活
        CODE_PHONE_CAN_REGISTER     = 6000,  //手机号可注册

        FIX_VERSION                 = "2.0.0", //对比参照版本号
        THIS_NEW_FIX_VERSION        = '3.1.0',
        APP_SECRET_KEY              = "HLK@#$@sdAppha7987",
        MAX_TS_INTERVAL             = 86400, //允许的最大时间误差（1天）

    END=true;

    protected $client;
    protected $token;
    protected $version;

    public function __construct(Request $request){
       
        parent::__construct();

        $this->client  = strtolower($request->input("client"));

        $this->token   = $request->input("token");

        $this->version = $request->input("version");
    }


    /**
     * @param array $data
     * @param string $msg
     * @return array
     * @desc 统一返回成功数据
     */
    public static function callSuccess($data = [], $msg = '成功')
    {

        return [
            'status'    => true,
            'code'      => self::CODE_SUCCESS,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }

    /**
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return array
     * @desc 统一返回失败数据
     */
    public static function callError($msg = '', $code = self::CODE_ERROR, $data = [])
    {

        return [
            'status'    => false,
            'code'      => $code,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }

    /**
     * app请求接口返回数据
     * @param  array  $data
     * @param $code
     * @return array
     */
    public function appReturnJson($data = [], $code = ''){

        $result["server"] = env('WHICH_SERVER');

        $result["client"] = $this->client;

        if( !empty($code) ){

            $result['status'] = $code;

            $result["msg"]    = $data['msg'];

        }elseif($data['status'] == true){

            $result['status'] = self::CODE_SUCCESS;

            $result["msg"]    = (!empty($data['msg']) && $data['msg']!='成功')?$data['msg']:"请求成功";

        }elseif($data['status'] == false){

            $result['status'] = self::CODE_ERROR;

            $result["msg"]    = $data['msg'];

        }


        $result['items'] = [];

        $items = $data['data'];

        if(empty($items['items']) && !empty($items)){

            $result["items"]  = $items;

        }elseif( !empty($items['items']) ){
            $result = array_merge($result, $items);
        }

        $result = $this -> formatReturnData($result);

        //小于2.0.0按之前版本输出格式化数据重新输出
        if($this->compareVersion($this->version, self::FIX_VERSION)) {

            if( isset($result["client"]) ){

                unset($result["client"]);

            }

        }

        return self::returnJson($result);
    }

    /**
     * 比较两个版本大小
     * @param $version  当前版本
     * @param $fixVersion  参照版本
     * @return boolean 【$version > $fixVersion ? true : false】
     */
    public function compareVersion($version, $fixVersion){

        $arr = explode(".", $version);

        $newArr = explode(".", $fixVersion);

        $length = count($newArr);

        for($i = 0; $i < $length; $i++) {

            if($arr[$i] < $newArr[$i]) {

                return false;

            } else if($arr[$i] > $newArr[$i]) {

                return true;
            }
        }
        return true;
    }

    /**
     * @param array $returnData
     * @return mixed
     * @desc 格式化数据为字符串
     */
    protected function formatReturnData($returnData = null) {
        if(!empty($returnData)){
            if(!is_array($returnData)){
                return $returnData;
            }
            //兼容：$data['items'] = 0
            if(isset($returnData['items']) && !is_array($returnData['items']) && $returnData['items'] == 0){
                return $returnData['items'];
            }
            foreach ($returnData as $key => $value) {
                if(is_array($value)) {
                    if(!empty($value)) {
                        $v = $this->formatReturnData($value);
                    } else {
                        $v = array("__EMPTY" => "__EMPTY");
                    }
                } else {
                    $v   = (string)$value;
                }
                $tem[$key] = $v;
            }

        }else{
            $tem = ["__EMPTY" => "__EMPTY"];
        }

        return $tem;
    }

    /**
     * 
     * @SWG\Post(
     *   path="/app_sign_login",
     *   tags={"APP-User"},
     *   summary="app 跳转到 h5 登陆  [AppController@loginWapPage]",
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
     *      default="ios",
     *      enum={"android","ios"}
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
     *      name="url",
     *      in="formData",
     *      description="url",
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
     *      name="encode",
     *      in="formData",
     *      description="【如果存在 url 参数需要 urldecode】",
     *      required=false,
     *      type="string",
     *      default="",
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
    /**
     * app 跳转到 h5 登陆 二次跳转到下一个链接【参数移植自老的九斗鱼】
     */
    public function loginWapPage( Request $request ){

        $version  =  $request->input("version");
        $client   =  $request->input("client");
        $token    =  $request->input("token");
        $url      =  $request->input("url", "/");
        $encode   =  $request->input("encode",'');
        //如果存在 encode 则 urldecode（url）
        if($encode &&  $request->input("url")!=''){
            $url = urldecode($request->input("url"));
        }

        //登陆状态
        $userInfo = SessionLogic::getTokenSession();
        if($userInfo) {
            $tokenLogic               = new TokenLogic();
            $tokenRecord              = OAuthAccessTokenDb::getUserIdByToken($token);
            $expires                  = strtotime($tokenRecord['expires']);
            $tokenExpiresIn           = $expires - time();
            $data['token_expires_in'] = ($tokenExpiresIn > 0) ? $tokenExpiresIn : 0;
            $data['access_token']     = $token;
            Log::info('REMOTE_ADDR_ip:',[ $_SERVER['REMOTE_ADDR'] ]);
            $data['access_token_key'] = $tokenLogic->encryptToken($token,  $_SERVER['REMOTE_ADDR']);
            $data['client']           = $client;
            setcookie(
                env('COOKIE_NAME', 'JDY_COOKIES'),
                SessionLogic::encryptCookie($data),
                time() + (int)$data['token_expires_in'],
                '/',
                ToolDomainCookie::getDomain()
            );

            $isLogin = 1;

        }else{
            //销毁cookie
            setcookie(
                env('COOKIE_NAME', 'JDY_COOKIES'),
                '',
                time() - 1,
                '/',
                ToolDomainCookie::getDomain()
            );

            $isLogin =  0;

        }
        Log::info('loginWapPage',['client'=>$client,'url'=>$url, 'user_id'=>$this->getUserId()]);

        if($client == RequestSourceLogic::SOURCE_ANDROID && strpos($url, 'pay/appConfirm') === false && !empty($url)){
            if(strpos($url, '?from') !== false){
                $url .= "&token={$token}&client={$client}";
            }
            header('location:'.$url);
            die;
        }

        return Redirect::to($url);



        /*
        if( (stripos($url, 'http://') === false) && (stripos($url, 'https://') === false) || (stripos($url, '?from') === false)){
            if(strpos($url, '?') === false){
                $url = $url . '?version='.$version.'&client='.$client;
            }else{
                $url = $url . '&version='.$version.'&client='.$client;
            }

            Log::info('loginWapPage - 1:' . $url);
            return redirect($url);
        }else{
            $url .= '&version='.$version.'&client='.$client.'&isLogin='.$isLogin;
            Log::info('loginWapPage - 2:' . $url);
            return redirect($url);
        }
        */


    }


    protected function androidHtmlJumpUrl($url){

        switch ($this->client){

            case RequestSourceLogic::SOURCE_IOS:{
                $subDomain = env('IOS_SUB_DOMAIN');
                break;
            }
            case RequestSourceLogic::SOURCE_ANDROID:{
                $subDomain = env('ANDROID_SUB_DOMAIN');
                break;
            }
        }

        //跳转页面

        $httpQuery = [

            'version' => $this->version,
            'client' => $this->client,
            'token'	=> $this->token,
            //'url' => $url,
        ];

        $query = http_build_query($httpQuery);


        $url = "http://".$subDomain.env('MAIN_DOMAIN')."/app_sign_login?".$query.'&url='.$url;

        return $url;

    }

    

    /**
     * 设置token（登陆标识）
     */
    protected function setToken($token){

        $this->token = $token;

        return $token;
    }

    /**
     * 获取token（登陆标识）
     */
    protected function getToken(){

        return $this->token;
    }

    /**
     * 设置客户端
     * @param string $client
     */
    protected function setClient($client){

        $this->client = $client;
    }

    /**
     * 获取客户端
     * @param string $client
     */
    protected function getClient(){

        return $this->client;
    }


    /**
     * 设置版本
     * @param string $version
     */
    protected function setVersion($version){

        $this->version = $version;
    }

    /**
     * 获取版本
     * @param string $client
     */
    protected function getVersion(){

        return $this->version;
    }
    
    /**
     * 返回成功信息
     * */
    protected function successRequest($msg){

        $data = self::callSuccess([],$msg);

        return self::appReturnJson($data);
    }

    /**
     * 返回错误信息
     * */
    protected function errorRequest($msg,$code = self::CODE_ERROR){

        $data = self::callError($msg,$code);

        return self::appReturnJson($data);
    }


    /**
     * @param $version
     * @param $key
     *
     * 难证APP是否重复提交
     * key 组成部分
     * (1) 客户端时间戳
     * (2) 六位随机码
     * (3) uid
     * (4) 以上字段md5后生成的sign
     * 判断逻辑
     * (1) 时间误差在24小时内
     * (2) sign验证
     * (3) KEY是否存在于缓存，若存在，直接返回，不存在，存入缓存，有效期1小时
     */

    protected function checkSubmit($version,$key,$versign,$isCheckSubmit){

        //线上环境且版本号大于2.0.0
        if($this->compareVersion($version,self::FIX_VERSION) && $isCheckSubmit){

            if(!$key){
                return $this->errorRequest("缺少unique参数！");
            }
            $arr = explode('-',$key);
            //客户端发送的时间戳
            $ts = $arr[0];
            //判断时间误差24小时内
            $currentTS = time();
            if(($currentTS - self::MAX_TS_INTERVAL >= $ts) || ($currentTS + self::MAX_TS_INTERVAL < $ts)){
                return $this->errorRequest("时间错误！");
            }

            //客户端的sign
            $sign = array_pop($arr);

            //拼接字符串生成sign
            $str = '';
            foreach($arr as $v){
                $str .= $v;
            }
            $str .= $versign;

            //服务端生成的sign
            $currentSign = md5($str);

            if($sign != $currentSign){
                return $this->errorRequest("签名错误！");
            }
            $cacheKey = md5($key);

            $status = Cache::get($cacheKey);

            if($status){
                return $this->errorRequest("请忽重复提交！");
            }

            Cache::put($cacheKey,1,60);

        }
    }

    /**
     * 获取请求来源
     */
    public function getAppRequst(){

        return strtolower($this->getClient());
    }

    /**
     * 版本号排序
     * @author txh
     */
    function sortVersion($versionArr, $sort = "asc") {
        for($i=0;$i<count($versionArr)-1;$i++ ){
            for($j=0; $j<count($versionArr)-1-$i; $j++){
                if( $this->compareVersion($versionArr[$j],$versionArr[$j+1])){
                    $tmp                = $versionArr[$j];
                    $versionArr[$j]     = $versionArr[$j+1];
                    $versionArr[$j+1]   = $tmp;
                }
            }
        }
        if($sort == "desc") $versionArr = array_reverse($versionArr);
        return $versionArr;
    }

    /**
     * 检测版本号
     */
    public function checkVersion($version,$client){
        if($client == 'pfb')//普付宝 不验证版本
            return true;

        $sign       = ".";
        $versionArr = explode($sign, $version);

        if(count($versionArr) >= 4){
            array_pop($versionArr);
            $version = implode($sign, $versionArr);
        }

        $allowVersions = SystemConfigModel::getConfig('APP_VALID_VERSION');

        if(!isset($allowVersions[$version]) || ($allowVersions[$version] == 0)) {
            return $this->errorRequest("当前版本已过期,请前往appstore 搜索“九斗鱼”下载最新版本。");
        }
    }

    /**
     * @return array|int
     * @desc 检测是否登录
     */
    public function checkUserIdIsLogin(){

        $userId = $this->getUserId();

        if(empty($userId)){
            $data = self::callError('登录超时');
            return self::appReturnJson($data,self::CODE_LOGIN_EXPIRE);
        }

        return $userId;

    }

    /**
     * @return array
     * App检测是否登录接口
     */
    public function checkIsLoginApi()
    {

        $result = $this->checkUserIdIsLogin();

        if(!empty($result)){

            return self::callSuccess([]);

        }

    }



}
