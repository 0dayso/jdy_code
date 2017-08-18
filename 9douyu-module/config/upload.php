<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/12
 * Time: 上午10:23
 */
return [

    //图片上传配置
    'PICTURE'           => [
        'PICTURE_SAVE_PATH'    => realpath(base_path('storage/resources')) . '/',        // 存储路径
        'MAX_SIZE'             => 2*1024*1024,                                           // 最大限制 2M
        'MAX_SIZE_DESC'        => '2MB',                                                 // 最大限制 2M 描述
        'TYPE'                 => ['gif', 'jpg', 'png', 'bmp'],                          // 上传类型

        'PICTURE_WEB_URL'      => env('MODULE_URL'),                                     // 图片服务器地址
        'PICTURE_WEB_URL_HTTPS'=> env('MODULE_URL_HTTPS'),                               // 图片服务器地址HTTPS
        'ERROR_PICTURE'        => assetUrlByCdn('/static/images/error-new.png'),        // 错误图片展示
        'NO_EXISTS_PICTURE'    => assetUrlByCdn('/static/images/no_exists.jpg'),        // 图片不存在
    ],


];