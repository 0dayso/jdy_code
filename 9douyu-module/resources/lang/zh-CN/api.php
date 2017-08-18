<?php

use App\Http\Logics\AppLogic;

return [

    'CODE_' . AppLogic::CODE_SUCCESS             => '成功',
    'CODE_' . AppLogic::CODE_ERROR               => '未知错误',
    'CODE_' . AppLogic::CODE_SIGNATURE           => '签名错误',
    'CODE_' . AppLogic::CODE_RESUBMIT            => '重复提交',
    'CODE_' . AppLogic::CODE_MISSING_PARAMETERS  => '缺少参数',
    'CODE_' . AppLogic::CODE_NO_MORE_ASSIGN_LIST  => '没有更多的债转信息',
    'CODE_' . AppLogic::CODE_NO_USER_ID          => '用户未登录',
];
