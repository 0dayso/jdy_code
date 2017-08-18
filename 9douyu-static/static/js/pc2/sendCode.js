(function($){
$.extend({
    bindSendCode : function(options) {
        options = options || {type: 'common', autoPhone: false}
         
        var timeout = options.timeout || 0, maxTimeout = options.maxTimeout || 60, timer;
        var desc    = options.desc || "秒后重发", sendRes;
        options.autoPhone = options.autoPhone || null;
        options.captcha = options.captcha || false;  //验证码
        $("#code").attr("disabled", null);          //删除可能存在的disabled属性
        options.url = options.url || '/utility/phone_code/sendCode';
        
        //跳秒初始化
        timer = setInterval(function() {
            timeout--;

            if(timeout > 0) {
                $("#code").addClass("disable").val(timeout + desc);
            } else {
                $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                clearInterval(timer);
            }            

        }, 1000);
        
        $("#code").click(function(){
            if(timeout > 0) return false;
            
            clearInterval(timer);    //清除跳秒
            
            var captcha = '';
            if(options.captcha){
                captcha = $('input[name="captcha"]').val();
            }
            
            if(options.callback){
                if(!eval(options.callback).apply(this)) return false;
            }

            //联动优势验卡
            var go = 1;
            if($("input[name=is_ump]").val()==1){
                if(!$.phone_check($("#phone"))) {
                    $('.form-tips').addClass('text-error');
                    $('.form-tips').text('手机号有误');//data.ret_msg
                    return false;
                }
                $.ajax({
                    url : '/user/recharge/checkBankCard',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    data: {'bank_account': $("input[name=card_no]").val(), 'account_name': ''+$("input[name=account_name]").val()+'','identity_code':$("input[name=identity_card]").val(), 'phone':$("input[name=phone]").val()},
                    success : function(data) {
                        if(data.ret_code!='0000') {
                            $('.form-tips').addClass('text-error');
                            $('.form-tips').text('银行卡信息验证失败');//data.ret_msg
                            go = -1;
                        }else{
                            $('.form-tips').removeClass('text-error');
                            $('.form-tips').text('');
                        }
                    }
                });
                if(go==-1) return false;
            }


            var phone = '';
            if($("#phone").size()) {
                if($("#phone").hasClass('wrong')) return false;  //手机号码有误，禁止提交
                phone = $.trim($("#phone").val());
                if(!$.phone_check($("#phone"))) {
                    return false;
                }
            }


            $.ajax({
                url : options.url,
                type: 'POST',
                dataType: 'json',
                data: {'phone': phone, type: options.type, autoPhone: options.autoPhone,callback:options.callback,captcha:captcha},
                success : function(result) {
                    sendRes = result;
                    if(sendRes.captcha === false && options.captcha) {
                        	$("#captcha").click();
                    } 
                    if(sendRes.status) {
                        if(timeout <= 0) {
                            timeout = maxTimeout;
                            $("#code").addClass("disable").val(timeout + desc).attr("disabled", true);
                        }
                        timer = setInterval(function() {
                            timeout--;

                            if(timeout > 0) {
                                $("#code").addClass("disable").val(timeout + desc);
                            } else {
                                $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                                clearInterval(timer);
                            }            

                        }, 1000);

                    } else {
                        if(options.autoPhone && sendRes.login === false) {
                            location.href = '/user/login';
                        } 
                        //$("#code").addClass("error").val(sendRes.msg);
                        $("#tipMsg").text(sendRes.msg);

                    }
                },
                error : function(msg) {
                    $("#code").attr("disabled", null);
                    $("#tipMsg").text("服务器端错误，请点击重新获取");
                    clearInterval(timer);
                }
            });
        });
    }
});
})(jQuery);