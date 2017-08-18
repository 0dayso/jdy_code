/**
 * Created by scofie on 16/6/20.
 */
(function($){
    $.extend({
        bindSendCode : function(options) {
            options         = options || {type: 'common', autoPhone: false}

            var timeout     = options.timeout || 0, maxTimeout = options.maxTimeout || 60, timer;
            var desc        = options.desc || "s后重发",sendRes;
            options.autoPhone = options.autoPhone || null;
            options.captcha = options.captcha || false;  //验证码
            options.url     = options.url || '/register/sendSms';
            $("#sendCode").attr("wap2-btn-disabled", null);     //删除可能存在的disabled属性
            options.desc    = desc;
            //跳秒初始化
            changeSendCode.cleanTime(timeout,desc,options.phone);

            //wap2.2修改为点击加载弹窗
            $("#sendCode").on("click touched",function(){
                var phone=$("#phone").val();
                if(phone == ''){
                    $(".error").text('请输入手机号码');
                    return false;
                }
                /*var layerStatus = $(this).attr('alert-status');

                 if( layerStatus !=  'on' || layerStatus=='') return false;

                 changeSendCode.openAlert("code");*/
                options.timeout = 0;
                //发送数据
                changeSendCode.sendCode(options);
            })
            //关闭弹窗
            $(".wap2-btn-cancle").on("click touched",function () {
                changeSendCode.closeAlert();
            })
            //验证语音提示
            $(".wap2-answer").on("click touched",function(){
                changeSendCode.openAlert("answer");
            })
            //发送短信验证
            $(".wap2-code-sms").on("click touched",function(){

                //if(timeout > 0) return false;
                //判断弹层
                if(changeSendCode.checkRule(options) == false){
                    return false;
                }
                changeSendCode.closeAlert();
                //options.url = '/register/sendSms'
                //发送数据
                changeSendCode.sendCode(options);
            });
            //关闭语音提示
            $("#cancle-answer").on("click touched",function(){
                changeSendCode.closeAlert();
            })
            //发送语音验证码
            $("#verification").on("click touched",function () {
                //if(timeout > 0) return false;
                if(changeSendCode.checkRule(options) == false){
                    return false;
                }
                options.url = '/util/sendVoiceCode'
                changeSendCode.sendCode(options);
                //关闭窗口
                changeSendCode.closeAlert();

            })
            //联系在线客服
            $(".wap2-code-customer").on('click,touched',function () {

            })
        }
    });
    var changeSendCode =  {
        openAlert:function (element) {
            if( element == 'code' ){
                $(".wap2-layer").show();
                $(".wap2-fixed-tip").animate({bottom:'0'});
            }else{
                $(".wap2-alert").show();
                $(".wap2-layer").hide();
            }
        },
        closeAlert:function () {
            $(".wap2-layer").hide();
            $(".wap2-alert").hide();
        },
        cleanTime:function (timeout,desc,phone) {
            timer = setInterval(function() {
                timeout--;
                if( timeout >0){
                    $("#sendCode").addClass("disabled").val(timeout + desc).attr('alert-status','off');
                    $('.wap2-vcode-mobile').html('验证码已发送至：'+phone);

                }else{
                    $("#sendCode").removeClass("disabled").val($("#sendCode").attr("default-value")).attr("disabled", null).attr('alert-status','on');
                    $('.wap2-vcode-mobile').html('未收到验证码?请点击发送验证码');
                    clearInterval(timer);
                }
            }, 1000);
        },
        checkRule:function (options) {

            clearInterval(options.timer);    //清除跳秒
            if($("#phone").hasClass('wrong')) return false;  //手机号码有误，禁止提交
            phone = $.trim($("#phone").val());
            if(!$.phone_check($("#phone"))) {
                return false;
            }
        },
        sendCode:function(options){
            //var phone      = options.phone;
            var phone      = $("#phone").val();
            var captcha    = $("input[name=captcha]").val();
            var timeout    = options.timeout
            var maxTimeout = options.maxTimeout
            $.ajax({
                url : options.url,
                type: 'POST',
                dataType: 'json',
                data: {'phone':phone, type: options.type, autoPhone: options.autoPhone,callback:options.callback,'captcha':captcha},
                success : function(result) {
                    sendRes = result;
                    if(sendRes.captcha === false && options.captcha) {
                        $("#captcha").click();
                    }
                    if(sendRes.status) {
                        if(timeout <= 0) {
                            timeout = maxTimeout;
                            //$("#sendCode").addClass("wap2-btn-disabled").html(timeout + options.desc).attr("disabled", true).attr('alert-status','off');
                            $("#sendCode").addClass("disabled").val(timeout + options.desc).attr("disabled", true).attr('alert-status','off');
                            $('.wap2-vcode-mobile').html('验证码已发送至：'+phone);
                        }
                        changeSendCode.cleanTime(timeout,options.desc,phone);

                    } else {
                        if(options.autoPhone && sendRes.login === false) {
                            location.href = '/user/login';
                        }
                        $("#tipMsg").text(sendRes.msg);
                        $("#captcha").click();
                    }
                },
                error : function(msg) {
                    $("#code").attr("disabled", null);
                    $("#tipMsg").text("服务器端错误，请点击重新获取");
                    clearInterval(timer);
                }
            });

        }
    }
})(jQuery);
