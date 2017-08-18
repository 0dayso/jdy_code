(function($) {
$(document).ready(function() {
    
    $("#phone,#password,#password2").focus(function(){
        $(this).parent().siblings(".tips-msg").show();
        $(this).parent().siblings(".tips-msg").removeClass("tips-success tips-error");
    }).blur(function(){
        
        $(this).parent().siblings(".tips-msg").hide();
        
    });
    $("#phone").focus(function(){
        $(this).parent().siblings(".tips-msg").html("请填写真实有效的手机号码");
    });

    $("#password").blur(function(){
        var password = $.trim($("input[name=password]").val());
        var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
        if($("input[name=password]").val() == '') {
            return false;   
        };
        if(!password.match(pattern)){
            $(this).btnShowTips('6到16位的字母及数字组合');
        }else {         
            $(this).btnShowTips('','success');
        }
    });

    function checkPasswordConfirm() {
        if($.trim($("input[name=password]").val()) == '') {
            return false;   
        };
        if($.trim($("input[name=password]").val()) != $.trim($("input[name=password2]").val())){
            $("input[name=password2]").btnShowTips('两次密码输入不一致');
        }else {
            $("input[name=password2]").btnShowTips('','success');
        }
    }


    $("#password2").blur(function(){
        if($.trim($("input[name=password2]").val()) != '') {
            checkPasswordConfirm()
        }
    });

    $("#password").change(function() {
        if($.trim($("input[name=password2]").val()) != '') {
            checkPasswordConfirm()
        }
    });

    $("#phone").blur(function(){
        var phone = $.trim($("input[name=phone]").val());
        var pattern = PHONE_PATTERN;
        if($("input[name=phone]").val() == '') {
            return false;   
        };
        if(!phone.match(pattern)) {
            $(this).btnShowTips('请输入正确的手机号码');
            return false;
        }
    });
    
    //验证码
    $("#registerForm").submit(function() {
        var textArr = {
            'password': '密码不能为空',
            'password2':'请再次确认密码',
            'phone':'手机号不能为空'
        }
        
        var failFlag = false;
        $("input[type=text],input[type=password]").each(function(){
            if($(this).val() == '') {
                $(this).btnShowTips(textArr[$(this).attr("name")]);
                failFlag = true;
                return false;
            }
        });
        
        if(failFlag) return false;
        
        if(!$(this).data("checkCode")) {
            var $This = $(this);
            $.ajax({
                url:"/Utility/Captcha/checkCode",
                type:"POST",
                data:{
                    code:$.trim($("input[name=code]").val())
                },
                dataType:"json",
                async:false,
                success:function(result) {
                    if (result.status == 1) {
                        $This.data("checkCode", true);
                        $This.submit();
                    } else {
                        $("input[name=code]").btnShowTips('验证码错误');
                        $("#captcha").click();
                        setTimeout(function(){
                            $("input[name=code]").btnShowTips('', 'success');
                        }, 3000);
                    }
                },
                error:function(msg) {
                    $("input[name=code]").btnShowTips('服务器发生错误');
                }
            });
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
        
        if(!$(this).data("checkCode")) {
            var $This = $(this);
            $.ajax({
                url:"/Utility/Captcha/checkCode",
                type:"POST",
                data:{
                    code:$.trim($("input[name=code]").val())
                },
                dataType:"json",
                async:false,
                success:function(result) {
                    if (result.status == 1) {
                        $This.data("checkCode", true).data("lock", false);
                        $This.submit();
                    } else {
                        $("input[name=code]").btnShowTips('验证码错误');
                        $("#captcha").click();
                        setTimeout(function(){
                            $("input[name=code]").btnShowTips('', 'success');
                        }, 3000);
                    }
                },
                error:function(msg) {
                    $("input[name=code]").btnShowTips('服务器发生错误');
                }
            });
            return false;
       }
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
