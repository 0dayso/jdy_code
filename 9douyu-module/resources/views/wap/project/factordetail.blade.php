<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>九斗鱼，安全投资平台</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link href="{{assetUrlByCdn('/static/images/favicon.ico')}}" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <link rel="stylesheet" href="{{assetUrlByCdn('/static/weixin/css/public.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{assetUrlByCdn('/static/weixin/css/wap2.css')}}" type="text/css"/>

</head>
<body>
<?php
$company = isset($creditDetail['companyView']) ? $creditDetail['companyView'] : null;
?>

<article>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-1"></span>{{ $project["name"] }}</dt>
        <dd class="wap2-project-list-dd">

        </dd>
    </dl>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-2"></span>项目描述</dt>
        <dd class="wap2-project-list-dd">
            <?php
                echo isset($creditDetail['companyView']['credit_desc']) ? htmlspecialchars_decode($creditDetail['companyView']['credit_desc']) : null;
            ?>
        </dd>
    </dl>

    <!--<dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-3"></span>风险控制</dt>
        <dd class="wap2-project-source">
            <?php
                echo isset($creditDetail['companyView']['repayment_source']) ? htmlspecialchars_decode($creditDetail['companyView']['repayment_source']) : null;
            ?>
        </dd>
    </dl>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-4"></span>资金安全</dt>
        <dd class="wap2-project-secure">
            <dl>
                <dt>1.</dt>
                <dd>九斗鱼记录出借人的每笔投资，并生成符合法律法规的有效合同文件，且所有的
资金流向均由独立第三方机构代为管理，以确保用户资金安全；</dd>
            </dl>
            <dl>
                <dt>2.</dt>
                <dd>九斗鱼平台的所有投资项目均通过多重风险控制审核，并对投资项目进行全面风
险管理，以最大程度保障出借人的资金安全；</dd>
            </dl>
            <dl>
                <dt>3.</dt>
                <dd>九斗鱼平台全程采用 VeriSign256 位 SSL 强制加密证书进行数据加密传输，有效
保障银行账号、交易密码等机密信息在网络传输过程中不被查看、修改或窃取；</dd>
            </dl>
            <dl>
                <dt>4.</dt>
                <dd>平台所有的投资项目均交纳 1%作为风险准备金，由东亚银行监管；</dd>
            </dl>

        </dd>
    </dl>-->
    
    <?php if(!empty($company['trade_info_links'])) { ?>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-6"></span>企业实勘</dt>
        <dd class="wap2-project-list-dd">
            @foreach ( $company["trade_info_links"] as $image )
                <img src="<?php echo $image['thumb'][$view_ssl];?>">
            @endforeach
        </dd>
    </dl>
    <?php } ?>
    <?php if(!empty($company['factor_info_links'])) { ?>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon  wap2-icon-6"></span>保理合同</dt>
        <dd class="wap2-project-list-dd">
            @foreach ( $company["factor_info_links"] as $image )
                <img src="<?php echo $image['thumb'][$view_ssl];?>">
            @endforeach
        </dd>
    </dl>
    <?php } ?>
</article>
</body>
</html>