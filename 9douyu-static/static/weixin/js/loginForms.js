(function($){
     $(function(){
         
         //输入框失去焦点x
         //text 和 password两种数据同步
         $(".pwdTip input").eq(0).blur(function(){
             $(".pwdTip input").eq(1).val($(this).val());
         });
         $(".pwdTip  input").eq(1).blur(function(){
             $(".pwdTip  input").eq(0).val($(this).val());
         });

         //判断用户名
         $("#username").blur(function(){

              commonUtil.username(this.value);
         });
         //判断密码
         $("#password").blur(function(){

             commonUtil.pwd(this.value);
         });

         //手机号码判断
         $("#phone").blur(function(){
              commonUtil.phone(this.value);
         });
         //验证码判断
         $("#code").blur(function(){
             commonUtil.code(this.value);
         });
         //点击查看密码
         $(".wap2-eye").click(function(){
             var rel= $.trim($(".wap2-eye").attr("class"));
            // alert(rel);
                if(rel=='wap2-eye'){
                    $(".wap2-eye").addClass('open');
                    $("#password").hide();
                    $("#showPwd").show();

                }else{
                    $(".wap2-eye").removeClass('open');
                    $(".wap2-eye").attr('type','password');
                    $("#password").show();
                    $("#showPwd").hide();
                }
         });

         //登录按钮提交
         $("#loginForm").submit(function(){

             var username=$("#username").val();
             var password=$("#password").val();
             if(commonUtil.username(username)!=true){
                 return false;
             }
             if( commonUtil.pwd(password)!= true){
                return false;
             }
                 return true;
         });
         //注册下一步按钮
         $("#registerForm").submit(function(){

             var phone=$("#phone").val();
             var password=$("#password").val();
             var code=$("#code").val(); 
             if(commonUtil.phone(phone)!=true) {
                 return false;
             }
             if(commonUtil.pwd(password)!=true){
                return false;
             }
             if(commonUtil.code(code)!=true){
                 return false;
             }

             return true;
         });

     });
    var commonUtil={
        username:function(username){
                   username=$.trim(username);
                  var pattern = /^[0-9a-z]{6,30}$/i;
                   if(username =="" || username == null ) {
                      commonUtil.waring('请输入手机号/用户名');
                       return false;
                   }
                   if(!username.match(pattern)) {
                       commonUtil.waring('用户名6-30位字母或数字');
                         return false;
                    }
                    commonUtil.tips();
                    return true;

             },
        phone:function(phone){
            var phone  = $.trim(phone);
            var pattern = PHONE_PATTERN;
            var unique ='';
            if(phone == '') {
                commonUtil.waring('请输入手机号码');
                return false;
            }
            if(!phone.match(pattern)) {
                commonUtil.waring('手机号码格式不正确，请重新输入');
                return false;
            }else{

                    $.ajax({
                        url:'/register/checkUnique',
                        type:'POST',
                        data:{phone:phone,type:'phone'},
                        dataType:'json',
                        async:false,
                        async: false,  //同步发送请求
                        success:function(result){
                            unique=result.status;
                        },
                        error:function(){
                            commonUtil.tips("手机校验失败");
                            return true;
                        }
                    });
                if(!unique) {
                    commonUtil.waring("手机号已经注册");
                    $("#code").attr('data-lock','lock');
                    return false;
                } else {
                    commonUtil.tips();
                    $("#code").attr('data-lock','');
                    return true;
                }
            }


        },
        pwd:function(password){
                    var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
                    if(password == '') {
                         commonUtil.waring('密码6-16位的字母及数字组合');
                         return false;
                    }
                    if(!password.match(pattern)){
                         commonUtil.waring('密码格式不正确');
                         return false;
                     }
                   commonUtil.tips();
                   return true;
            },
        code:function(code){
            var code = $.trim(code);
            if(code == '') {
                commonUtil.waring('请输入验证码');
                return false;
            }
             
            $.ajax({
                        url:'/register/checkPhoneCode',
                        type:'POST',
                        data:{code:code},
                        dataType:'json',
                        async:false,
                        async: false,  //同步发送请求
                        success:function(result){
                            code=result.status;
                        },
                        error:function(){
                            commonUtil.tips("请输入正确的验证码");
                            return true;
                        }
                    });
                if(!code) {
                    commonUtil.waring("请输入正确的验证码");
                    return false;
                } else {
                    commonUtil.tips();
                    return true;
                }
            commonUtil.tips();
            return true;
        },
        tips:function(){
            $(".error").find("p").eq(0).text('');
        },
        waring:function(msg){
            $(".error").find("p").eq(0).text(msg);

         }

    }

})(jQuery);