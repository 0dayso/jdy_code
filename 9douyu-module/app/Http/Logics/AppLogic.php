<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/2/17
 * Time: 下午6:43
 */

namespace App\Http\Logics;


class AppLogic extends BaseLogic
{

    const
        CODE_DINGO_API_ERROR          = 1000,  // dingo api报错 生产环境不应该报此错误
        CODE_SUCCESS                  = 2000,  // 成功
        CODE_PHONE_EXIST              = 2001,  // 手机号存在
        CODE_PHONE_NOT_EXIST          = 2002,  // 手机号不存在

        CODE_ERROR                    = 4000,  // 未知错误
        CODE_SIGNATURE                = 4001,  // 签名错误
        CODE_RESUBMIT                 = 4002,  // 重复提交
        CODE_MISSING_PARAMETERS       = 4003,  // 缺少参数
        CODE_PHONE_FORMAT             = 4004,  // 手机号格式不正确

        //理财列表
        CODE_NO_MORE_ASSIGN_LIST      = 4005,  //没有更多债转信息

        //活期收益详情
        CODE_NO_USER_ID               = 4006,  //用户未登录
        CODE_NO_REGISTER              = 4007,  //手机号未注册
        END                           = true;


    /**
     * 返回数据
     *
     * @param array $data
     * @param int $code
     * @param array $parameter
     * @return array
     */
    public static function callSuccess($data=[], $code = self::CODE_SUCCESS, $parameter = null){

        if($parameter!== null && is_string($parameter)) {
            $msg = $parameter;
        }else{
            if(is_array($parameter)) {
                $msg = trans('api.CODE_' . $code, $parameter);
            }else{
                $msg = trans('api.CODE_' . $code);
            }
        }

        return [
            'code'      => $code,
            'msg'       => $msg,
            'data'      => $data
        ];
    }

    /**
     * 返回错误数据
     *
     * @param array $data
     * @param int $code
     * @param array $parameter
     * @return array
     */
    public static function callError($code = self::CODE_ERROR, $parameter = null, $data=[]){

        if($parameter!== null && is_string($parameter)) {
            $msg = $parameter;
        }else{
            if(is_array($parameter)) {
                $msg = trans('api.CODE_' . $code, $parameter);
            }else{
                $msg = trans('api.CODE_' . $code);
            }
        }

        return [
            'code'      => $code,
            'msg'       => $msg,
            'data'      => $data
        ];
    }

}
