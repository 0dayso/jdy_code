<?php

namespace App\Http\Middleware;

use App\Http\Logics\AppLogic;
use Closure;

/**
 * 格式化数据
 *
 * Class AppApiResponseFormat
 * @package App\Http\Middleware
 */
class AppApiResponseFormat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if(is_object($response) && method_exists($response,'getOriginalContent')){
            $content = $response->getOriginalContent();
        }else{
            if(is_array($response)){
                $content = $response;
            }else{
                $content = null;
            }
        }
        \Log::info('app4.0+ response original content', [$content]);

        return self::formatData($content);
    }


    /**
     * 格式化 controller 返回数据
     *
     * @param array $data
     * @return array
     */
    public static function formatData($data = []){

        $data["server"] = env('WHICH_SERVER');
        $data["code"]   = (string)$data['code'];

        if(empty($data)){
            \Log::info('app4.0+ response original content is empty!');
            return [
                'code'  => AppLogic::CODE_ERROR,
                'msg'   => trans('api.CODE_' . AppLogic::CODE_ERROR)
            ];
        }else{

            if(isset($data['status']))
                unset($data['status']);

            if(isset($data['data']) && is_array($data['data'])){
                $data['data'] = self::formatDataValue($data['data']);
            }

            if(isset($data['data']) && empty($data['data'])){
                \Log::info('app4.0+ response original content[\'data\'] is empty!');
                unset($data['data']);
            }
        }

        return $data;
    }

    /**
     * 格式化返回的Data数据
     *
     * @param array $data
     * @return array
     *
     */
    public static function formatDataValue( $data ){

        if(is_array($data)){

            foreach($data as $k => $val){

                if(empty($val) && $val !== 0 && $val !== '0'){
                    unset($data[$k]);
                    continue;
                }

                if(empty(self::formatDataValue($val)) && $val !== 0 && $val !== '0'){
                    unset($data[$k]);
                    continue;
                }

                $data[$k] = self::formatDataValue($val);

            }

        }elseif(!empty($data) || $data === '0' || $data === 0){

            $data = (string)$data;

        }

        return $data;

    }
}
