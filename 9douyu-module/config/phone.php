<?php

return [

    'SN'=>'SDK-BBX-010-19299',
    'PSW'           => '2-10f[-[',
    'CODE_BASE'     => '0123456789',
    'CODE_LENGTH'   => 6,
    'TIMEOUT'       => 60,
    'SECURE_KEY'    => '3MifqYS6_57rZWCKF',  //发送手机短信安全码
    'FREQUENT_TIMES'=> 3, //频繁发送次数限制
    'FREQUENT_EXPIRE_SECOND'=>300,  //5*60 5分钟
    //亿美软通(之前的)
    'EMAY_USERNAME' => '6SDK-EMY-6688-KGXNO',               //普通账户 channel 2
    'EMAY_PASSWORD' => '735766',
    'EMAY_USERNAME_MARKETING'   => '6SDK-EMY-6666-REZSM',   //营销账户 channel 1
    'EMAY_PASSWORD_MARKETING'   => '094945',
    //亿美软通(新版)
    'EMAY_MERGE_NAME' => '9SDK-EMY-0999-RFSNT',               //验证码账户(不能发营销) channel 3
    'EMAY_MERGE_PASSWORD' => '959529',

];