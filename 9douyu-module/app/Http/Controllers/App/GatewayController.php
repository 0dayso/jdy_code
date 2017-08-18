<?php
/**
 * APP控制网关
 *
 */
namespace App\Http\Controllers\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Log;
use Config;

class GatewayController extends  AppController{


    /**
     * 支持的所有客户端
     * @return array
     */
    private function getClients(){

        return ["ios", "android"];
    }


    /**
     * 根据版本号获取对应的加加密串
     */
    private function versign($version = ""){

        $versigns = Config::get('versign');
        //按版本号排序
        $sortVersion     =  $this->sortVersion(array_keys($versigns),'desc');
        $versign = '';
        foreach($sortVersion as $ver){
            if($this->compareVersion($version,$ver)){
                $versign = $versigns[$ver];
                break;
            }
        }

        return $versign;
    }
    /**
     * 服务端uuId，token验证，参数验证
     * 接收POST请求
     * 请求链接:/app/gateway
     */

    /**
     * @SWG\Post(
     *   path="/app/gateway",
     *   summary="App请求入口  [GatewayController@index]",
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
     *     @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="string",
     *      default="861BF082-32A3-403F-BAB4-58FB9EF3B2F8",
     *   ),
     *    @SWG\Parameter(
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1468219647-246153-07272287-68c86291219255dd1d73a782f44fda54",
     *   ),
     *   @SWG\Parameter(
     *      name="request",
     *      in="formData",
     *      description="请求路由",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="App端请求成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="App端请求成功.",
     *   )
     * )
     */
    public function index(Request $requestObj) {

        $client         = strtolower($requestObj->input("client",''));
        $version        = $requestObj->input("version","");
        $request        = trim($requestObj->input("request",""));
        $uuid           = $requestObj->input("uuid","");
        $unique         = $requestObj->input("unique","");         //用于验证是否重复提交的唯一码
        $isCheckSubmit  = $requestObj->input('isCheckSubmit',1);   //是否做重复提交的验证,网页测试的时候不做验证

        $data           = $requestObj->all();

        Log::info("App Request:",$data);

        $this->checkVersion($version,$client);  //将1.0.X版本加个请升级信息

        //根据版本号获取对应的加密KEY
        $versign = $this->versign($version);

        $env = App::environment();

        //线上环境才做重复提交验证
        if($env === 'production'){
            //检查请求是否重复提交
            $this->checkSubmit($version,$unique,$versign,$isCheckSubmit);
        }

        //获取路由配置
        $route = Config::get('route');

        if(!in_array($client, $this->getClients()) || !array_key_exists($request, $route) || empty($version) || empty($uuid)){
            return $this->errorRequest("请求参数错误");
        }

        $routeConf   = $route[$request];
        $parse       = explode("@", $routeConf);
        $actionClass = '\App\Http\Controllers\App\\'.$parse[0];
        $method      = $parse[1];

        if(!class_exists($actionClass)){
            return $this->errorRequest("请求接口不存在");
        }

        try {
            $instance = new $actionClass($requestObj);
            call_user_func_array(array($instance, "setClient"), array($client));
            call_user_func_array(array($instance, "setVersion"), array($version));
            call_user_func_array(array($instance, $method), func_get_args());

            //Log::info("App Request Success:",$data);

        } catch (\Exception $e) {

            $data['error_msg']  = $e->getMessage();
            $data['error_code'] = $e->getCode();
            $data['error_line'] = $e->getLine();
            $data['error_file'] = $e->getFile();

            Log::error("App Request Error:",$data);

            $returnData = self::callError($e->getMessage());

            return self::appReturnJson($returnData);
        }

    }


}