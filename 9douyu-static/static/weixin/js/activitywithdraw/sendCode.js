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
                $("#code").removeClass("again").html(timeout + desc);
            } else {
                $("#code").addClass("again").html($("#code").attr("default-value")).attr("disabled", null);
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
            var phone = '';
            phone = $.trim($("#phone").val());
            var pattern = PHONE_PATTERN;

            if(phone == '' ) {
                $("#code").addClass("error").html('请输入手机号');
                return false;
            }
            if( !phone.match(pattern) ){
                $("#code").addClass("error").html('手机号错误');
                return false;
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
                            $("#code").addClass("again").html(sendRes.msg + "," + timeout + desc).attr("disabled", true);
                        }
                        timer = setInterval(function() {
                            timeout--;

                            if(timeout > 0) {
                                $("#code").removeClass("again").html(sendRes.msg + "," + timeout + desc);
                            } else {
                                $("#code").addClass("again").html($("#code").attr("default-value")).attr("disabled", null);
                                clearInterval(timer);
                            }            

                        }, 1000);

                    } else {
                        if(options.autoPhone && sendRes.login === false) {
                            location.href = '/activityIndex/index';
                        } 
                        $("#code").addClass("error").html(sendRes.msg);

                    }
                },
                error : function(msg) {
                    console.log(msg);
                    $("#code").html("服务器端错误，请点击重新获取").attr("disabled", null);
                    clearInterval(timer);
                }
            });
        });
    }
});
})(jQuery);