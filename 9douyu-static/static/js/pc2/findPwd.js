(function($) {
    $(document).ready(function() {

        $.findPwd = {

            tipHidden:function(){
                $("#login_notice_msg").hide();
            },

            tip:function(msg){
                $("#login_notice_msg").html(msg).show();
            },
            //验证手机号
            checkPhone:function(){

                var phone = $.trim($("input[name=phone]").val());
                var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
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

            //验证验证码
            checkPhoneCode:function(){

                var phoneCode = $.trim($("input[name=phoneCode]").val());

                if( phoneCode == '' ) {
                    this.tip('请输入验证码');
                    this.borderColor('phoneCode',1);
                    return false;
                }else if( phoneCode.length != 6){
                    this.tip('验证码必须是六位数字');
                    this.borderColor('phoneCode',1);
                    return false;
                }else{
                    this.tip('');
                    this.borderColor('phoneCode',2);
                }
                return true;
            },

            //提交
            checkSubmit:function(){

                if( this.checkPhone() && this.checkPhoneCode())
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

        //提交
        $("#findPasswordForm").submit(function() {

            if($.findPwd.checkSubmit() == false){
                return false;
            }

        });


        /**
         * 显示提示信息函数
         * @params  obj     错误信息提示div,jQuery Object，eg. $("#tips")
         * @params  type    信息类型,eg. success/error
         * @params  msg     提示信息
         */
        $("#findPasswordForm input").each(function(){
            $(this).focus(function(){
                $(this).parent(".btn-box").addClass("focus")
            }).blur(function(){
                $(this).parent(".btn-box").removeClass("focus")
            })
        })
    });
})(jQuery);
