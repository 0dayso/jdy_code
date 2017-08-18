(function($){
    $.extend({
        //验证码验证
        captcha_check: function (cap_obj){
            var code = $.trim(cap_obj.val());
            var tips = $.trim(cap_obj.attr("tips"));

            if(code.length == 0) {
                $.showTips($('#'+tips), '验证码不能为空', 'error');
                return false;
            } else {
                $.showTips($('#'+tips), '', 'success');
                return true;
            }
        },
           
        //手机验证
        phone_check: function (phone_obj){
            var phone = $.trim(phone_obj.val());
            var tips  = $.trim(phone_obj.attr("tips"));
            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
            if(phone.length == 0) {
                $.showTips($('#'+tips), '手机号码不能为空', 'error');
                return false;
            }
            if(!phone.match(pattern)) {
                $.showTips($('#'+tips), '请输入正确的手机号码', 'error');
               return false;
            } else {
                $.showTips($('#'+tips), '', 'success');
            }
            return true;
        },
        
        /**
         * 唯一性检测
         * */
        phone_unique_check: function (phone_obj) {
            if(!$.phone_check(phone_obj)) {
                return false;
            }
            var tips        = $.trim(phone_obj.attr("tips"));
            var phone       = $.trim(phone_obj.val());
            var phoneflag   = true;
            $.ajax({
                url:'/user/register/checkUnique',
                type:'POST',
                data:{phone:phone,type:'phone'},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result.status) {
                        $.showTips($('#'+tips), '', 'success');
                        phoneflag = true;
                   } else {
                        $.showTips($('#'+tips), '手机号已注册', 'error');
                        phoneflag = false;
                   }
                },
                error:function(msg){
                    console.log(msg);
                    phoneflag = false;
                }
            });
            return phoneflag;
        },
        
        //姓名验证
        name_check: function (name_obj){
            var name  = $.trim(name_obj.val());
            var tips  = $.trim(name_obj.attr("tips"));
            if(name.length == 0) {
                $.showTips($('#'+tips), '姓名不能为空', 'error');
                return false;
            } else {
                $.showTips($('#'+tips), '', 'success');
            }
            return true;
        },
        
        /*检测*/
        checkCaptchaPhone: function (){
            var cap_obj   = $("input[name='captcha']");
            var phone_obj = $("#phone");
            if(!$.captcha_check(cap_obj))        
                return false;
            if(!$.phone_unique_check(phone_obj))
                return false;
                
            return true;
        },
        
        //提示显示
        showTips: function(obj, msg, type) {
            if(typeof type == 'undefined') type = 'error';
            
            if(type == 'error') {
                var addClass    = 'tips-error';
                var removeClass = 'tips-success';
            } else {
                var removeClass = 'tips-error';
                var addClass    = 'tips-success';
            }
            
            $(obj).html(msg).addClass(addClass).removeClass(removeClass).show();
        }
    });
    
    
})(jQuery);