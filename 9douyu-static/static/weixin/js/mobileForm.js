(function($){
    $(function(){
        //输入框聚焦失焦
        $(".m-input").each(function(){
            $(this).focus(function(){
                $(this).addClass("focus").next(".m-input-tips").show();
            }).blur(function(){
                $(this).removeClass("focus").next(".m-input-tips").hide()

            });
        });
        //用户名判断
         $(".m-input[name=username]").blur(function(){
            var username = $.trim($(this).val());
            var pattern = /^[0-9a-z]{6,30}$/i;
            if($(this).val() == '') {
                return false;
            };
            if(!username.match(pattern)) {
                $(this).attr("error", true).mobileTips($(this).attr("role-value"));
                return false;
            }else{
                $(this).attr("error", false);
            }

        });
        //登录密码判断
         $(".m-input[name=password]").keyup(function(){
            var password = $.trim($(this).val());
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if($(this).val() == '') {
                return false;
            };
            if(!password.match(pattern)){
                $(this).attr("error", true).mobileTips($(this).attr("role-value"));
            }else{
                $(this).attr("error", false);
            }
        });
        //交易密码判断
        $(".m-input[name=tradepassword]").blur(function(){
            var tradepassword = $.trim($(this).val());
            //var pattern = /^[0-9]{6}$/i;
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if($(this).val() == '') {
                return false;
            };
            if(!tradepassword.match(pattern)){
                $(this).attr("error", true).mobileTips($(this).attr("role-value"));
            }else{
                $(this).attr("error", false);
            }
        });

       //验证码判断
        $(".m-input[name=code]").blur(function(){
            var code = $.trim($(this).val());
            if($(this).val() == '') {
            	$(this).attr("error", true).mobileTips($(this).attr("role-value"));
                return false;
            };
            $(this).attr("error", false);
        });
        //手机号码判断
        $(".m-input[name=phone]").blur(function(){
            var phone  = $.trim($(this).val());
            var unique = $.trim($(this).attr("unique"));
            var pattern = PHONE_PATTERN;
            if($(this).val() == '') {
                return false;
            };
            if(!phone.match(pattern)) {
                $(this).attr("error", true).attr("role-value",'请输入正确的手机号码').mobileTips('请输入正确的手机号码');
                return false;
            }else{
            	if(unique == "true") {
            		$.ajax({
	                    url:'/register/checkUnique',
	                    type:'POST',
	                    data:{phone:phone,type:'phone'},
	                    dataType:'json',
	                    async: false,  //同步发送请求
	                    success:function(result){
	                        if(!result.status) {
	                        	var msg = $(".m-input[name=phone]").attr("unique-tips");
	                        	var msg = msg ? msg : "手机号已注册";
	                            $(".m-input[name=phone]").attr("error", true).attr("role-value",msg).mobileTips(msg);
	                        } else {
	                        	 $(".m-input[name=phone]").attr("error", false);
	                        }
	                    },
	                    error:function(msg){
	                    	 $(".m-input[name=phone]").attr("error", true);;
	                    }
	                });
            		if($(this).attr("error") == true) return false;
            	} else {
                    $(this).attr("error", false);
            	}
            }
        });

        //投资金额判断
        $(".m-input[name=cash]").bind("keyup blur",function(){
            calProfit();
        });

        var calProfit = function () {

            //还款方式
            var refundType  = $("#refund_type").val();
            //投资金额
            var cash        = parseInt($.trim($("input[name=cash]").val()));
            //项目利率
            var profit      = parseFloat($.trim($("#profit_rate").val()));
            //项目期限
            var limitTimes  = $("input[name=invest_time]").val();
            //发布时间
            var publishTime = $("#publish_at").val();
            //结束时间
            var endDate     = $('#end_at').val();
            //投资最小金额
            var investMin   = $.toFixed($("input[name=investMin]").val());
            //项目剩余融资金额
            var leftAmount  = parseInt($.trim($("#leftAmount").val()));
            //用户账户余额
            var userBlance = parseInt($.trim($("#balance").val()));
            //优惠券金额
            var bonusAmount = 0;
            //投资金额对象
            var cashObj     = $("input[name=cash]");
            //投资总额
            var investSum   = $("#invest-sum");
            //立即投资按钮
            var subInvest   = $("#subInvestProject");

            $("#s8-invest-num").html('');
            $("#invest-num-s8").html('');

            if(refundType == 30){
                $("#profits").html('0.00');
            }

            //投资金额的格式是否正确
            if(cashObj.val().match(/(?!^0)^\d*$/)) {
                cashObj.data("preValue", cash);
            } else {
                cashObj.val(cashObj.data("preValue"));
            }

            investSum.html("");
            subInvest.val("立即投资").removeClass("disabled");
            $("#invest-gains").find("span").html("0.00元");

            //投资金额是否为空
            if($.trim(cashObj.val()) == '') {
                cashObj.attr("error", true);
                investSum.html('请输入投资金额');
                subInvest.addClass("disabled").val('请输入投资金额');
                return false;
            }

            //是否大于最小投资金额
            if(isNaN(cash) || cash < investMin) {
                cashObj.attr("error", true);
                investSum.html('最低投资'+$.formatMoney(investMin)+'元');
                subInvest.addClass("disabled").val('最低投资'+$.formatMoney(investMin)+'元');//闪电付息
                return false;
            }

            //前置付息投资金额必须为整数倍
            if(refundType == 30 && cash%investMin>0){
                cashObj.attr("error", true);
                investSum.html('投资金额必须为'+$.formatMoney(investMin)+'的倍数');
                subInvest.addClass("disabled").val('投资金额必须为'+$.formatMoney(investMin)+'的倍数');
                return false;
            }

            //是否超出项目剩余融资额
            if(cash > leftAmount) {
                cashObj.attr("error", true);
                investSum.html('超出项目剩余融资额');
                subInvest.addClass("disabled").val('超出项目剩余融资额');
                return false;
            }

            //投资后可投金额不能小于100
            if(leftAmount-cash < 100 && leftAmount - cash > 0) {
                cashObj.attr("error", true);
                investSum.html('投资后可投金额不能小于100');
                subInvest.addClass("disabled").val('投资后可投金额不能小于100');
                return false;
            }

            //优惠券
            if($("input[name=bonus_id], select[name=bonus_id]").size()){
                var avalue = $("input[name=bonus_id]:checked, select[name=bonus_id] option:selected").attr("data-money");
                bonusAmount = typeof avalue != 'undefined' ? avalue : 0;
            }

            //判断账户余额是否大于投资金额
            if(cash > userBlance + Number(bonusAmount) && $("input[name=userExperienceId], select[name=userExperienceId]").is(":checked") != true) {
                cashObj.attr("error", true);
                investSum.html('账户余额不足，请充值');
                subInvest.addClass("disabled").val('账户余额不足，请充值');
                return false;
            }

            cashObj.attr("error", false);
            //判断是否前置付息
            if(refundType==30){
              var profit = Math.abs(cash)*(profit/100)*limitTimes/365;
            }else{
              var profit = getInterestObj(refundType, cash, profit, limitTimes,publishTime, endDate);
            }

            //计算加息券的收益
            var couponStr = '';
            if($("input[name=bonus_id], select[name=bonus_id]").size()){

                var coupRate = $("select[name=bonus_id] option:selected").attr("data-rate");
                var couponRate = typeof coupRate != 'undefined' ? coupRate : 0;
                if(couponRate!='' && couponRate > 0){
                    var couponProfit = getInterestObj(refundType, cash, couponRate, limitTimes,publishTime, endDate);
                    couponStr += ',额外加息奖励'+$.formatMoney($.toFixed(couponProfit, 2))+'元';
                    //新手s8的收益计算
                    $("#s8-invest-num").html("加息奖励<span>"+$.formatMoney($.toFixed(couponProfit, 2))+"元</span>");
                }

            }

            if(refundType == 30){
                $("#invest-gains").find("span").html($.formatMoney($.toFixed(profit, 2))+"元");
            }else{
                $("#invest-num-s8").html("预期收益<span>" + $.formatMoney($.toFixed(profit, 2)) + "元</span>");
            }

            $("#profits").html($.formatMoney($.toFixed(profit, 2)));
            $("#profit").val($.formatMoney($.toFixed(profit, 2)));
            $("#subInvestProject").val("立即投资").removeClass("disabled");

        };

        $("input[name=bonus_id], select[name=bonus_id]").bind("change", function(){
            $("input:radio[name='userExperienceId'], select[name=userExperienceId]").prop("checked",false);

            var userBlance = parseInt($.trim($("#balance").val()));
            var invest     = parseInt($.trim($("input[name=cash]").val()));
            var avalue     = $("input[name=bonus_id]:checked, select[name=bonus_id] option:selected").attr("data-money");
            if(avalue == undefined) {
                var bonusAmount =  0;
                $("input[name=cash]").prop("readOnly",false);
            } else {
                var bonusAmount =  avalue;
            }
            if(invest > userBlance + Number(bonusAmount)) {
                $(".m-input[name=cash]").attr("error", true);
                $("#invest-sum").html('账户余额不足，请充值');
                //新手s8的收益计算
                $("#doInvestProject").css('color','#ff514e').val('账户余额不足，请充值');
            }else{
                $(".m-input[name=cash]").attr("error", false);
                $("#invest-sum").html('');
                //新手s8的收益计算
                $("#doInvestProject").css('color','#fff').val('立即投资');
            }

            if($(this).is("input")) {
                var $This = $(this);
                var checked = $(this).prop("checked");
            } else {
                var $This = $(this).children("option:selected");
                var checked = $(this).children("option:selected").size();
            }
            if(checked && !!$This.attr("data-rate") && $This.attr("data-rate") != '0.0') {
                //$("#profit_rate").val(parseFloat($("#profit_rate").attr("data-value")) + parseFloat($This.attr("data-rate")));
                $("#coupon_rate").val(parseFloat($This.attr("data-rate")));
            } else {
                //$("#profit_rate").val($("#profit_rate").attr("data-value"));
                $("#coupon_rate").val(0);
            }

            calProfit();
            experienceInvest()
        });

        $("input:radio[name='userExperienceId'], select[name=userExperienceId]").click(function(){
            var cash =  parseInt($.trim($(this).attr('cash')));
            $("input[name=cash]").val(cash).blur().prop("readOnly",true);
            $("input[name=bonus_id]").prop("checked",false);
        });

        //表单提交为空判断
        $("form").submit(function() {
            var textArr = {
                'username': '用户名不能为空',
                'phone':'手机号不能为空',
                'password': '密码不能为空',
                'code':'验证码不能为空',
                'realname':'姓名不能为空',
                'idcard':'身份证不能为空',
                'withdrawNum':'提现金额不能为空',
                'cash':'投资金额不能为空'
            };
            var failFlag = false;
            $(".m-input").each(function(){
                if($(this).val() == '') {
                    $(this).mobileTips(textArr[$(this).attr("name")]);
                    failFlag = true;
                    return false;
                }else if($(this).attr("error") == "true"){
                    $(this).mobileTips($(this).attr("role-value"));
                    failFlag = true;
                    return false;
                }
            });

            if(failFlag) return false;

        });

        function experienceInvest(){
            var status  = $("#bonusId").find("option:selected").attr('data-rate');
            var maxCash = parseInt($('#availableCash').val());
            var cash    = parseInt($.trim($("input[name='cash']").val()));
            if(status) {
                $("#gold-cash-s8").html(maxCash + "元");
                $("#s8-invest-cash").html("");
                $("#use_cash").val("");
                $(".s8-cash-check").find("label").html("不使用")
                //document.getElementById('checkcash').checked=false
            }else{
                var day = $("#availableDay").val();
                var rate = $("#profit_rate").val();

                if (cash == "" || cash == 0) {
                    return false;
                }
                var usedCash = maxCash;
                if (cash < maxCash) {
                    var usedCash = cash;
                }
                //计算收益
                $("#gold-cash-s8").html("可用"+usedCash + "元");
                var profit = Math.abs(usedCash) * (rate / 100) * day / 365;
                var profitStr = $("#s8-invest-cash").html("预期收益<span>" + $.formatMoney($.toFixed(profit, 2)) + "元</span>");
                $("#use_cash").val(usedCash);
                $(".s8-cash-check").find("label").html("使用")
                document.getElementById('checkcash').checked=true
            }
        }

        //到期还本息
        function getInterest( cash, percentage) {

            var productLine    = $("#product_line").val();
            var projectType    = $("#project_type").val();
            var interest 	   = 0;
            var endTime 	   = $("#end_at").val();
            var investTime     = $("#invest_time").val();
            var myDate         = new Date();
            var nowDate        = myDate.getFullYear()+'/'+(myDate.getMonth()+1)+'/'+myDate.getDate();

            if(productLine == 200 || (productLine == 100 && projectType == 1)) {

                //投资日期与到期日期相差天数
                var day = GetDateDiff(nowDate, endTime.replace(/\-/g, "/"), 'day');

                //var realInvestTime =  new Date(0,0,0).getDate();
                interest 	   = $.formatMoney($.toFixed(cash * (percentage / 100) * day / 365),2);
            }else{

                var publish    = $("#publish_at").val();

                var nextDate   = addMoth(endTime,1-investTime);

                var days 	   = GetDateDiff(nowDate, nextDate.replace(/\-/g, "/"), 'day');

                var diffDay    = GetDateDiff(publish.substr(0,10).replace(/\-/g, "/"), nextDate.replace(/\-/g, "/"), 'day');

                var time       = $("#invest_time").val()-1;

                var baseProfit = $.formatMoney($.toFixed((cash * (percentage / 100) / 12)),2);

                var first      = $.formatMoney($.toFixed(baseProfit * (days / diffDay)),2);

                console.log('nowDate:'+nowDate);
                console.log('days:'+days);
                console.log('diffDay:'+diffDay);
                console.log('time:'+time);
                console.log('baseProfit:'+baseProfit);
                console.log('first:'+first);

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
         * 获取预期收益
         * @param refundType
         * @param cash
         * @param profit
         * @param limitTimes
         * @param publishTime
         * @param endDate
         * @returns {*}
         */
        function getInterestObj(refundType, cash, profit, limitTimes, publishTime, endDate){

            var getInterestOjb = new getProjectInterest(refundType, cash, profit, limitTimes, publishTime, endDate);

            return getInterestOjb.interestTotal;

        }

    });
})(jQuery);
