(function($){
    $(document).ready(function(){
        //计算收益
        var calculatePredictProfit = function() {
            $("input[name=assign_cash]").formatInput(/^$|^\d+(?:\.\d{0,2})?$/);

            var assignPrincipal      = $.toFixed($("input[name=assign_cash]").val());
            var assignableCash  = $.toFixed($("#assignable_cash").val());

            if(isNaN(assignPrincipal) || (assignPrincipal < 10)) {
                resetPredictProfit();
                return false;
            }

            if(assignPrincipal > assignableCash) {
                assignPrincipal = assignableCash;
                $("input[name=assign_cash]").val(assignPrincipal);
            }

            var discountRate    = $.toFixed($("input[name=discount_rate]").val(), 1);
            if(isNaN(discountRate)) {
                discountRate    = 0.0;
                $("input[name=discount_rate]").val('0.0');
            }else if(discountRate>10){
                discountRate = 10;
            }

            //手续费
            if($("#free_handling").val() == "1") {
                var handlingFee = 0.00;
            } else {
                var handlingFee = $.toFixed(assignPrincipal * $("#handle_rate").val());
            }
            $(".handling-fee").html(handlingFee);

            //待收利息
            var interestSum     = $.toFixed($("#interest_sum").val());
            var interestDay     = $.toFixed($("#interest_day").val());
            var totalDay        = $.toFixed($("#total_day").val());
            var interestAccrual = $.toFixed($("#interest_accrual").val());
            var interest = $.toFixed((interestAccrual * (assignPrincipal / assignableCash)) + (interestSum * (interestDay / totalDay) * (assignPrincipal / assignableCash)));

            //预计到账金额
            var predictProfit = $.toFixed((assignPrincipal * (1 - (discountRate / 100.0))) + interest - handlingFee);
            $(".predict-profit").html($.formatMoney(predictProfit));

            /*
             console.log([interestSum, interestDay, totalDay, interestAccrual,
             interest, assignPrincipal, discountRate,
             handlingFee, assignPrincipal, assignableCash]);
             // */

            return true;
        }

        //重置收益数据
        var resetPredictProfit = function() {
            $(".handling-fee").html('0.00');
            $(".predict-profit").html('0.00');
        }


        $("body").on("keyup change blur", "input[name=assign_cash], input[name=discount_rate]", function(){
            calculatePredictProfit();
        });

        $("body").on("keyup change blur", "input[name=discount_rate]", function(){
            $(this).formatInput(/^$|^\d+(?:\.\d{0,1})?$/);
            if(parseFloat($(this).val()) > parseFloat($("#max_discount_rate").val())) { //不能超过最大折让率
                $(this).val($("#max_discount_rate").val());
            }
        });

        $('.next').click(function(){
            calculatePredictProfit();
            $('.wap2-pop').show();
        });
        $('#cancel_button').click(function(){
           // calculatePredictProfit();
            $('.wap2-pop').show();
        });

        $('.cancel').click(function(){
            $('.wap2-pop').hide();
        });

        //function payPassword(){
        //    var password = $("input[name=trading_password]");
        //    var passwordV = $.trim(password.val());
        //    var pattern = /[0-9]{6}/;
        //    if(passwordV=='') {
        //        password.val('');
        //        password.attr("placeholder", "请输入交易密码");
        //        return false;
        //    } else if(passwordV.length!=6) {
        //        password.val('');
        //        password.attr("placeholder", "请输入6位数字交易密码");
        //        return false;
        //    } else if(!pattern.test(passwordV)) {
        //        password.val('');
        //        password.attr("placeholder", "密码有误，请重新输入");
        //        return false;
        //    } else {
        //        return true;
        //    }
        //}


        //提交债权转让申请
        $("body").on("click", "#sub", function(){
            //var payPass = payPassword();
            //if( payPass ){
            //    $('#submit').submit();
            //}else{
            //    return false;
            //}
            var password = $("input[name=trading_password]");
            var passwordV = $.trim(password.val());
            if(passwordV==''){
                $("#err_tips").html("交易密码不能为空!");
               // password.attr("placeholder", "");
                return false;
            }
            $.ajax({
                url:'/CurrentInvest/ajaxTradingPassword',
                type:'POST',
                data:{trading_password:passwordV},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result==false) {
                        //password.val('');
                        $("#err_tips").html("交易密码有误,请重新输入!");
                        //password.attr("placeholder", "密码有误，请重新输入");
                        return false;
                    } else {
                        $('#preAssign').submit();
                    }
                }
            });
        });
        
        //提交取消转让
        $("body").on("click", "#cal_sub", function(){
            if($(this).data("lock")) {
                return false;
            }
            var projectId       = parseInt($.trim($("input[name=project_id]").val()));
          
            $.ajax({
                url: '/OwnCreditAssign/checkProjectId',
                type: 'POST',
                dataType: 'json',
                data: {projectId: projectId},
                async: false,  //同步发送请求
                success: function(result) {
                   if(result==true) {
                       $('#calAssign').submit();
                   }
                   
                }
            });
        }); 

        //债权转让
        $(".assign-ctrl").click(function(){
            if($(this).data("lock")) return false;

            $(this).data("lock", true);
            ajustPopup(".credit-assign-loading");
            var obj = $(this);
            var url = $(this).attr("href");
            $.ajax({
                url: url,
                success: function(html) {
                    $(obj).data("lock", null);
                    $(".credit-assign-preview").html(html);

                    ajustPopup(".credit-assign-preview");
                },
                error: function(msg) {
                    $(obj).data("lock", null);
                    console.log(msg);
                }
            });
            return false;
        });
       
    });
})(jQuery);