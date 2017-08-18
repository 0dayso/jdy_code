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
            该期九省心项目是信贷类项目，是耀盛中国旗下耀盛信贷针对全国各地的中小企业提供的快速融资贷款的业务，所有的项目均经过RISKCALC风控技术评审，实地勘察。
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
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-3"></span>抵押房产信息</dt>
        <dd class="wap2-project-source">
            <dl>
                <dt>房产位置:</dt>
                <dd>
                    <?php
                        echo isset($creditDetail['companyView']['residence']) ? htmlspecialchars_decode($creditDetail['companyView']['residence']) : null;
                    ?>
                    </dd>
            </dl>
            <dl>
                <dt>房产面积:</dt>
                <dd>
                    <?php
                    echo isset($creditDetail['companyView']['housing_area']) ? htmlspecialchars_decode($creditDetail['companyView']['housing_area']) : null;
                    ?>
                    平米</dd>
            </dl>
    </dl>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-4"></span>借款人信息</dt>
        <dd class="wap2-project-source">
            <dl>
                <dt>性别:</dt>
                <dd>
                    <?php echo (!empty($company['sex']) && $company['sex'] == 1) ? '男' : '女'; ?>
                </dd>
            </dl>
            <dl>
                <dt>年龄:</dt>
                <dd><?php
                    echo isset($company['age']) ? $company['age'] : null;
                    ?></dd>
            </dl>
            <dl>
                <dt>户籍所在地:</dt>
                <dd><?php
                    echo isset($company['family_register']) ? $company['family_register'] : null;
                    ?>
                    </dd>
            </dl>
            <dl>
                <dt>居住地:</dt>
                <dd>    <?php
                    echo isset($company['residence']) ? $company['residence'] : null;
                    ?>
                    </dd>
            </dl>
            <dl>
                <dt>借款人征信记录:</dt>
                <dd>
                    <?php
                    echo isset($company['credibility']) ? $company['credibility'] : null;
                    ?>
                        </dd>
            </dl>
    </dl>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-5"></span>到期后如何赎回？</dt>
        <dd class="wap2-project-list-dd">
            本金和利息会自动存入您的九斗鱼账户，申请提现即可转入您绑定的银行卡中。

        </dd>
    </dl>
    <?php if(!empty($company['homeloan_images_links'])) { ?>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-6"></span>房产抵押资料</dt>
        <dd class="wap2-project-list-dd">
            @foreach ( $company["homeloan_images_links"] as $image )
                <img src="<?php echo $image['thumb'][$view_ssl];?>">
            @endforeach
        </dd>
    </dl>
    <?php } ?>
    <?php if(!empty($company['identity_images_links'])) { ?>
    <dl class="wap2-project-list">
        <dt class="wap2-project-list-dt"><span class="wap2-icon wap2-icon-6"></span>借款人证件</dt>
        <dd class="wap2-project-list-dd">
            @foreach ( $company["identity_images_links"] as $image )
                <img src="<?php echo $image['thumb'][$view_ssl];?>">
            @endforeach
        </dd>
    </dl>
    <?php } ?>
</article>
</body>
</html>