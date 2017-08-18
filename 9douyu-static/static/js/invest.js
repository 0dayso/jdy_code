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
		});

		function calInvestSum(){
		    if($.trim($("input[name=cash]").val()) == '') {
		        $(".project-tips").html('请输入投资金额');
		        return false;
		    }

		    var invest  = $.toFixed($.trim($("input[name=cash]").val()));
		    var investMin   = $.toFixed($("input[name=investMin]").val());
		    if(isNaN(invest) || invest < investMin) {
		        $(".project-tips").html('最低投资'+$.formatMoney(investMin)+'元');
		        return false;
		    }
		    var leftAmount = $.toFixed($.trim($("#leftAmount").val()));
		    if(invest > leftAmount) {
		        $(".project-tips").html('投资额不能超出项目剩余融资额');
		        return false;
		    }
		    var investLeftMin   = $.toFixed($("input[name=investLeftMin]").val());
		    var leftAmount = $.toFixed($.trim($("#leftAmount").val()));
		    if(leftAmount - invest < investLeftMin && leftAmount -  invest > 0) {
		        $(".project-tips").html('投资后可投金额不能小于'+investLeftMin);
		        return false;
		    }
            var projectWay = $("input[name=project_way]").val();

		    var month = parseInt($.trim($("#invest_month").val()));
		    if(projectWay == 60) {
		    	month = $("input[name=invest_time]").val();
		    }
		    var yearRate = parseFloat($.trim($("#profit_rate").val()));
            if(!$("input[name=refunded_times]").size()) {
                var principalInterestList = $.getPrincipalInterestList(yearRate, Math.abs(month), Math.abs(invest), $("#refundType").val());
            } else {
                var principalInterestList = calCreditAssignInvestInterest(yearRate, Math.abs(month), Math.abs(invest), $("#refundType").val());
            }
		    var profit = principalInterestList['interest'];

		    if(projectWay == 60) {
	    	    var publishTime    = $("input[name=publish_time]").val();
	    	    var realInvestTime =  new Date(0,0,0).getDate();
	    	    var day            = GetDateDiff(publishTime.substr(0,10).replace(/\-/g, "/"), new Date().toLocaleDateString(),'day');
	    	    var records        = principalInterestList.records;
	    	    $(records).each(function(i){
	    	            //首期利息重新计算
	    	            if(i == 0) {
	                          var interest      = records[i+1]["interest"];
	                          var realInterest  = interest / realInvestTime * (realInvestTime - day);
	                          var diffInterest  = interest - realInterest;
	                          profit           -= diffInterest;
	    	            }
	    	    });
		    }

		    $(".project-tips").html("当前投资额预期可收益"+ $.formatMoney($.toFixed(profit, 2))+"元");
		}

        function calCreditAssignInvestInterest(yearRate, month, invest, refundType) {
            var refundedTimes   = $.toFixed($("input[name=refunded_times]").val(), 0);
            var discountRate    = $.toFixed($("input[name=discount_rate]").val(), 1);
            var investPrincipal = $.toFixed(invest / (1 - (discountRate / 100)));
            var principalInterestList = $.getPrincipalInterestList(yearRate, month, investPrincipal, refundType);

            var interest = 0;
            var investInterestDay = $.toFixed($("input[name=investInterestDay]").val(), 0);
            var totalDay = $.toFixed($("input[name=totalDay]").val(), 0);

            var records = principalInterestList['records'];
            $.each(records, function(i) {
                if(i <= refundedTimes) {    //已回款部分不再计算
                    return true;    //continue;
                }

                if(i == (refundedTimes + 1)) {  //原债权人待收利息扣除
                    interest += records[i].interest * investInterestDay / totalDay;
                    return true;
                }

                interest += records[i].interest;
            });

            principalInterestList['interest'] = $.toFixed(interest, 2);

            return principalInterestList;
        }

        //红包下拉选择
        $(".bonus-items").change(function(){
            var thisOption = $(this).find("option").eq($(this).get(0).selectedIndex);
            if('experience' == thisOption.attr("data-type")) {
                var name = 'userExperienceId';
                var cash =  parseInt($.trim(thisOption.attr('cash')));
                $("input[name=cash]").val(cash).blur().prop("readOnly",true);
            } else {
                var name = 'bonusId';
                $("input[name=cash]").prop("readOnly",false);
            }

            if(!!thisOption.attr("data-rate") && thisOption.attr("data-rate") != '0.0') {
                $("#profit_rate").val(parseFloat($("#profit_rate").attr("data-value")) + parseFloat(thisOption.attr("data-rate")));
            } else {
                $("#profit_rate").val($("#profit_rate").attr("data-value"))
            }

            $(this).attr("name", name);
            calInvestSum();
        });

        //红包单选按钮选择
        $("input[name=bonusId]").click(function(){
            var checked = $(this).prop("checked");

            if(checked && !!$(this).attr("data-rate") && $(this).attr("data-rate") != '0.0') {
                $("#profit_rate").val(parseFloat($("#profit_rate").attr("data-value")) + parseFloat($(this).attr("data-rate")));
            } else {
                 $("#profit_rate").val($("#profit_rate").attr("data-value"))
            }

            calInvestSum();
        });

		$("input:radio[name='userExperienceId']").click(function(){
				var cash =  parseInt($.trim($(this).attr('cash')));
		   	   	$("input[name=cash]").val(cash).blur().prop("readOnly",true);
		   	   	$("input[name=bonusId]").prop("checked",false);

		});

		$("input:radio[name='bonusId']").click(function(){
		   	$("input[name=cash]").blur().prop("readOnly",false);
		   	$("input[name=userExperienceId]").prop("checked",false);
		});

		$("#investForm").submit(function() {
		    if($(this).data("investLock")) return false;
		    if(!$(this).data("canSubmit")) {
		        if($.trim($("input[name=cash]").val()) == '') {
		            $(".project-tips").html('请输入投资金额');
		            return false;
		        }
		        var invest           = $.toFixed($.trim($("input[name=cash]").val()));
		        var leftAmount       = $.toFixed($.trim($("#leftAmount").val()));
		        var investMin        = $("input[name=investMin]").val();
                if($("input[name='bonusId']").size()) {
                    var bonusId          = $("input[name='bonusId']:checked").val();
                    var userExperienceId = $("input[name=userExperienceId]:checked").val();
                } else {
                    var bonusId          = $("select[name='bonusId']").val();
                    var userExperienceId = $("select[name=userExperienceId]").val();
                }

		        if(isNaN(invest)) {
		            $(".project-tips").html('投资金额必须为数值');
		            return false;
		        }

		        if(invest < investMin) {
		            $(".project-tips").html('投资额最低' + $.formatMoney(investMin) + '元');
		            return false;
		        }

		        if(invest > leftAmount) {
		            $(".project-tips").html('投资额不能超出项目剩余融资额');
		            return false;
		        }

                var investLeftMin   = $.toFixed($("input[name=investLeftMin]").val());
		        if(leftAmount - invest < investLeftMin && leftAmount -  invest > 0) {
		            $(".project-tips").html('投资后可投金额不能小于' + investLeftMin);
		            return false;
		        }

		        $.ajax({
		            url     : '/invest/project/checkInvestAjax',
		            type    : 'POST',
		            dataType: 'json',
		            data    : {balance:invest,bonusId:bonusId,userExperienceId:userExperienceId},
		            success : function(result) {
		                if(result.status) {
		                   $("#investForm").data("canSubmit", true);
		                   $("#investForm").submit();
		                } else {
		                    if(!result.login) {
		                        $(".user-balance").html("用户已安全退出");
		                        $(".redirect-link").attr("href", $("#loginUrl").val()).html("登录");
		                    } else {
		                        $(".user-balance").html("账户余额不足，可用余额为：" + $.formatMoney(result.balance) + "元");
                                $.shake($(".user-balance"), 'fontred', 4);
		                        $(".redirect-link").attr("href", $("#rechargeUrl").val()).html("充值");
		                    }
		                }
		            },
		            error   : function(msg) {
		            }
		        });

		        //取消表单的提交，由成功的Ajax触发提交
		        return false;
		    } else {
		        $(this).data("investLock", true);
		    }
		});
    });
})(jQuery);
