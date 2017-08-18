(function($){
	$(document).ready(function(){
            var checkcode=$("#checkcode1").data("img");
            var channel=$('.re-form').attr('data-channel');
		    $("<form>",{action:'/register/doRegister',id:'registerForm',method:"post"}).appendTo(".re-form");
            $("<input>",{type:'hidden',name:'aggreement',value:"1"}).appendTo("form");
            $("<input>",{type:'hidden',name:'channel',value:channel}).appendTo("form");
            $("<input>",{type:'hidden',name:'redirect_url',value:"/Novice/success"}).appendTo("form");
            $("<div>",{"class":"ann2promote-input-box",}).appendTo("form");
            $("<input>",{type:'text',placeholder:"请输入手机号",id:'phone',name:'phone',"class":"ann2promote-input"}).appendTo(".ann2promote-input-box");
            $("<div>",{"class":"ann2promote-input-box ann",}).appendTo("form");
            $("<input>",{type:'password',placeholder:"请设置登录密码（6-16位数字,字母）",id:'password',name:'password',"class":"ann2promote-input"}).appendTo(".ann");
            $("<div>",{"class":"ann2promote-input-box ann-1",}).appendTo("form");
            $("<input>",{type:'text',placeholder:"请输入校验码",id:'checkcode',name:'captcha',"class":"ann2promote-input"}).appendTo(".ann-1");
            $("<img>",{"class":"checkcode","src":checkcode}).appendTo(".ann-1");
            $("<div>",{"class":"ann2promote-input-box ann1",}).appendTo("form");
            $("<input>",{type:'text',placeholder:"请输入验证码",value:'',id:'phonecode',name:'code',"class":"ann2promote-input w9"}).appendTo(".ann1");
            $("<input>",{type:'button',id:'code',"default-value":"获取验证码",value:'获取验证码',"class":"ann2promote-code"}).appendTo(".ann1");
            $("<p>",{"class":"ann2promote-tip",id:"tips-error",text:''}).appendTo("form");
            $("<input>",{type:"submit","class":"ann2promote-btn",value:registerWord}).appendTo("form");
            $("<div>",{"class":"ann2promote-input-box ann2-txt"}).appendTo("form");
            $("<p>",{"class":"ann2"}).appendTo(".ann2-txt");
            $("<a>",{href:"/login","class":"blue",text:"已有帐号？"}).appendTo(".ann2");
            $("<p>",{"class":"ann3"}).appendTo(".ann2-txt");
            $("<i>",{}).appendTo(".ann3");
            $("<span>",{text:" 账户资金享有银行级别安全保障"}).appendTo(".ann3");

            $(".checkcode").click(function(){
                this.src=this.src+Math.random();
            })


    $.register = {
         tipHidden:function(){
                $("#tips-error").text('');
            },

        //验证手机号
            checkPhone:function(){

                var phone = $.trim($("input[name=phone]").val());
                var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
                if(phone == '') {
                    $("#tips-error").text('请输入手机号码');
                    return false;
                }
                if(!phone.match(pattern)) {
                   $("#tips-error").text('请输入正确的手机号码');
                    return false;
                }else{
                    this.tipHidden();
                    return true;
                }

            },
            //验证密码
            checkPassword:function(){
                var password = $.trim($("input[name=password]").val());
                var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
                if(password == '') {
                    $("#tips-error").text('请输入密码');
            
                    return false;
                }
                if(!password.match(pattern)){
                    $("#tips-error").text('6到16位的字母及数字组合');
                    return false;
                }else {
                    this.tipHidden();
                    return true;
                }
            },
            //验证校验码
            checkCaptchaCode:function(){

                var captchaCode = $.trim($("input[name=captcha]").val());

                if( captchaCode == '' ) {
                   $("#tips-error").text('请输入校验码');
                    return false;
                }
                if(isNaN(captchaCode)){
                    $("#tips-error").text('校验码格式不正确');
                    return false;
                }
                else{
                    if(captchaCode.length!==4){
                        $("#tips-error").text('校验码格式不正确');
                        return false;
                    }
                    this.tipHidden();
                }
                return true;
            },//验证验证码
            checkPhoneCode:function(){

                var phoneCode = $.trim($("input[name=code]").val());

                if( phoneCode == '' ) {
                    $("#tips-error").text('请输入验证码');
                    return false;
                }
                if(isNaN(phoneCode)){
                    $("#tips-error").text('验证码格式不正确');
                    return false;
                }
                else{
                    if(phoneCode.length!==6){
                        $("#tips-error").text('验证码格式不正确');
                        return false;
                    }
                    this.tipHidden();
                }
                return true;
            },


            //提交
            checkSubmit:function(){

                if( this.checkPhone() && this.checkPassword()&& this.checkCaptchaCode()&& this.checkPhoneCode())
                {
                    return true;
                }
                     return false;

                 }
        };

        //离开焦点
        $("#phone, #password, #checkcode, #phonecode").blur(function(){

            $.register.checkSubmit();

        });

    

	// codeCheck.js
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


	// sendCode.js

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
                $("#code").addClass("disable").val(timeout + desc);
            } else {
                $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                clearInterval(timer);
            }            

        }, 1000);
        
        $("#code").click(function(){
            var lock  =$("#code").attr('data-lock');
            if(lock =='lock') return false;
            if(timeout > 0) return false;
            
            clearInterval(timer);    //清除跳秒
            
            var captcha = '';
            if(options.captcha){
                captcha = $('input[name="captcha"]').val();
            }

            if(options.callback){
                if(!eval(options.callback).apply(this)) return false;
            }

            //联动优势
            var is_ump = $("input[name=is_ump]").val();
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
                data: {'is_ump':is_ump,'phone': phone, type: options.type, autoPhone: options.autoPhone,callback:options.callback,captcha:captcha},
                success : function(result) {
                    sendRes = result;
                    if(sendRes.captcha === false && options.captcha) {
                        	$("#captcha").click();
                    } 
                    if(sendRes.status) {
                        if(timeout <= 0) {
                            timeout = maxTimeout;
                            $("#code").addClass("disable").val(/*sendRes.msg + "," +*/timeout + desc).attr("disabled", true);
                        }
                        timer = setInterval(function() {
                            timeout--;

                            if(timeout > 0) {
                                $("#code").addClass("disable").val(/*sendRes.msg + "," + */timeout + desc);
                            } else {
                                $("#code").removeClass("disable").val($("#code").attr("default-value")).attr("disabled", null);
                                clearInterval(timer);
                            }            

                        }, 1000);

                    } else {
                        if(options.autoPhone && sendRes.login === false) {
                            location.href = '/user/login';
                        } 
                        // $("#code").addClass("error").val(sendRes.msg);
                        $("#tips-error").text(sendRes.msg);
                        $(".checkcode").attr("src",checkcode+Math.random());

                    }
                },
                error : function(msg) {
                    console.log(msg);
                    $("#code").val("重新获取").attr("disabled", null);
                    $("#tips-error").text("服务器端错误，请点击重新获取");
                    clearInterval(timer);
                }
            });
        });
    }
});

    });

	// form提交

	$(document).ready(function(){

            $.bindSendCode({'url':'/register/sendSms', type: 'register', autoPhone: false, timeout: 0, maxTimeout: 60, captcha: true});
            //输入或者失去焦点判断

            $("form").submit(function(){

                if($.register.checkSubmit() == false){
                    return false;
                }

            });

            // 锚点
            $(".ann2promote-pro-btn a").click(function() {
                $("#phone").focus();
            });

        })

})(jQuery);