(function($) {
    $(document).ready(function() {

        var tip_error = $('.wap2-tip.error p');

        if($("#type").html() == 1){
            $(".wap2-input-group.mt1").show();
         }else if($("#type").html() == 2){
            $(".wap2-input-group.mt1").hide();
         }
        $("#show_notice").hide();

        /**
         * 明文显示密码
         */
        $(".wap2-eye").click(function(){
            var self = $(this);
            if(self.hasClass('open')){
                self.removeClass("open");
                $("input[name='password']").attr("type", "password");
            } else {
                self.addClass("open");
                $("input[name='password']").attr("type", "text");
            }
        });

        /**
         * 验证手机号
         */
        $("#phone").blur(function(){
            tip_error.html('');
            var phone   = $.trim($("input[name=phone]").val());
            var pattern = PHONE_PATTERN;
            var purl    = '/ActivityIndex/checkIsRegister';

            if($("input[name=phone]").val() == '' || !phone.match(pattern)) {
                tip_error.html('请输入正确的手机号码');
                return false;
            }
            $.ajax({
                url : purl,
                type: 'POST',
                dataType: 'json',
                data: {'phone': phone},
                success : function(result) {
                    Res = result;
                    if(Res.status === false) {
                        tip_error.html(Res.msg);
                    }
                    if(Res.status) {
                        if(Res.status == 2){ //手机号已存在，进行绑定
                            $(".wap2-input-group.mt1").hide();
                            $("#type").html(2);
                            $("#doRegister").attr('action',Res.action);
                        }else if(Res.status == 1){ //手机号不存在进行注册
                            $(".wap2-input-group.mt1").show();
                            $("#type").html(1);
                            $("#doRegister").attr('action',Res.action);
                        }else{
                            tip_error.html(Res.msg);
                        }
                    } else {                        
                        tip_error.html(Res.msg);
                    }
                },
                error : function(msg) {
                    tip_error.html(msg);
                }
            });
            
        });

        //手机号验证
        $("#checkRegister").submit(function() {

            var phone = $.trim($("input[name=phone]").val());
            var pattern = PHONE_PATTERN;
            if($("input[name=phone]").val() == '' || !phone.match(pattern)) {
                tip_error.html('请输入正确的手机号码');
                return false;
            }
            if(!$("#agree").is(":checked")){
                tip_error.html('请先同意协议');
                return false;
            }
        });

        //注册页面
        $("#doRegister").submit(function() {
            var type = $("#type").html();
            if($.trim($("input[name=phone]").val()) == ''){
                tip_error.html('手机号不能为空');
                return false;
            }

            //判断验证码是否为空
            if($.trim($("input[name=code]").val()) == '' && type == 1){
                tip_error.html('验证码不能为空');
                return false;
            }
            //检查密码格式是否正确
            var password = $.trim($("input[name=password]").val());
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if($("input[name=password]").val() == '') {
                tip_error.html('请填写登录密码');
                return false;
            };


            if(!password.match(pattern)){
                tip_error.html('登录密码格式不正确');
                return false;
            }
            if(!$("#agree").is(":checked")){
                tip_error.html('请先同意协议');
                return false;
            }

        });


        //注册页面
        $("#dobind").submit(function() {

            if($.trim($("input[name=phone]").val()) == ''){
                tip_error.html('手机号不能为空');
                return false;
            }

            //检查密码格式是否正确
            var password = $.trim($("input[name=password]").val());
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;

            if($("input[name=password]").val() == ''){
                tip_error.html('请填写登录密码');
                return false;
            }
            if(!password.match(pattern)) {
                tip_error.html('登录密码格式不正确');
                return false;
            };

            if(!$("#agree").is(":checked")){
                tip_error.html('请先同意协议');
                return false;
            }

        });


        var luhn = function(num){
            var str='';
            var numArr = num.split('').reverse();
            for(var i=0;i<numArr.length;i++){
                str+= (i % 2 ? numArr[i] * 2 : numArr[i]);
            }
            var arr = str.split('');
            return  eval(arr.join("+")) % 10 == 0;
        }

        //绑定银行卡表单难
        $("#doAddWithDrawCard").submit(function() {

            var number = $.trim($("input[name=card_number]").val().replace(/[ ]/g,""));
            var len = number.length;
            if(!len) {
                tip_error.html("请填写银行卡");
                return false;
            }

            //卡号必须是16、18、19位。
            //验证规则：16和19位的根据luhn规则来验证，18位的根据长度来验证
            if((len == 19 || len==16) && luhn(number)){
                return true;
            } else if(number.match(/^\\d{18}$/)){
                return true;
            }else{
                tip_error.html("请填写正确的银行卡");
                return false;
            }

        });



    });
})(jQuery);

