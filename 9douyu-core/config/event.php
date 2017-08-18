<?php

return [
    //事件列表
    'list'  => [
        'App\Events\Api\Invest\ProjectSuccessEvent' => [
            'desc'      => '项目投资成功',
            'params'    => [
                'project_id',
                'invest_cash',
                'profit',
            ],
        ],
        'App\Events\Api\BankCard\BindCardFailedEvent' => [
            'desc'      => '支付回调绑卡失败',
            'params'    => [
                'order_id',
                'user_id',
            ],
        ],
        'App\Events\Api\User\RegisterSuccessEvent' => [
            'desc'      => '用户注册成功',
            'params'    => [
                'phone',
            ],
        ],
        'App\Events\Api\User\DoActivateSuccessEvent' => [
            'desc'      => '用户激活成功',
            'params'    => [
                'user_id',
            ]
        ],
        'App\Events\Api\Order\WithdrawHandleFailedEvent' => [
            'desc'      => '批量对账处理失败',
            'params'    => [
                'failed_order',
            ]
        ],
        'App\Events\Api\Current\RefundAutoInvestListener' => [
            'desc'      => '自动回款进零钱计划',
            'params'    => [
                'auto_invest_list',
            ]
        ]
    ],
];