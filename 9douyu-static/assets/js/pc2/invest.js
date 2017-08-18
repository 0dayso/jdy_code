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
			}else if(refundType == 40){//等额本息的算法
				equalInvestSum();
			}else{
				calInvestSum();
			}
		});


		//红包下拉选择
		$(".bonus-items").change(function(){
			var thisOption = $(this).find("option").eq($(this).get(0).selectedIndex);
			var refundType 	= $("input[name=refund_type]").val();				//还款方式

			var rate = thisOption.attr("data-rate");

			var min  = thisOption.attr("data-min");

			var using = thisOption.attr("data-using");

			if(thisOption.val() > 0){

				$("#min_money").val(min);

				$("#using_range").val(using);
			}else{
				$("#min_money").val(0);

				$("#using_range").val('');
			};

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
			};
			if(refundType == 40){
				equalInvestSum();
			}else{
				calInvestSum();
			}

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
			};

			var balance = $.toFixed($.trim($("#balance").val()));

			if(cash > balance){
				$(".project-tips").html('账户余额不足，请充值');
				return false;
			};

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
			};

			$(".project-tips").html("投资预期收益"+ $.formatMoney($.toFixed(profit, 2))+"元"+msg);
		};

		//由于 data.toLocaleDateString()safair8.0浏览器有兼容问题 故自己手动拼接日期 DateDemo()
		function DateDemo(){
			var d, s = "";
			d = new Date();
			s += d.getFullYear() + "/";
			s += (d.getMonth() + 1) + "/";
			s += d.getDate();
			return(s);
		}
		//到期还本息
		function getInterest( cash, percentage) {

			var productLine    = $("#product_line").val();
			var projectType    = $("#project_type").val();
			var interest 	   = 0;
			var endTime 	   = $("#end_at").val();
			var investTime     = $("#invest_time").val();

			if(productLine == 200 || (productLine == 100 && projectType == 1)) {

				//投资日期与到期日期相差天数
				var day = GetDateDiff(DateDemo(), endTime.replace(/\-/g, "/"), 'day');

				//var realInvestTime =  new Date(0,0,0).getDate();
				interest 	   = $.formatMoney($.toFixed(cash * (percentage / 100) * day / 365),2);
			}else{

				var publish    = $("#publish_at").val();

				var nextDate   = addMoth(endTime,1-investTime);

				var days 	   = GetDateDiff(DateDemo(), nextDate.replace(/\-/g, "/"), 'day');

				var diffDay    = GetDateDiff(publish.substr(0,10).replace(/\-/g, "/"), nextDate.replace(/\-/g, "/"), 'day');

				var time       = $("#invest_time").val()-1;

				var baseProfit = $.formatMoney($.toFixed((cash * (percentage / 100) / 12)),2);

				var first      = $.formatMoney($.toFixed(baseProfit * (days / diffDay)),2);

				interest   	   = parseFloat(baseProfit * time) + parseFloat(first);
			};
			return interest;
		};

		function addMoth(d,m){
			var ds=d.split('-');
			d=new Date( ds[0],ds[1]-1+m,ds[2])
			return d.toLocaleDateString().match(/\d+/g).join('-')
		}

		/**
		 * desc: 减少月数
		 * @param d
		 * @param m
         * @returns {string}
         */
		function delMonth(d,m){
			var ds=d.split('-');
			d=new Date( ds[0],ds[1]-m-1,ds[2])
			return d.toLocaleDateString().match(/\d+/g).join('-');
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

		/**
		 * 闪电付息数据验证
		 * @returns {boolean}
         */
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
			};

			//金额不能为空
			if($.trim($("input[name=cash]").val()) == '') {
				$(".project-tips").html('请输入投资金额');
				return false;
			};

			//投资金额不为空 且 不小于最小投资金额
			if(isNaN(invest) || invest < investMin) {
				$(".project-tips").html('最低投资'+$.formatMoney(investMin)+'元');
				return false;
			};

			//闪电付息项目 投资金额 必须为 最小金额的倍数
			if(refundType == 30 && invest%investMin > 0){
				$(".project-tips").html('投资金额必须为'+investMin+'的倍数');
				return false;
			};

			//投资金额不可大于账户余额
			if(invest > balance){
				$(".project-tips").html('账户余额不足，请充值');
				return false;
			};

			//投资额不能超出项目剩余融资额
			if(invest > leftAmount) {
				$(".project-tips").html('投资额不能超出项目剩余融资额');
				return false;
			};

			//投资后项目剩余可投金额 不能小于 最小投资金额
			if(leftAmount - invest < investMin && leftAmount - invest != 0) {
				$(".project-tips").html('投资后可投金额不能小于'+investMin);
				return false;
			};

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

/***************************************************[等额本息计算]***************************************************/

		/**
		 * desc 等额本息计算
		 * @returns {boolean}
         */
		function equalInvestSum(){

			var invest  	= $.toFixed($.trim($("input[name=cash]").val()));	//投资金额

			var per  = $("#percentage").val();//项目利率

			var cash = parseFloat($.trim($("#cash").val()));//投资金额

			var couponMoney = parseFloat($("#bonus_money").val()); //红包金额

			var rate   = $("#bonus_profit").val(); //红包利率

			var balance 	= $.toFixed($.trim($("#balance").val()));			//账户余额
			var leftAmount 	= $.toFixed($.trim($("#leftAmount").val()));		//项目剩余金额

			var projectType    = $("#project_type").val(); //项目类型
			var interest 	   = 0; //利息
			var endTime 	   = $("#end_at").val(); //项目完结日
			var investTime     = $("#invest_time").val();//投资期限
			var publish    = $("#publish_at").val();//发布时间

			var invest_time = DateDemo().replace(/\//g, "-");
			var profit = 0;

			cash    += couponMoney;

			var msg  = '';

			if(cash <= 0){

				$(".project-tips").html('请输入投资金额');

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
			profit = getEqualInterest(cash,per,invest_time, investTime, publish, projectType);
			if(rate > 0){

				var rateProfit = getEqualInterest(cash,rate,invest_time, investTime, publish, projectType);

				msg   += ',额外加息奖励'+$.formatMoney($.toFixed(rateProfit, 2))+'元';
			}

			$(".project-tips").html("投资预期收益"+ $.formatMoney($.toFixed(profit, 2))+"元"+msg);

		}

		//function getEqualInterest(equalInterest){
		//	var rateProfit=0;
		//	for(var i=0;i<equalInterest.length;i++){
		//		rateProfit += equalInterest[i];
		//	}
		//	return rateProfit;
		//}

		/**
		 * 等额本息利息列表
		 * @param cash
		 * @param percent
		 * @param invest_time
		 * @param time_limit
		 * @param publish
         * @param projectType
         * @returns {Array}
         */
		function getEqualInterest(cash, percent, invest_time, time_limit, publish, projectType){
			//console.log('right:',cash, percent, invest_time, time_limit, publish, projectType);
			var refund_time;//回款时间节点
			var day_cmp;
			var m = 0;
			var i;
			var interestRecord = []; //每月利息记录数组
			var principalTotal = 0;  //每月本金记录数组
			var principalArr   = [];
			var monthInterest;
			var rateProfit=0;

			var refundPlanArr = getRefundPlan(time_limit);
			//console.info('right:',refundPlanArr);

			//统计等额本息利息列表
			for(i=0;i<refundPlanArr.length;i++){
				refund_time = refundPlanArr[i];
				day_cmp = GetDateDiff(refund_time.replace(/\-/g, "/"),invest_time.replace(/\-/g, "/"), 'day');
				if(day_cmp >= 0){
					time_limit--;
					continue;
				}
				if(percent>0){
					//月利率
					var monthPercent = getMonthPercent(percent);
					//每月还款额
					var equalCash = getEqualRefundCash(cash, monthPercent, time_limit);
					var noEqualCash = getNoEqualRefundCash(cash, monthPercent, time_limit);
				}
				break;
			}
			//console.info('right:',monthPercent,equalCash);

            //循环把利息放入数组中
			for(i=0;i<refundPlanArr.length;i++){
				refund_time = refundPlanArr[i];
				day_cmp = GetDateDiff(refund_time.replace(/\-/g, "/"),invest_time.replace(/\-/g, "/"), 'day');
                //投资时间大于回款时间节点
				if(day_cmp >= 0){
					continue;
				}
				m++;
				//月利息计算
                monthNoInterest = ( cash * monthPercent - noEqualCash) * Math.pow((1+monthPercent),m-1) + noEqualCash;
				monthInterest = $.toFixed(monthNoInterest,2);
				principalTotal += (equalCash-monthInterest);
				//console.log(monthInterest);
				//首月利息
				if(m == 1){

					var days 	   = GetDateDiff(DateDemo(), refund_time.replace(/\-/g, "/"), 'day');

					publish = publish.substr(0,10).replace(/\-/g, "/");
					var date = new Date(publish);
					var beforeTimes = date.pattern("yyyy-MM-dd");

					if(i != 0){
						beforeTimes = refundPlanArr[i-1];
					}

					var diffDay = GetDateDiff(beforeTimes.replace(/\-/g, "/"),refund_time.replace(/\-/g, "/"), 'day');

					monthInterest  = $.toFixed(monthNoInterest * (days / diffDay),2);

					var noForInterest = $.toFixed(monthNoInterest * (days / diffDay));

				}

				interestRecord.push(monthInterest);

				principalArr.push(equalCash-monthInterest);

			}

			for(var i=0;i<interestRecord.length;i++){
				rateProfit += interestRecord[i];
			}

			if(((cash-principalTotal)) > -0.05){
				rateProfit = rateProfit - (cash-principalTotal);
			}

			return rateProfit;

		}

		/**
		 * desc 月利率
		 * @param per
		 * @returns {number}
         */
		function getMonthPercent(per){

			return (parseInt(per)/100)/12;
		}

		/**
		 * desc 获取等额本息每个月的还款额
		 * @param cash 投资金额
		 * @param monthPercent 月利率
		 * @param timeLimit 投资期限
		 * @returns {*|Number|string}
         */
		function getEqualRefundCash(cash, monthPercent, timeLimit){
			var equalCash = (cash * monthPercent * Math.pow((1+monthPercent),timeLimit))/(Math.pow((1+monthPercent),timeLimit)-1);
			return $.toFixed(equalCash,2);
		}

		function getNoEqualRefundCash(cash, monthPercent, timeLimit){
			return (cash * monthPercent * Math.pow((1+monthPercent),timeLimit))/(Math.pow((1+monthPercent),timeLimit)-1);
		}

		/**
		 * desc 获取回款计划的时间列表
		 * @param type
		 * @returns {string}
         */
		function getRefundPlan(type){
			var end_at 	   = $("#end_at").val();
            var times = [];
			var endTime = '';
			var nextMonthDate = '';
			for(var i= 0; i < type;i++){
				endTime = delMonth(end_at,i);
				nextMonthDate = getNextMonthDate(end_at,endTime);
				times.push(nextMonthDate);
			}
			return times.reverse();
		}

		/**
		 * desc 获取下一个回款日
		 * @param startTime
		 * @param endTime
		 * @returns {*}
         */
		function getNextMonthDate(startTime, endTime){

			var s=startTime.split('-');
			var e=endTime.split('-');
			if(parseInt(s[2]) != parseInt(e[2])){
				endTime = getTheDate(endTime,-e[2]);
			}
			return endTime;
		}

		/**
		 * desc 获取当前时间加几天或者减几天
		 * @param date
		 * @param day
         * @returns {string}
         */
		function getTheDate(date,day)
		{
            //可以加上错误处理
			var a = new Date(date)
			a = a.valueOf()
			a = a + day * 24 * 60 * 60 * 1000
			a = new Date(a);
			var m = a.getMonth() + 1;
			if(m.toString().length == 1){
				m='0'+m;
			}
			var d = a.getDate();
			if(d.toString().length == 1){
				d='0'+d;
			}
			return a.getFullYear() + "-" + m + "-" + d;

		}


/***************************************************[等额本息计算]***************************************************/


		/**
		 * 交易密码验证
		 * @returns {boolean}
         */
		function checkPasswordPre(){
			var passwordObj = $("#tradingPassword");
			var passwordV = $.trim(passwordObj.val());
			if(passwordV==''){
				passwordObj.attr("placeholder", "请填写交易密码");
				return false;
			};
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
			};
		};

	});
})(jQuery);
