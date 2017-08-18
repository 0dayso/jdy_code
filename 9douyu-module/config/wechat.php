<?php
return [
    /**
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,

    /**
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /**
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
//    'app_id'  => env('WECHAT_APPID', 'wx7d31a8561e5ff81d'),         // AppID
//    'secret'  => env('WECHAT_SECRET', '7a6f7b5117b21d225307b9d4cd7001aa'),     // AppSecret
//    'token'   => env('WECHAT_TOKEN', '9kI3GSayOOgVAsafnLdBpYJ3B5elyYyX'),          // Token
//    'aes_key' => env('WECHAT_AES_KEY', 'dRlV8Pu3izgzWdTPwBvVejOe2qhc2TP62agY4MS82nB'),                    // EncodingAESKey

    'app_id'  => env('WECHAT_APPID', 'wxf2b8313e84f65edf'),         // AppID
    'secret'  => env('WECHAT_SECRET', 'bfd7c72a67af9983830a3323a2d668f5'),     // AppSecret
    'token'   => env('WECHAT_TOKEN', '9kI3GSayOOgVAsafnLdBpYJ3B5elyYyX'),          // Token
    'aes_key' => env('WECHAT_AES_KEY', '6u5lXKVGblZlSVIUvJLjJHI3tqKwI7o4kyA6KgBkWoK'),                    // EncodingAESKey
    /**
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat_' . date('Y-m-d') . '.log')),
    ],

    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
     'oauth' => [
         'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
         'callback' => env('WECHAT_OAUTH_CALLBACK', '/wechat/callback'),
     ],

    /**
     * 微信支付
     */
    // 'payment' => [
    //     'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
    //     'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //     'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
    //     'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
    //     // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
    //     // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
    //     // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
    //     // ...
    // ],

    /**
     * 微信配置
     */
    'jdyWeixin' => [
        'url'=>'http://wx.9douyu.com',
    ],
];
