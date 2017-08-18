<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/20
 * Time: 下午4:50
 * Desc: 借款体系接口的配置
 */

return [
    
    //借款系统通信签名配置
    'ApiSign'  => [
        'api_auth_key' =>'qLtpWgJh54cWg4ysn6kA1ajzhFNdUHkL',
    ],

    //债权借款人账户录入
    'LoanUserCreditApi' => [
        'sendLoanUserCreditData'    => '/api/batchCreateUserAndCredit',
        'doPublishCredit'           => '/api/doPublishCredit',
        'doRefundNotice'            => '/api/doRefundNotice',
        'makeLoans'                 => '/api/makeLoans',
    ],
];

