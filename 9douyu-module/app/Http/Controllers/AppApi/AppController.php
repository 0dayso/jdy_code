<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller as BaseController;

use App\Http\Logics\AppLogic;
use Illuminate\Http\Request;

use Log, Cache, Redirect;


/**
 * app4.0+ 接口控制器基础类
 *
 * Class AppController
 * @package App\Http\Controllers\AppApi
 */
class AppController extends BaseController
{
    const


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
     * @param $result
     * @return array
     * 数据返回
     */
    public function returnJsonData($result){

        if($result['status']){

            return AppLogic::callSuccess($result['data']);

        }

        return AppLogic::callError($result['code']==500?AppLogic::CODE_ERROR:$result['code'], $result['msg']);

    }


}
