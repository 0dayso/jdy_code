@extends('pc.common.layout')

@section('header')
    <div class="wrap">
        <div class="login-header">
            <a href="/"><img src="{{assetUrlByCdn('/static/images/new/logo-login-replace.png')}}" width="144" height="80"></a><span>登录</span>
        </div>
    </div>
@endsection

@section('title', '找回密码 - 验证身份')

@section('content')
<block name="main">
    <div class="t-wrap t-mt30px">
        <div class="t-account1">
            <h3 class="t-accout-title"><span></span>找回密码</h3>
            <div class="t-account-step">
                <div class="t-account-line"></div>
                <dl class="t-account-step1">
                    <dt>1</dt>
                    <dd>验证身份</dd>
                </dl>
                <dl class="t-account-step2">
                    <dt class="t-mn">2</dt>
                    <dd class="t-blue">设置密码</dd>
                </dl>
                <dl class="t-account-step3">
                    <dt>3</dt>
                    <dd>完成</dd>
                </dl>
            </div>
            <form method="post" action="/doResetLoginPassword" id="resetPasswordForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="phone" value="{{$phone}}">
                <input type="hidden" name="code" value="{{$code}}">
                <dl class="t-accout-2">
                    <dt class="t-lh36px">新登录密码</dt>
                    <dd><input type="password" class="form-input t-a-1" name="password" id="password" placeholder="6到16位的字母及数字组合"/></dd>
                </dl>
                <dl class="t-accout-2">
                    <dt class="t-lh36px">确认登录密码</dt>
                    <dd><input type="password" class="form-input t-a-1" name="password2" id="password2" placeholder="6到16位的字母及数字组合"/></dd>
                </dl>
                <p class="t-reg-a1 tc t-mt-10px" id="tipMsg">
                    @if(Session::has('errorMsg'))
                        {{ Session::get('errorMsg') }}
                    @endif
                </p>
                <p class="tc t-pb100px"><input type="submit" class="btn btn-blue btn-large t-w236px" value="确定"/>
                </p>
            </form>
        </div>
    </div>
</block>
@endsection

@section('jspage')

   <script src="{{ assetUrlByCdn('static/js/pc2/formCheck.js') }} "></script>
   <script src="{{ assetUrlByCdn('static/js/pc2/sendCode.js') }} "></script>
   <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                //验证密码
                $.resetPwd={
                    tipHidden:function(){
                        $("#tipMsg").hide();
                    },

                    tip:function(msg){
                        $("#tipMsg").html(msg).show();
                    },
                    checkPassword:function(){
                        var password = $.trim($("input[name=password]").val());
                        var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
                        if(password == '') {
                            this.tip('请输入密码');
                            this.borderColor('password',1);
                            return false;
                        }
                        if(!password.match(pattern)){
                            this.tip('6到16位的字母及数字组合');
                            this.borderColor('password',1);
                            return false;
                        }else {
                            this.tipHidden();
                            this.borderColor('password',2);
                            return true;
                        }

                    },
                    //验证确认密码
                    checkPwdConfirms:function(){
                        var password = $.trim($("input[name=password]").val());
                        var password2 = $.trim($("input[name=password2]").val());

                        if( password2 == '') {
                            this.tip('请输入确认密码');
                            this.borderColor('password2',1);
                            return false;
                        }

                        if($.trim($("input[name=password]").val()) != $.trim($("input[name=password2]").val())){
                            this.tip('两次密码输入不一致');
                            this.borderColor('password',1);
                            this.borderColor('password2',1);
                            return false;
                        }else {
                            this.tipHidden();
                            this.borderColor('password',2);
                            this.borderColor('password2',2);
                            return true;
                        }


                    },
                    //提交
                    checkSubmit:function(){

                        if( this.checkPassword() && this.checkPwdConfirms())
                        {
                            return true;
                        }
                        return false;

                    },

                    borderColor:function(idEle, type){
                        if(type == 1) {
                            $("#" + idEle).css('border-color', '#ff7200');
                        }else{
                            $("#" + idEle).css('border-color', '#cccccc');
                        }
                    },
                }

                //离开焦点
                $("#password, #password2").blur(function(){

                    $.resetPwd.checkSubmit();

                });

                //提交
                $("#resetPasswordForm").submit(function() {

                    if($.resetPwd.checkSubmit() == false){
                        return false;
                    }

                });

            });
        })(jQuery);
    </script>
@endsection
