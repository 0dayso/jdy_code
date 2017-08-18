(function($){
    /******************充值秒跳****************/
    var timeout;
    var go;
    function timer(){
        timeout--;
        if(timeout==0){
            $('#code').removeClass('disable').val('重新获取验证码');
            $('#code').removeAttr("disabled");
            timeout = 60;
            clearInterval(go);
            $.cookie('sendCode', '', { expires:-1 });
        }else{
            $('#code').attr("disabled","true");
            $('#code').addClass('disable').val(timeout+'秒后重发');
            $.cookie('sendCode', timeout, { expires:  timeout});
        }
    }
    if($.cookie("sendCode")>0){
        timeout = $.cookie("sendCode");
        go = setInterval(timer,1000);
    }else{
        timeout = 60;
    }

    /******************充值功能块****************/
    $.extend({
        //签约and发送验证码
        sendCode: function(type){
            var obj = $('#code');
            obj.click(function () {
                var id_card = $('input[name=id_card]').val(),
                    order_id = $('input[name=order_id]').val(),
                    name = $('input[name=name]').val(),
                    cash = $('input[name=cash]').val(),
                    card_no = $('input[name=card_no]').val(),
                    phone = $('input[name=phone]').val(),
                    bank_code = $('input[name=bank_code]').val();
                if( type == 'suma' ){
                    $.sendSumaCode(type,id_card,order_id,name,cash,phone,card_no,bank_code);
                }else{
                    if(obj.attr('value')=='重新获取'){
                        $.sendPass(type,order_id);
                    }else{
                        $.sendSign(type,id_card,order_id,name,cash,phone,card_no,bank_code);
                    }
                }
            })
        },
        sendSign: function(type,id_card,order_id,name,cash,phone,card_no,bank_code){
            $.ajax({
                url:'/pay/sendSign',
                type:'POST',
                data:{type:type,phone:phone,order_id:order_id,name:name,cash:cash,id_card:id_card,card_no:card_no,bank_code:bank_code,_token:$('input[name=_token]').val()},
                dataType:'json',
                success:function(result){
                    if(result.status=='success') {
                        $.showTips($('#form-tips'), '发送成功', 'error');
                        go = setInterval(timer,1000);
                        if(type=='ump') $.sendPass(type,order_id,phone);
                    } else {
                        $.showTips($('#form-tips'), result.msg, 'error');
                    }
                },
                error:function(msg){
                    console.log(msg);
                }
            })
        },
        sendPass: function(type,order_id,phone) {
            $.ajax({
                url:'/pay/sendCode',
                type:'POST',
                data:{type:type,order_id:order_id,phone:phone,_token:$('input[name=_token]').val()},
                dataType:'json',
                success:function(result){
                    if(result.status=='success') {
                        $.showTips($('#form-tips'), '发送成功', 'error');
                        go = setInterval(timer,1000);
                    } else {
                        $.showTips($('#form-tips'), result.msg, 'error');
                    }
                },
                error:function(msg){
                    console.log(msg);
                }
            })
        },
        //TODO:丰付支付
        sendSumaCode: function(type,id_card,order_id,name,cash,phone,card_no,bank_code){
            var is_first= 1;
            var bankId  = $('input[name=bank_id]').val();
            //TODO:重复点击
            var isSend   = $('#isSend').val();
            if( isSend == "yes"){
                return false;
            }
            $("#isSend").val('yes');
            $('#code').attr("disabled","true");
            $('#code').addClass('disable').val('只能发送一次');
            $.ajax({
                url:'/pay/sendSign',
                type:'POST',
                data:{type:type,phone:phone,order_id:order_id,name:name,cash:cash,id_card:id_card,card_no:card_no,bank_code:bank_code,is_first:is_first,bank_id:bankId,_token:$('input[name=_token]').val()},
                dataType:'json',
                success:function(result){
                    if(result.status=='success') {

                        $('#randomValidateId').val(result.randomValidateId);
                        $('#tradeId').val(result.tradeId);
                        $.showTips($('#form-tips'), '发送成功', 'error');
                        //go = setInterval(timer,1000);
                        $("#isSend").val('no');
                    } else {
                        $("#isSend").val('no');
                        $.showTips($('#form-tips'), result.msg, 'error');
                    }
                },
                error:function(msg){
                    $("#isSend").val('no');
                    console.log(msg);
                }
            })
        },


        //联动优势需要自己验证 验证码
        checkCode: function(code,phone){
            $.ajax({
                url:'/pay/checkCode',
                type:'POST',
                data:{code:code,phone:phone,_token:$('input[name=_token]').val()},
                dataType:'json',
                success:function(result){
                    if(result.status==false) {
                        $.showTips($('#form-tips'), result.msg, 'error');
                    }else{
                        clearInterval(go);
                        $.cookie('sendCode', '', { expires:-1 });
                        $('#rechargeSub').submit();
                    }
                },
                error:function(msg){
                    console.log(msg);
                }
            })
        },

        //充值提交验证
        paySub: function(type){
            $('#paySub').click(function(){
                if(!$.agree()) return false;
                if(!$.phone_check($('input[name=phone]'))) return false;
                if(!$.captcha_check($('input[name=code]'))) return false;
                if(type=='ump')
                    $.checkCode($('input[name=code]').val(),$('input[name=phone]').val());
                else{
                    clearInterval(go);
                    $.cookie('sendCode', '', { expires:-1 });
                    if(type=='rea' || type=='suma'){
                        $('#rechargeSub').submit();
                        $('#paySub').attr('disabled',true);
                        $('#paySub').css({'background-color':'#ccc'});
                        $('input[name=_token]').val('');
                    }else{
                        $('#rechargeSub').submit();
                    }

                }

            })
        },

        //同意服务协议
        agree:function(){
            if(!$('#agree').is(':checked')){
                $.showTips($('#form-tips'), '请同意协议再充值', 'error');
                return false;
            }else{
                $.showTips($('#form-tips'), '', 'success');
                return true;
            }
        },

        //手机验证
        phone_check: function (phone_obj){
            var phone = $.trim(phone_obj.val());

            var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/;
            if(phone.length == 0) {
                $.showTips($('#form-tips'), '手机号码不能为空', 'error');
                return false;
            }
            if(!phone.match(pattern)) {
                $.showTips($('#form-tips'), '请输入正确的手机号码', 'error');
                return false;
            }
            return true;
        },

        //验证码验证
        captcha_check: function (cap_obj){

            var code = $.trim(cap_obj.val());
            if(code.length == 0) {
                $.showTips($('#form-tips'), '验证码不能为空', 'error');
                return false;
            }

            var pattern = /[0-9]{6}/;
            if (!code.match(pattern)) {
                $.showTips($('#form-tips'), '请输入有效的短信验证码', 'error');
                return false;
            }

            $.showTips($('#form-tips'), '', 'success');
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