<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/2
 * Time: 下午8:38
 * Desc: 提示信息/名称
 */

return [

    'PROJECT'   => [

        //还款方式
        'REFUND_TYPE_10'    => '到期还本息',
        'REFUND_TYPE_20'    => '先息后本',
        'REFUND_TYPE_30'    => '投资当日付息，到期还本',
        'REFUND_TYPE_40'    => '等额本息',

        //状态
        'STATUS_100'        => '未审核',
        'STATUS_110'        => '未通过',
        'STATUS_120'        => '未发布',
        'STATUS_130'        => '投资中',
        'STATUS_140'        => '未开始',
        'STATUS_150'        => '还款中',
        'STATUS_160'        => '已完结',

        //产品线
        'PRODUCT_LINE_100'  => '九省心',
        'PRODUCT_LINE_200'  => '九安心',
        'PRODUCT_LINE_300'  => '闪电付息',

        //项目期限
        'TYPE_0'            => '天',
        'TYPE_1'            => '天',
        'TYPE_3'            => '月期',
        'TYPE_6'            => '月期',
        'TYPE_12'           => '月期',
    ],


    'FUND_HISTORY'  => [
        'EVENT_ID_200'          => '回款',
        'EVENT_ID_300'          => '定期',

        'EVENT_ID_400'          => '零钱计划转入',

        'EVENT_ID_401'          => '零钱计划转出',
        'EVENT_ID_402'          => '回款自动转零钱计划',
        'EVENT_ID_500'          => '债转',
        'EVENT_ID_600'          => '充值',
        'EVENT_ID_700'          => '提现',
        'EVENT_ID_701'          => '提现失败',
        'EVENT_ID_702'          => '取消提现',
        'EVENT_ID_800'          => '活动奖励',
        'EVENT_ID_900'          => '债权转让',
        'EVENT_ID_901'          => '债权承接',
        'EVENT_ID_1000'         => '系统操作',
        'EVENT_ID_1100'         => '新零钱计划转入',
        'EVENT_ID_1101'         => '新零钱计划转出',
    ],

    //订单状态
    'ORDER_STATUS' => [

        'STATUS_200'            => '成功',
        'STATUS_300'            => '待处理',
        'STATUS_301'            => '处理中',
        'STATUS_401'            => '超时',
        'STATUS_402'            => '取消提现',
        'STATUS_500'            => '失败'
    ],

    'WITHDRAWRECORD_STATUS' => [

        'STATUS_0'          => '未处理',
        'STATUS_1'          => '已处理'
    ],

    //短信相关
    'SMS_MESSAGE' => [
        //回款自动进零钱计划
        'MESSAGE_REFUND_AUTO_CURRENT_NOTICE'    => '【九斗鱼】亲爱的%s，您今日有%s个项目回款，回款总额%s元，已自动转入零钱计划%s元，让您天天享收益，请留意账户资金变动。客服4006686568',
        'BEFORE_REFUND_NOTICE'                  => '【九斗鱼】亲爱的%s，您投资的%s号项目已提前回款，回款总额%s元，建议您将回款再投资，提高收益。客服4006686568'
    ],


    'MESSAGE_CREDIT_ASSIGN_SELLER'   => '【九斗鱼】恭喜，您申请转让的%s项目已完成转让，本次成功转让本金%s元，请注意查看账户。客服4006686568',
    'MESSAGE_CREDIT_ASSIGN_BUYER'    => '【九斗鱼】恭喜，您已成功购买债权转让项目%s，本次投资金额%s元，购买本金%s元，首次回款日为%s，总收益为%s元。客服：4006686568',



];