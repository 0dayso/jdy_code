(function($){
$.extend({
     //手机验证
    phone_check: function (phone_obj){
        var phone = $.trim(phone_obj.val());
        var tips  = $.trim(phone_obj.attr("tips"));

        var pattern = PHONE_PATTERN;
        if(phone.length == 0) {
            //$('#tips').html('手机号码不能为空');
            return false;
        }
        if(!phone.match(pattern)) {
            $('#tips').attr('class' , 'tips error');
            $('#tips').html('请输入正确的手机号码');
            return false;
        } else {
           
            $('#code').attr('class' , "download-btn");
            $('#code').attr('disabled' ,false);
        }
        return true;
    },
    bindSendCode : function(options) {
        options = options || {type: 'APP'}
         
        var timeout = options.timeout || 0, maxTimeout = options.maxTimeout || 60, timer;
        var desc    = options.desc || "秒", sendRes;
        
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
            
            if(options.callback){
                if(!eval(options.callback).apply(this)) return false;
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
                data: {'phone': phone, type: options.type, callback:options.callback},
                success : function(result) {
                    sendRes = result;

                    if(sendRes.status) {
                        $('#tips').attr('class' , 'tips default');
                        $('#tips').html('短信已发送');

                        if(timeout <= 0) {
                            timeout = maxTimeout;
                            $("#code").addClass("disable").val("剩余" + timeout + desc).attr("disabled", true);
                        }
                        timer = setInterval(function() {
                            timeout--;

                            if(timeout > 0) {
                                $("#code").addClass("disable").val("剩余" + timeout + desc);
                            } else {
                                $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                                clearInterval(timer);
                            }            

                        }, 1000);
                    } else {
                        $('#tips').html(sendRes.msg );
                    }
                },
                error : function(msg) {
                    console.log(msg);
                    $('#tips').html("服务器端错误，请点击重新获取").attr("disabled", null);
                    clearInterval(timer);
                }
            });
        });
    }
});
})(jQuery);