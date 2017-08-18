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

         //判断登录用户名
         $("#username").on("blur",function(){

             commonUtil.phone(this.value);
         });
         //判断登录密码
         $("#password").on("blur keyup focusin",function(){
             if($("#username").val() == ''){
                 commonUtil.waring('请先输入手机号码1');
                 commonUtil.lock("lock",false);
                 commonUtil.hideEvn("wap2-delete");
                 return false;
             }
             if(($("#password").val()).length >= 6){
                 commonUtil.pwd(this.value);
             }
         });

         //注册手机号码判断
         $("#phone").on("blur",function(){
              commonUtil.phone(this.value);
         });

         //注册第一步输入手机号和手机号验证码的判断
         $("input[name=code],input[name=phone]").on({
             keyup: function(){
                 if($.trim($("#code").val()) != '' && $.trim($("#phone").val()) != '') {
                     commonUtil.lock(false,true);
                     $("#submit-next").removeClass("disabled");
                 }else{
                     $("#submit-next").addClass("disabled");
                 }
             },
             blur: function() {
                 $(this).keyup();
             }
         });

         //验证码判断
         /*$("#code").on("keyup blur",function(){
             commonUtil.code(this.value);
         });*/
         $("#reg_password").on("blur keyup",function(){
             if(($("#reg_password").val()).length >= 6){
                 commonUtil.pwd(this.value);
             }
         });
         //登录按钮提交
         $("#loginForm").submit(function(){

             var lockStatus  = $("#submit-next").attr("data-lock");
             if( lockStatus  == 'lock' ){
                 return false;
             }
             var password   = $("#password").val();

             if(commonUtil.pwd(password)!=true) {
                 return false;
             }
         });
         //注册下一步按钮
         $("#registerForm").submit(function(){
             var lockStatus  = $("#submit-next").attr("data-lock");
             if( lockStatus  == 'lock' ){
                 return false;
             }
             if(!document.getElementById("check").checked) {
                 commonUtil.waring('请勾选注册协议');
                 return false;
             }
             var phone=$("#phone").val();
             var password=$("#password").val();
             var code=$("#code").val();
             if(commonUtil.phone(phone) != true){
                 return false;
             }
             if(code == '') {
                 commonUtil.waring('验证码不能为空');
                 commonUtil.lock("lock",false);
                 return false;
             }
             if(commonUtil.pwd(password)!=true){
                return false;
             }
             /*if(commonUtil.code(code)!=true){
                 return false;
             }*/
             //commonUtil.code(code);
             return true;
         });
         //完成注册提交验证
         $(".submit-finished").click(function(){

             commonUtil.register_check();
             //return true;
         })
     });
    var commonUtil={
        phone:function(phone){
            var phone  = $.trim(phone);
            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
            var unique ='';
            commonUtil.showEvn("wap2-delete");
            if(phone == '') {
                commonUtil.waring('请输入手机号码');
                commonUtil.lock("lock",false);
                commonUtil.hideEvn("wap2-delete");
                return false;
            }
            if(phone.length <11){
                commonUtil.waring('请输入11位手机号码');
                commonUtil.lock("lock",false);
                return false;
            }
            if(!phone.match(pattern)) {
                commonUtil.waring('手机号码格式不正确，请重新输入');
                commonUtil.lock("lock",false);
                return false;
            }
            commonUtil.tips();
            commonUtil.lock(false,true);
            return true;
        },
        pwd:function(password){
                    var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
                    commonUtil.showEvn("wap2-delete");
                    if(password == '') {
                         commonUtil.waring('密码6-16位的字母及数字组合');
                         commonUtil.lock("lock",false);
                         commonUtil.hideEvn("wap2-delete");
                         return false;
                    }
                    if(!password.match(pattern)){
                         commonUtil.waring('密码格式不正确');
                        commonUtil.lock("lock",false);
                         return false;
                     }
                   commonUtil.tips();
                   commonUtil.lock(false,true);
                   return true;
            },
        code:function(code){
            var phone=$("#phone").val();
            var code = $.trim(code);
            if(code == '') {
                commonUtil.waring('请输入验证码');
                commonUtil.lock("lock",false);
                return false;
            }

            $.ajax({
                        url:'/register/checkPhoneCode',
                        type:'POST',
                        data:{code:code,phone:phone},
                        dataType:'json',
                        /*async:false,
                        async: false,  //同步发送请求*/
                        success:function(result){
                            code=result.status;
                            if(!code) {
                                commonUtil.waring("请输入正确的验证码");
                                commonUtil.lock("lock",false);
                                return false;
                            } else {
                                commonUtil.tips();
                                commonUtil.lock(false,true);
                                return true;
                            }
                        },
                        error:function(){
                            commonUtil.tips("请输入正确的验证码");
                            commonUtil.lock("lock",false);
                            return true;
                        }
                    });

        },
        //wap端注册验证v3.1.0
        register_check:function(){
            var phone = $("input[name='phone']").val();
            var code = $("input[name='code']").val();
            var aggreement = $("input[name='aggreement']").val();
            var password = $("input[name='password']").val();
            var invite_phone = $("input[name='invite_phone']").val();

            $.ajax({
                url:'/registerAjaxFormCheck',
                type:'POST',
                data:{phone_code:code,phone:phone,aggreement:aggreement,password:password,invite_phone:invite_phone},
                dataType:'json',
                /*async:false,
                 async: false,  //同步发送请求*/
                success:function(result){
                    var status = result.status;
                    if(!status) {
                        commonUtil.waring(result.msg);
                        //commonUtil.lock("lock",false);
                        return false;
                    } else {
                        commonUtil.tips();
                        commonUtil.lock(false,true);
                        //return true;
                        $("#registerConfirmForm").submit();
                    }
                },
                error:function(){
                    commonUtil.tips("");
                    commonUtil.lock("lock",false);
                    return true;
                }
            });

        },
        tips:function(){
            $(".error").text('');
        },
        waring:function(msg){
            $(".error").text(msg);

        },
        lock:function (lock,status) {
            $("#submit-next").attr('data-lock',lock);
            if(status == true) {
                $("#submit-next").removeClass("disabled");
            } else {
                $("#submit-next").addClass("disabled");
            }
        },
        showEvn:function (classEvn) {
            $("."+classEvn).show();
        },
        hideEvn:function (classEvn) {
            $("."+classEvn).hide();
        }

    }

})(jQuery);