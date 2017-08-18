<?php

namespace App\Http\Middleware;

use App\Http\Logics\AppLogic;
use App\Http\Logics\RequestSourceLogic;
use Closure;

/**
 * app4.0+ 接口安全中间件
 *
 * Class AppApiAuth
 * @package App\Http\Middleware
 */
class AppApiAuth
{

    /**
     * 支持的所有客户端
     * @return array
     */
    private static function allowClient(){
        return [RequestSourceLogic::SOURCE_IOS, RequestSourceLogic::SOURCE_ANDROID];
    }

    /**
     * @return string
     * 重复提交签名
     */
    private static function getUniqueKey(){
        return 'LECHz3Ripo9e';
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $env = \App::environment();

        if($env === 'production'){

            $client         = strtolower($request->input("client",''));
            $version        = $request->input("version","");
            $uuid           = $request->input("uuid","");
            $unique         = $request->input("unique","");

            # 接口必传参数非空验证
            if(empty($client) || !in_array($client, $this->allowClient()) || empty($version) || empty($uuid) || empty($unique)){
                return AppApiResponseFormat::formatData(AppLogic::callError(AppLogic::CODE_MISSING_PARAMETERS));
            }

            # 简单签名算法验证
            $parameter      = explode('-', $unique);
            $sign           = array_pop($parameter);
            $str            = '';
            foreach($parameter as $value){
                $str       .= $value;
            }

            $str .= self::getUniqueKey();
            $currentSign    = md5($str);
            if($sign != $currentSign){
                return AppApiResponseFormat::formatData(AppLogic::callError(AppLogic::CODE_SIGNATURE));
            }

            // 重复提交验证
            $cacheKey       = md5($unique);
            $status         = \Cache::get($cacheKey);
            if($status){
                return AppApiResponseFormat::formatData(AppLogic::callError(AppLogic::CODE_RESUBMIT));
            }
            \Cache::put($cacheKey,1,60);
        }


        \Log::info('app4.0+ auth', ['path'=> $request->path(), 'data'=> $request->all()]);

        return $next($request);
    }
}
