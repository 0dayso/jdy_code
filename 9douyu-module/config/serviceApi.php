<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/16
 * Time: 下午4:15
 */

return [

    //短信相关接口
    'moduleSms'=>[
        'verify'            => '/verify',   // 验证码类短信
        'notice'            => '/notice',   // 通知类短信
        'market'            => '/market',   // 营销类
        'voice'             => '/voice',    // 语音
        'blackList'         => '/blackList', //获取短信黑名单词组
    ],



    //事件
    'moduleEmail'       => [
        'sendMail'          => '/sendMail',  //发送内容为字符型的邮件
        'sendMailHtml'          => '/sendMailHtml' //发送内容带HTML格式的邮件

    ],

    //银行卡

    'moduleBankCard'    => [

        //连连卡bin接口,根据银行卡号获取对应的信息
        'getCardInfo'           => '/getCardInfo',

        //融宝信用卡鉴权接口
        'checkCreditCard'       => '/checkCreditCard',

        //融宝储蓄卡鉴权接口
        'checkDepositCard'      => '/checkDepositCard',
    ],


    //服务 @todo 请写一些备注
    'partner' => [
        'partner_id'       => '110000901001',
        'secret_sign'      => 'b926ab99d913f7bacbb3e526ebf75c98'
    ],

    //系统配置
    'moduleConfig' => [
        'getConfigList'  => '/SystemConfig/list', //配置列表
        'addSystemConfig' => '/SystemConfig/addConfig', //添加配置
        'editSystemConfig' => '/SystemConfig/editConfig', //编辑配置
        'getConfigById' => '/SystemConfig/getConfigById', //通过id获取配置信息

    ],


];
