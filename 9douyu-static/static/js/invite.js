(function($){
	
	$(document).ready(function(){
        $("#phone").blur(function(){
            var phone_obj = $(this);
            $.phone_unique_check(phone_obj);
        });
        
        $("#real_phone").blur(function(){
            var phone_obj = $(this);
            $.phone_check(phone_obj);
        });
        
        $("#real_name").blur(function(){
            var name_obj = $(this);
            $.name_check(name_obj);
        });
        
        $("input[name='captcha']").blur(function(){
            var cap_obj = $(this);
            $.captcha_check(cap_obj);
        });
        
        $("#code").click(function(){
            if($.checkCaptchaPhone()) {
                $('#phoneInviteForm').submit();
            }
        });
        
        $("#addFriend").click(function(){
            var name_obj   = $("#real_name");
            var phone_obj  = $("#real_phone");
            if(!$.name_check(name_obj))        
                return false;
            if(!$.phone_check(phone_obj))
                return false;
            var form = $("#realForm");
            $(".login-btn").hide();
            $("#popMsg").html('正在添加中....请稍后');
            $(".pop").popDiv(500);
            $.ajax({
                url :form.attr("action"),
                type:form.attr("method"),
                data:{real_phone:phone_obj.val(),real_name:name_obj.val()},
                dataType:'json',
                success:function(result){
                    $(".pop_ing").hide();
                    if(result.login) {
                        $(".login-btn").hide();
                        $(".close-btn").show();
                        if(result.status) {
                            $("#popMsg").html('恭喜您，添加好友成功');
                            $(".pop").popDiv(500);
                        } else {
                            $("#popMsg").html(result.errorMsg);
                            $(".pop").popDiv(500);
                        }
                    } else {
                        $("#popMsg").html('登陆超时');
                        $(".login-btn").show();
                        $(".close-btn").hide();
                        $(".pop").popDiv(500);
                    }
                },
                error:function(msg){
                    console.log(msg);
                }
            });

        });
    })
})(jQuery);