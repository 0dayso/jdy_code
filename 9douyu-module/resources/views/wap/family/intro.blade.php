<!DOCTYPE html>
<html lang="zh-cn" class="no-js">
<head>
    <meta http-equiv="Content-Type">
    <meta content="text/html; charset=utf-8">
    <meta charset="utf-8">
    <title>什么是家庭账户?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1,target-densitydpi=medium-dpi">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <script>
        var readyRE = /complete|loaded|interactive/;
        var ready = window.ready = function (callback) {
            if (readyRE.test(document.readyState) && document.body) callback()
            else document.addEventListener('DOMContentLoaded', function () {
                callback()
            }, false)
        }
        //rem方法
        function ready_rem() {
            var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
            var _html = document.getElementsByTagName('html')[0];


            if (view_width > 640) {
                _html.style.fontSize = 640 / 16 + 'px'
            } else if(screen.height>500){
                _html.style.fontSize = view_width / 16 + 'px';
                if(screen.height==1280&&screen.width==800){
                    _html.style.fontSize = view_width / 21 + 'px';
                }
            }else{

                _html.style.fontSize = 16 + 'px'
            }

        }
        ready(function () {
            ready_rem();
        });

    </script>
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyintro.css') }}"  id="sc" />
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/animations.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/animate.min.css') }}" />
</head>
<body>

<div class="page page-1-1 page-current">
    <div class="wrap">
        <div class="family-intro-top animated fadeInLeft">
            <span class="one"><img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img03.png') }}" alt=""></span>
            <p>什么是家庭账户？</p>
        </div>
        <div class="family-box fadeInRight animated">
            <p>你的家人同意授权你，用家庭成员的身份为家人创建或关联九斗鱼账户，帮助家庭成员进行投资</p>
        </div>
        <img class="img_6 pt-page-moveIconUp" src="{{ assetUrlByCdn('/static/weixin/images/topic/family-up.png') }}" />

    </div>
</div>
<div class="page page-2-1 hide">
    <div class="wrap">

        <div class="family-intro-top  animated fadeInLeft">
            <span class="two"><img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img04.png') }}" alt=""></span>
            <p>我们需要做什么？</p>
        </div>
        <div class="family-box fadeInRight animated">
            <dl>
                <dt><i></i>绑定对方手机号</dt>
                <dd>选择对方角色，输入对方手机号及对方收到的授权短信验证码</dd>
                <dt><i></i>验证对方身份</dt>
                <dd>输入对方姓名、身份证号、银行卡号进行验证，用于日后进行充值、提现、购买产品</dd>
                <dt><i></i>投资</dt>
                <dd>授权成功后，选择进入已成功授权的亲友账户，就可以帮助亲友购买产品，坐等回款了</dd>
            </dl>
        </div>
        <img class="img_6 pt-page-moveIconUp" src="{{ assetUrlByCdn('/static/weixin/images/topic/family-up.png') }}" />
    </div>
</div>

<div class="page page-3-1 hide">
    <div class="wrap">

        <div class="family-intro-top animated fadeInLeft">
            <span class="three"><img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img05.png') }}" alt=""></span>
            <p>亲友需要做什么？</p>
        </div>
        <div class="family-box fadeInRight animated">
            <dl>
                <dt><i></i>授权短信验证码</dt>
                <dd>在您绑定亲友手机号时，需要对方告知您授权短信验证码（仅需一次）</dd>
                <dt><i></i>充值短信验证码</dt>
                <dd>在您操作亲友账户充值时，需要对方告知您充值短信验证码（每次充值时需要，确保资金安全）</dd>

            </dl>
        </div>
        <img class="img_6 pt-page-moveIconUp" src="{{ assetUrlByCdn('/static/weixin/images/topic/family-up.png') }}" />
    </div>
</div>

<div class="page page-4-1 hide">
    <div class="wrap">
        <div class="family-intro-top  animated fadeInLeft">
            <span class="four"><img src="{{ assetUrlByCdn('/static/weixin/images/topic/family-img06.png') }}" alt=""></span>
            <p>为什么安全？</p>
        </div>
        <div class="family-box txt fadeInRight animated">
            <p><i></i>您的账户资金和亲友账户资金完全独立分开，充值、投资、提现、回款互不干扰</p>
            <p><i></i>账户资金同卡进出，亲友的出借资金提现后原路返回其充值时的银行卡</p>
            <p><i></i>家庭账户资金，同样享受银行级安全保障</p>
        </div>
        <img class="img_6 pt-page-moveIconUp" src="{{ assetUrlByCdn('/static/weixin/images/topic/family-up.png') }}" />

    </div>
</div>

<script src="{{ assetUrlByCdn('/static/weixin/js/zepto.min.js') }}"></script>
<script src="{{ assetUrlByCdn('/static/weixin/js/touch.js') }}"></script>
<script src="{{ assetUrlByCdn('/static/weixin/js/familyintro.js') }}"></script>
</body>
</html>

