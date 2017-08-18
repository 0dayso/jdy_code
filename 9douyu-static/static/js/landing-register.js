(function($) {
$(document).ready(function() {
 
	$("#phone").focus(function(){
        $(".btn-tips").html("请填写真实有效的手机号码");
    });
    $("#password").focus(function(){
        $(".btn-tips").html("6到16位的字母及数字组合");
    });
	$("#password2").focus(function(){
        $(".btn-tips").html("6到16位的字母及数字组合");
    });
	$("form input").blur(function(){
		$(".btn-tips").html("");
	});
    $("#password").blur(function(){
        var password = $.trim($("input[name=password]").val());
        var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
        if($("input[name=password]").val() == '') {
            return false;   
        };
        if(!password.match(pattern)){
           $(".btn-tips").html('6到16位的字母及数字组合').addClass("tips-error");
		   
        }else {         
            $(".btn-tips").html('');
        }
    });

    function checkPasswordConfirm() {
        if($.trim($("input[name=password]").val()) == '') {
            return false;   
        };
        if($.trim($("input[name=password]").val()) != $.trim($("input[name=password2]").val())){
            $(".btn-tips").html('两次密码输入不一致').addClass("tips-error");
        }else {
            $(".btn-tips").html('');
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
            $(".btn-tips").html('请输入正确的手机号码').addClass("tips-error");
            return false;
        }
    });
    
    //验证码
    $("#registerForm").submit(function() {
        var textArr = {
            'password': '密码不能为空',
            'password2':'请再次确认密码',
            'phone':'手机号不能为空',
			'code': '验证码不能为空'
        }
        
        var failFlag = false;
        $("input[type=text],input[type=password]").each(function(){
            if($(this).val() == '') {
				$(this).addClass("hover");
                $(".btn-tips").html(textArr[$(this).attr("name")]).addClass("tips-error");
				
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
						
                        $(".btn-tips").html('验证码错误').addClass("tips-error");
                        $("#captcha").click();
                        setTimeout(function(){
                            $(".btn-tips").html('');
                        }, 3000);
                    }
                },
                error:function(msg) {
                    $(".btn-tips").html('服务器发生错误').addClass("tips-error");
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
            $(this).addClass("hover")
        }).blur(function(){
            $(this).removeClass("hover")
        })
    })
});
})(jQuery);
