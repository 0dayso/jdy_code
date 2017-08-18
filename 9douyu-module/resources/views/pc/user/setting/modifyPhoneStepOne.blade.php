<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Laravel 5 pc user setting modifyPhone
            <form id="modifyPhone" action="/user/setting/phone/doVerifyTransactionPassword" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <dl class="t-accout-1">
                    <dt class="t-lh36px">请输入交易密码</dt>
                    <dd><input type="password" class="form-input t-a-1" autocomplete="off" id="tradingpassword" name="password">
                </dl>
                <p id="tipMsg" class="t-reg-a1 tc t-mt-10px"></p>

                <p class="tc t-pb100px"><input type="submit" value="确定" class="btn btn-blue btn-large t-w236px">
                </p>

        </div>
    </div>
</div>
</body>
</html>