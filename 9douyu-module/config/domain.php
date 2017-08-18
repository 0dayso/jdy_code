<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2016/12/13
 * Time: 下午1:46
 */


$domain = env('APP_DOMAIN');

$hostArr =  [
    '.9douyu.com',
    '.jiudouyu.com',
    '.9douyu.com.cn',
    '.jiudouyu.com.cn',
    '.dev.9dy.in',
    '.9dy-dev.sunfund.com',
    '.9dy-test.sunfund.com',
];

foreach ( $hostArr as $host ){

    $serverHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

    if(strpos($serverHost, $host)){

        return $serverHost;

    }

}

return $domain;

