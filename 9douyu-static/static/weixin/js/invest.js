(function($) {
    $(document).ready(function () {
        //投资收益计算
        $("input[name=cash]").bind("keyup blur", function () {
            if (!!$(this).attr("data-pattern")) {
                $(this).formatInput(new RegExp($(this).attr("data-pattern")));
            } else {
                $(this).formatInput(/(?!^0)^\d*$/);   //格式化，不能输入非数字，开头不能为零
            }
            var refundType 	= $("input[name=refund_type]").val();				//还款方式
            console.log(refundType);
            if(refundType == 30){
                calInvestSumPre();
            }else{
                calInvestSum();
            }

        });


        //红包下拉选择
        $(".bonus-items").change(function(){
            var thisOption = $(this).find("option").eq($(this).get(0).selectedIndex);

            var rate = thisOption.attr("data-rate");

            var min  = thisOption.attr("data-min");

            var using = thisOption.attr("data-using");

            if(thisOption.val() > 0){

                $("#min_money").val(min);

                $("#using_range").val(using);
            }else{
                $("#min_money").val(0);

                $("#using_range").val('');
            }

            if(rate) {

                var rateArr = rate.split('-');

                if (rateArr[0] == '1') {
                    $("#bonus_profit").val(0);
                    $("#bonus_money").val(rateArr[1]);
                } else {
                    $("#bonus_money").val(0);
                    $("#bonus_profit").val(rateArr[1]);
                }
            }else{
                $("#bonus_money").val(0);
                $("#bonus_profit").val(0);
            }

            calInvestSum();

        });

        $("#cash").focus(function(){

            $(".error").html('');
        });

        $("#investForm").submit(function(){

            var cash = parseFloat($.trim($("#cash").val()));

            var couponMoney = parseFloat($("#bonus_money").val());

            var rate   = $("#bonus_profit").val();

            var minMoney    = $("#min_money").val();

            if((rate > 0 || couponMoney > 0) && cash < minMoney){

                var using = $("#using_range").val();

                $(".project-tips").html('红包使用条件：' + using);

                return false;
            }

            var balance = $.toFixed($.trim($("#balance").val()));

            if(cash > balance){
                $(".project-tips").html('账户余额不足，请充值');
                return false;
            }

        });

        function calInvestSum(){

            var per  = $("#percentage").val();

            var cash = parseFloat($.trim($("#cash").val()));

            var couponMoney = parseFloat($("#bonus_money").val());

            var rate   = $("#bonus_profit").val();

            /*var minMoney    = $("#min_money").val();

             if((rate > 0 || couponMoney > 0) && cash < minMoney){

             var using = $("#using_range").val();

             $(".project-tips").html('红包使用条件：' + using);

             return false;
             }*/

            cash    += couponMoney;

            var msg  = '';

            if(cash <= 0){

                $(".project-tips").html('请输入投资金额');

                return false;
            }

            var profit = getInterest(cash, per);

            if(rate > 0){

                var rateProfit = getInterest(cash, rate);

                msg   += ',额外加息奖励'+$.formatMoney($.toFixed(rateProfit, 2))+'元';
            }

            $(".project-tips").html("当前投资额预期可收益"+ $.formatMoney($.toFixed(profit, 2))+"元"+msg);
        }


        //到期还本息
        function getInterest( cash, percentage) {

            var productLine    = $("#product_line").val();
            var projectType    = $("#project_type").val();
            var interest 	   = 0;

            if(productLine == 200 || (productLine == 100 && projectType == 1)) {

                var endTime 	   = $("#end_at").val();

                //投资日期与到期日期相差天数
                var day = GetDateDiff(new Date().toLocaleDateString(), endTime.replace(/\-/g, "/"), 'day');

                //var realInvestTime =  new Date(0,0,0).getDate();
                interest 	   = $.formatMoney($.toFixed(cash * (percentage / 100) * day / 365),2);
            }else{

                var publish    = $("#publish_at").val();

                var nextDate   = addMoth(publish.substr(0,10),1);

                var days 	   = GetDateDiff(new Date().toLocaleDateString(), nextDate.replace(/\-/g, "/"), 'day');

                var diffDay    = GetDateDiff(publish.substr(0,10).replace(/\-/g, "/"), nextDate.replace(/\-/g, "/"), 'day');

                var time       = $("#invest_time").val()-1;

                var baseProfit = $.formatMoney($.toFixed((cash * (percentage / 100) / 12)),2);

                var first      = $.formatMoney($.toFixed(baseProfit * (days / diffDay)),2);

                interest   	   = parseFloat(baseProfit * time) + parseFloat(first);
            }
            return interest;
        }

        function addMoth(d,m){
            var ds=d.split('-');
            d=new Date( ds[0],ds[1]-1+m,ds[2])
            return d.toLocaleDateString().match(/\d+/g).join('-')
        }

        /**
         * 前置付息提交
         */
        $("#investConfirmPre").submit(function(){
            if(calInvestSumPre() == true){
                if(checkPasswordPre() == true){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });

        function calInvestSumPre(){
            var refundType 	= $("input[name=refund_type]").val();				//还款方式
            var invest  	= $.toFixed($.trim($("input[name=cash]").val()));	//投资金额
            var investMin   = $.toFixed($("input[name=investMin]").val());		//投资最小金额
            var balance 	= $.toFixed($.trim($("#balance").val()));			//账户余额
            var leftAmount 	= $.toFixed($.trim($("#leftAmount").val()));		//项目剩余金额
            var investTime 	= $("input[name=invest_time]").val();				//投资期限
            var yearRate 	= parseFloat($.trim($("#profit_rate").val()));		//年华利率

            //闪电付息出始化页面显示收益
            $("#profit").html("0.00");

            //闪电付息出始化页面支付金额
            if(refundType==30 && invest%investMin > 0){
                $("#payMoney").html('0.00');
            }

            //金额不能为空
            if($.trim($("input[name=cash]").val()) == '') {
                $(".project-tips").html('请输入投资金额');
                return false;
            }

            //投资金额不为空 且 不小于最小投资金额
            if(isNaN(invest) || invest < investMin) {
                $(".project-tips").html('最低投资'+$.formatMoney(investMin)+'元');
                return false;
            }

            //闪电付息项目 投资金额 必须为 最小金额的倍数
            if(refundType == 30 && invest%investMin > 0){
                $(".project-tips").html('投资金额必须为'+investMin+'的倍数');
                return false;
            }

            //投资金额不可大于账户余额
            if(invest > balance){
                $(".project-tips").html('账户余额不足，请充值');
                return false;
            }

            //投资额不能超出项目剩余融资额
            if(invest > leftAmount) {
                $(".project-tips").html('投资额不能超出项目剩余融资额');
                return false;
            }

            //投资后项目剩余可投金额 不能小于 最小投资金额
            if(leftAmount - invest < investMin && leftAmount - invest != 0) {
                $(".project-tips").html('投资后可投金额不能小于'+investMin);
                return false;
            }

            if(refundType == 30){
                var profit = Math.abs(invest)*(yearRate/100)*investTime/365;
                $("#profit_cash").val($.formatMoney($.toFixed(profit, 2)));
                $("#profit").html($.formatMoney($.toFixed(profit, 2)));
                $("#payMoney").html(invest);
                $(".project-tips").html('');
            }else{
                $(".project-tips").html("当前投资额预期可收益"+ $.formatMoney($.toFixed(profit, 2))+"元"+couponStr);
            }

            return true;
        }

        function checkPasswordPre(){
            var passwordObj = $("#tradingPassword");
            var passwordV = $.trim(passwordObj.val());
            if(passwordV==''){
                passwordObj.attr("placeholder", "请填写交易密码");
                return false;
            }
            var flag = 0;
            $.ajax({
                url:'/user/ajaxCheckTradePassword',
                type:'POST',
                data:{trading_password:passwordV},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result == false) {
                        passwordObj.val('');
                        passwordObj.attr("placeholder","交易密码错误");
                        flag = 0;
                    } else {
                        flag = 1;
                    }
                }
            });
            if(flag == 1){
                return true;
            }else{
                return false;
            }
        }
    })
})(jQuery);
