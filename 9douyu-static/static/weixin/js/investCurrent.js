(function($){
    $(document).ready(function(){
        //投资收益计算
        $("input[name=cash]").bind("keyup blur",function() {
            if(!!$(this).attr("data-pattern")) {
                $(this).formatInput(new RegExp($(this).attr("data-pattern")));
            } else {
                $(this).formatInput(/(?!^0)^\d*$/);   //格式化，不能输入非数字，开头不能为零
            }
            calInvestSum();
            //experienceInvest()
        });

        //零钱计划期加息券
        $("#bonus_id").change(function(){
            var rate = $(this).find("option:selected").attr('data-value');
            if(rate){
                $("#useBonus").show();
                $("#rate").val(rate);
                $("input[name=addRate]").val(rate);
            }else{
                $("#useBonus").hide();
                $("#rate").val('');
                $("input[name=addRate]").val(0);
            }
            calInvestSum();
            //experienceInvest()
        });


        //零钱计划期加息券
        //$("#bonus_id").change(function(){
        //    var rate = $(this).find("option:selected").attr('data-value');
        //
        //    if(rate > 0){
        //        $("#useBonus").show();
        //        $("#rate").val(rate);
        //    }else{
        //        $("#useBonus").hide();
        //        $("#rate").val('');
        //    }
        //    calInvestSum();
        //});

        function calInvestSum(){
            if($.trim($("input[name=cash]").val()) == '') {
                $(".project-tips").html('请输入投资金额');
                return false;
            }

            var invest      = $.toFixed($.trim($("input[name=cash]").val()));
            var investMin   = $.toFixed($.trim($("input[name=investMin]").val()));
            var investMax   = $.toFixed($.trim($("input[name=investMax]").val()));
            var userBalance = $.toFixed($.trim($("input[name=userBalance]").val()));
            var leftAmount  = $.toFixed($.trim($("input[name=leftAmount]").val()));
            var currentRate = $.toFixed($.trim($("input[name=currentRate]").val()));
            var addRate     = $.toFixed($.trim($("input[name=addRate]").val()));

            console.log(addRate);

            if(isNaN(invest)) {
                $(".project-tips").html('请输入正确投资金额');
                return false;
            }

            if(invest < investMin) {
                $(".project-tips").html('最低投资'+$.formatMoney(investMin)+'元');
                return false;
            }

            if(invest > userBalance) {
                $(".project-tips").html('账户余额不足');
                return false;
            }

            if(invest > investMax) {
                $(".project-tips").html('单人加入零钱计划总额不超过'+$.formatMoney(investMax)+'元');
                return false;
            }

            if(invest > leftAmount) {
                $(".project-tips").html('您当前可加入额度为'+$.formatMoney(leftAmount)+'元');
                return false;
            }

            /*
            var planInterest = invest*(currentRate+addRate)/365/100;
            $(".project-tips").html('当前投额预期每日收益'+$.formatMoney(planInterest.toFixed(2))+'元');
            */
            var planInterest = invest*(currentRate+addRate)/365/100;
            $(".project-tips").html('当前投额预期每日收益'+$.formatMoney(planInterest.toFixed(2))+'元');

            return true;
        }

        $('.next').click(function(){
            var checkRs = calInvestSum();
            if( checkRs ) {
                $('.wap2-pop').show();
                $('.wap2-pop-tpw-title span').html($("input[name=cash]").val());
            }
        });

        function payPassword(){
            //var password = $("input[name=trading_password]");
            //var passwordV = $.trim(password.val());
            //var pattern = /[0-9]{6}/;
            //if(passwordV=='') {
            //    password.val('');
            //    password.attr("placeholder", "请输入交易密码");
            //    return false;
            //} else if(passwordV.length!=6) {
            //    password.val('');
            //    password.attr("placeholder", "请输入6位数字交易密码");
            //    return false;
            //} else if(!pattern.test(passwordV)) {
            //    password.val('');
            //    password.attr("placeholder", "密码有误，请重新输入");
            //    return false;
            //} else {
            //    return true;
            //}
        }

        $('.cancel').click(function(){
            $('.project-tips').text('');
            $('.wap2-pop').hide();
        });

        $("#sub").click(function() {
            //var payPass = payPassword();
            //if( payPass ){
            var password = $("input[name=trading_password]");
            var passwordV = $.trim(password.val());
            if(passwordV==''){
                password.attr("placeholder", "请输入交易密码");
                return false;
            }
            $.ajax({
                url:'/user/checkTradePassword',
                type:'POST',
                data:{trading_password:passwordV},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){

                    if(result.status==false) {
                        password.val('');
                        password.attr("placeholder",result.msg);
                        return false;
                    } else {
                        $('#investHForm').submit();
                    }
                }
            });

        });
        function experienceInvest(){
            var status = $("#bonus_id").find("option:selected").attr('data-value');
            var maxCash = parseInt($('#availableCash').val());
            var cash = parseInt($.trim($("#wap2-input-cash").val()));
            if(status) {
                $("#gold-cash-s8").find("span").html(maxCash);
                $("#s8-invest-cash").html("");
                $("#use_cash").val("");
            }else{
                var day = $("#availableDay").val();
                var rate = 7;
                if (cash == "" || cash == 0) {
                    $("#gold-cash-s8").find("span").html(maxCash);
                    return false;
                }

                var usedCash = maxCash;
                if (cash <= maxCash) {
                    var usedCash = cash;
                }
                //计算收益
                $("#gold-cash-s8").find("span").html("可用"+usedCash);
                //var profit = Math.abs(usedCash) * (rate / 100) * day / 365;
                var profit= $.formatMoney((day*usedCash*(rate)/365/100).toFixed(2))
                var profitStr = $(".project-tips").html();
                var profitStr = $(".project-tips").html(profitStr+"，体验金收益:" + $.formatMoney($.toFixed(profit, 2)) + "元");
                $("#use_cash").val(usedCash);
                $("#bonus_id").val(0);
            }
        }
    });
})(jQuery);

