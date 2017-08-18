(function($){
$(document).ready(function(){
    
    $("input[type=text],input[type=password]").each(function(){
        $(this).focus(function(){
            $(this).addClass("focus").next(".new-tips").text($(this).attr("role-value")).show();
        }).blur(function(){
            $(this).removeClass("focus").next(".new-tips").hide();
        });
    });
    
    //密码判断
    $("input[name=password]").blur(function(){
       var password = $.trim($(this).val());
       var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
       if($(this).val() == '') {
           return false;   
       };
       if(!password.match(pattern)){
           $(this).attr("error", true).next(".new-tips").addClass("error").show();
       }else{
           $(this).attr("error", false).next(".new-tips").removeClass("error").hide();
       }
   });

    //交易密码判断
    $("input[name=tradepassword]").blur(function(){
        var password = $.trim($(this).val());
        //var pattern = /^\d{6}$/i;
        var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
        if($(this).val() == '') {
            return false;
        };
        if(!password.match(pattern)){
            $(this).attr("error", true).next(".new-tips").addClass("error").show();
        }else{
            $(this).attr("error", false).next(".new-tips").removeClass("error").hide();
        }
    });

    $("input[name=real_name]").blur(function(){
        var realname = $.trim($("input[name=real_name]").val());
        var pattern = /^[\u4E00-\u9FA5]+$/;
        if(!realname.match(pattern) && realname !=''){
            $(this).attr("error", true).next(".new-tips").addClass("error").show();
        }else {         
            $(this).attr("error", false).next(".new-tips").removeClass("error").hide();
        }
    });

    
    $("input[name=identity_card]").blur(function() {
        var card = $.trim($(this).val());
        if(card == '') {
            return false;   
        }
        if(!checkIdCard(card)) {
            $('#yourCode').hide();
            $(this).attr("error", true).next(".new-tips").text("身份证校验失败").addClass("error").show();
            return false;
        } else {
            $(this).attr("error", false).next(".new-tips").text("").removeClass("error").hide();
        }
    });
    
    $("input[name=identity_card]").bind("keyup",function(){
        var n= this.value.split("");
        var str = new Array();
        var strlen = n.length >= 18 ? 18 : n.length;
        for(var i=0;i<strlen;i++){
            if(i==2 || i==5 || i==9 || i== 13 || i== 17){
                str.push(n[i]+"&nbsp;");
            }else{
                str.push(n[i]);
            }
        }
        mylook =str.join("");
        $('#yourCode').html(mylook);
        
        if($(".system-message").is(":hidden")){
            if(!$("#tradingpassword").length){
                $("#yourCode").css("top","149px");
            }
        }else{
            if(!$("#tradingpassword").length){
                $("#yourCode").css("top","183px");
            }else{
                $("#yourCode").css("top","248px");
            }
        };
        $("#yourCode").show();
        
    });

    function checkForm(){
        
        var sign = false;
        var card = $.trim($("input[name=identity_card]").val());
        var name = $.trim($("input[name=real_name]").val());
        if(!checkIdCard(card)) {
            $('#yourCode').hide();
            $("input[name=identity_card]").next(".new-tips").text("身份证校验失败").addClass("error").show();
            return false;
        }
        
        if($.trim($("input[name=real_name]").val()) == '') {
            $("input[name=real_name]").next(".new-tips").text("请填写您的名字").addClass("error").show();
            return false;
        }
        
        //检查是否已经被注册过
        $.ajax({
            url: '/user/information/doVerifyAjax',
            type: 'POST',
            dataType: 'json',
            async : false,
            data: {'real_name': name,'identity_card':card},
            success:function(data){
                if(data.err==2){
                    $('#yourCode').hide();
                    $("input[name=identity_card]").next(".new-tips").text("身份证已被认证").addClass("error").show();
                    sign = false;
                }else{
                    sign = true;
                }
            }
        });

        return sign;
    }
    
    //表单提交为空判断
    $("form").submit(function() {
        var textArr = {
            'password': '密码不能为空',
            'real_name':'姓名不能为空',
            'identity_card':'身份证不能为空',
        };
        var failFlag = false;
        $("input").each(function(){
            if($(this).val() == '') {
                $(this).next(".new-tips").addClass("error").text(textArr[$(this).attr("name")]).show();
                failFlag = true;
                return false;
            }else if($(this).attr("error") == "true"){
                
                $(this).next(".new-tips").addClass("error").text($(this).attr("role-value")).show();
                failFlag = true;
                return false;
            }
        });
        
        if(!failFlag){
            failFlag = (checkForm() == false);
        }
        
        if(failFlag) return false;
        
    });
});
})(jQuery);

/**
 * card     身份证号码
 * @return  boolean
 * @example
    var result = checkIdCard('440811199004152222');
 */
function checkIdCard(card) {
    if(typeof card == "undefined") {
        return false;
    }
    
    if(!card.match(/^(\d{15}|\d{17}X|\d{18})$/i)) {
        return false;
    }
    
    if(card.length == 15) {
        card = formatCard15to18(card);
    }
    
    return checkSum(card);
}

function calVerifyNumber(base) {
    if(base.length != 17) return false;
    
    var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
    
    var verify = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
    
    var checkSum = 0;
    
    for(var i = 0; i < base.length; i++) {
        checkSum += base.substr(i, 1) * factor[i];
    }
    
    var mod = checkSum % 11;
    
    var verifyNumber = verify[mod];
    
    return verifyNumber;
}

function formatCard15to18(card) {
    if(card.length != 15) return false;
    var specialArr = ['996', '997', '998', '999'];
    if(specialArr.in_array(card.substr(12, 3))) {
        card = card.substr(0, 6) + '18' + card.substr(6, 9);
    } else {
        card = card.substr(0, 6) + '19' + card.substr(6, 9);
    }
    
    card = card + calVerifyNumber(card);
}

function checkSum(card) {
    if(card.length != 18) return false;
    
    var base = card.substr(0, 17);
    
    if(calVerifyNumber(base) != card.substr(17, 1).toUpperCase()) {
        return false;
    }
    
    return true;
}

Array.prototype.S=String.fromCharCode(2);  
Array.prototype.in_array=function(e) {
    var r=new RegExp(this.S+e+this.S);  
    return (r.test(this.S+this.join(this.S)+this.S));  
}