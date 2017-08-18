<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Laravel 5 pc user setting modifyPhone
            <form id="modifyPhone" action="/user/setting/phone/modify" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <dl class="t-accout-1">
                    <dt class="t-lh36px">图片验证码</dt>
                    <dd><input class="form-input t-a-1" placeholder="验证码" name="captcha" tipmsg="请输入正确的图片验证码" tips="tipMsg" type="text">
                        <span class="t-account-img t-account-img1"><img id="captcha" src="/captcha/pc_update_phone" onclick="this.src=this.src+Math.random()" height="36" width="95"></span>
                    </dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">新手机号</dt>
                    <dd><input name="phone" id="phone" class="form-input t-a-1" tipmsg="请填写真实有效的手机号码" tips="tipMsg" type="text"></dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">手机验证码</dt>
                    <dd><input name="code" class="form-input t-a-1" tipmsg="请输入正确的手机验证码" tips="tipMsg" type="text"><span class="t-account-img"><input value="免费获取验证码" default-value="免费获取验证码" id="sendSms" class="code" type="button"></span>
                        <!--

                        <span class="t-account-img">重新发送</span>
                        <span class="t-account-img t-account-img2">60s后重新发送</span>   -->
                    </dd>
                </dl>

                <p class="t-reg-a1 tc t-mt-10px form-tips" id="tipMsg">修改手机号码-验证交易密码通过</p>

                <input value="<?php echo $token ?>" name="token" type="hidden">
                <p class="tc t-pb100px"><input type="submit" value="确定" class="btn btn-blue btn-large t-w236px">
                </p>
                <script src="/js/jquery-1.12.4.min.js"></script>

                <script type="text/javascript">
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                        }
                    });


                    $("body").on("click", "#sendSms", function(){
                        var phone =$("#phone").val();
                        console.log(phone);
                        $.post("/user/setting/phone/sendSms",{"phone" : phone}, function(data){
                            console.log(data);
                        }, "json");
                    });
                </script>
        </div>
    </div>
</div>
</body>
</html>