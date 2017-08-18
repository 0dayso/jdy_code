(function($) {
    $(document).ready(function() {
        /**
         * 注册验证方法
         * @type {{
         *      tipHidden: jQuery.register.tipHidden,
         *      tip: jQuery.register.tip,
         *      checkPhone: jQuery.register.checkPhone,
         *      checkPassword: jQuery.register.checkPassword,
         *      checkPwdConfirms: jQuery.register.checkPwdConfirms,
         *      checkPhoneCode: jQuery.register.checkPhoneCode,
         *      checkSubmit: jQuery.register.checkSubmit,
         *      borderColor: jQuery.register.borderColor
         *      }}
         */
        $.register = {

            tipHidden:function(){
                $("#system-message").hide();
            },

            tip:function(msg){
                $("#system-message").html(msg).show();
            },
            //验证手机号
            checkPhone:function(){

                var phone = $.trim($("input[name=phone]").val());
                var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
                if(phone == '') {
                    this.tip('请输入手机号码');
                    this.borderColor('phone',1);
                    return false;
                }
                if(!phone.match(pattern)) {
                    this.tip('请输入正确的手机号码');
                    this.borderColor('phone',1);
                    return false;
                }else{
                    this.tipHidden();
                    this.borderColor('phone',2)
                    return true;
                }

            },
            //验证密码
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
            //验证校验码
            checkCaptchaCode:function(){

                var captchaCode = $.trim($("input[name=captcha]").val());

                if( captchaCode == '' ) {
                    this.tip('请输入校验码');
                    this.borderColor('captchaCode',1);
                    return false;
                }else{
                    this.tipHidden();
                    this.borderColor('captchaCode',2);
                }
                return true;
            },
            //验证验证码
            checkPhoneCode:function(){

                var phoneCode = $.trim($("input[name=phone_code]").val());

                if( phoneCode == '' ) {
                    this.tip('请输入验证码');
                    this.borderColor('phoneCode',1);
                    return false;
                }else{
                    this.tipHidden();
                    this.borderColor('phoneCode',2);
                }
                return true;
            },

            //邀请手机号不可与注册手机号一致
            checkInvitePhone:function(){

                var invitePhone = $.trim($("input[name=invite_phone]").val());
                var phone       = $.trim($("input[name=phone]").val());

                if(invitePhone != ''){
                    var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;

                    if(!invitePhone.match(pattern)) {
                        this.tip('请输入正确的邀请手机号码');
                        this.borderColor('phone',1);
                        return false;
                    }else if( invitePhone == phone){
                        this.tip('邀请手机号不可与注册手机号一致');
                        this.borderColor('phone',1);
                        return false;
                    }

                }

                this.tipHidden();
                this.borderColor('phone',2);
                return true;

            },

            //提交
            checkSubmit:function(){

                if( this.checkPhone() && this.checkPassword() && this.checkPwdConfirms() && this.checkCaptchaCode() && this.checkPhoneCode() && this.checkInvitePhone() )
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


        };

        //离开焦点
        $("#phone, #password, #password2, #phoneCode, #captchaCode, #invitePhone").blur(function(){

            $.register.checkSubmit();

        });

        //提交
        $("#registerForm").submit(function() {

            if($.register.checkSubmit() == false){
                return false;
            }

        });

        //验证码
        $("#arriveRegisterForm").submit(function() {
            var textArr = {
                'username': '用户名不能为空',
                'password': '密码不能为空',
                'phone':'手机号不能为空'
            }

            var failFlag = false;
            $("#arriveRegisterForm").find("input[type=text],input[name=password]").each(function(){
                if($(this).val() == '') {
                    $(this).btnShowTips(textArr[$(this).attr("name")]);
                    failFlag = true;
                    return false;
                }
            });

            if(failFlag) return false;

        });

        /**
         * 显示提示信息函数
         * @params  obj     错误信息提示div,jQuery Object，eg. $("#tips")
         * @params  type    信息类型,eg. success/error
         * @params  msg     提示信息
         */
        /*var btnShowTips = function(obj,type,msg){
            if(type == 'success') $(obj).removeClass('error').addClass('success').html(msg).show();
            else $(obj).removeClass('success').addClass('error').html(msg).show();
        }
        */
        $("#aggreement").click(function() {
            if($(this).is(":checked")) {
                $("#submitBtn").prop("disabled", null).removeClass("disabled");
            } else {
                $("#submitBtn").prop("disabled", true).addClass("disabled");
            }
        });
        $("#registerForm input").each(function(){
            $(this).focus(function(){
                $(this).parent(".btn-box").addClass("focus")
            }).blur(function(){
                $(this).parent(".btn-box").removeClass("focus")
            })
        })
    });
})(jQuery);
