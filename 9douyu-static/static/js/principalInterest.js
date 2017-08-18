(function($){

    $.extend({
        //等额本息每月还款额
        getMonthRefundWithBase: function(yearRate, times, principal) {
            var monthPerYear = 12;  //一年12个月    
            var ratePerMonth   = (yearRate / 100.00) / monthPerYear;   //月利率百分比
            var refundPerMonth = (principal * ratePerMonth * Math.pow((1 + ratePerMonth), times)) / (Math.pow((1 + ratePerMonth), times)-1);
            return refundPerMonth;
        },
        

        //按月付息到期还本每月还款额
        getMonthRefundOnlyInterest: function(yearRate, times, principal) {
            var monthPerYear = 12;  //一年12个月
            var refundPerMonth = $.toFixed(principal * (yearRate / 100.00) / monthPerYear);
            return refundPerMonth;
        },

        //循环投资每月还款额（本金不断增加的按月付息到期还本）
        getMonthRefundCycleInvest: function(yearRate, times, principal) {
            return $.getMonthRefundOnlyInterest(yearRate, times, principal);
        },
        
        //一次性到期还本息
       getDayRefundBaseInterest: function(yearRate, times, principal) {
        	var dayPerDays = 365;  //一年365天
            var refundPerDay = principal * (yearRate / 100.00) / dayPerDays;
            return refundPerDay;
        },



        //闪电付息
        getDayRefundFirstInterest: function(yearRate, times, principal) {
            var dayPerDays = 365;  //一年365天
            var refundPerDay = $.toFixed(principal * (yearRate / 100.00) * times/ dayPerDays);
            //var profit = Math.abs(invest)*(yearRate/100)*days/365;
            return refundPerDay;
        },

        //等额本息
        getPrincipalInterestListWithBase: function(yearRate, times, principal) {
            //每月返还本息
            var avg                        = $.getMonthRefundWithBase(yearRate, times, principal);
            //本息合计
            var principalInterest          = $.toFixed(avg * times);
            //前n-1月 每月还款本息额
            var principalInterestPerMonth  = $.toFixed(avg);
            //最后一月 本息还款额
            var principalInterestLastMonth = $.toFixed(principalInterest - (principalInterestPerMonth * (times - 1)));

            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                principalInterest: principalInterest,
                interest: $.toFixed(principalInterest - principal),
                records: {}
            };
            var restBase = principal;   //剩余本金
            var restBaseInterest = principalInterest;   //本息余额
            var refundSum = 0;          //还款总额求和
            var refundBaseSum = 0;      //返还本金求和
            var refundInterestSum = 0;  //已返回利息
            var timesList = '';         //期数信息列表内容
            var monthRefund = '';       //月还款额
            var monthPerYear = 12;      //一年12个月
            var monthRate = yearRate / monthPerYear;  //月利率
            var monthRatePercente = monthRate / 100.0;  //月利率 12% = 0.12
            for(var i = 1; i <= times; i++) {
                var preRestBase = restBase; //上一期剩余本金
                if(i != times) {    //不是最后一个月
                    monthRefund = principalInterestPerMonth;
                    restBase    = $.toFixed(restBase * (1 + monthRatePercente) - monthRefund);        //本期剩余本金
                } else {
                    monthRefund = principalInterestLastMonth;
                    restBase    = 0;   //最后一期，剩余本金为0
                }
                restBaseInterest = $.toFixed(restBaseInterest - monthRefund);                  //剩余本金利息
                var interest = Math.abs($.toFixed(restBase - (preRestBase - monthRefund)));    //本期返还利息
                var refundBase = $.toFixed(parseFloat(monthRefund) - parseFloat(interest));    //本期返还本金
                list['records'][i] = {
                    times: i,                               //月份
                    restBase: restBase,                     //本金余额
                    restBaseInterest: restBaseInterest,     //本息余额
                    timesTotal: monthRefund,                //月返本息
                    refundBase: refundBase,                 //月返本金
                    interest: interest,                     //利息
                }
            }
            return list;
        },
        //银行零钱计划存款
        getPrincipalInterestBankCurrent: function(yearRate, times, principal) {
            var rate = 0.35;    //百元年利率
            var monthPerYear = 12;      //一年12个月
            return (principal/100)  * (rate/monthPerYear) * times;
        },
        //银行定期存款
        getPrincipalInterestBankRegular: function(yearRate, times, principal) {
            var rate = 3;    //百元年利率
            var monthPerYear = 12;      //一年12个月
            return (principal/100)  * (rate/monthPerYear) * times;
        },
        //货币基金
        getPrincipalInterestFund: function(yearRate, times, principal) {
            var rate = 4.68;    //百元年利率
            var monthPerYear = 12;      //一年12个月
            return (principal/100)  * (rate/monthPerYear) * times;
        },

        //按月付息到期还本
        getPrincipalInterestListOnlyInterest: function(yearRate, times, principal) {
            //每月返还本息
            var avg                        = $.getMonthRefundOnlyInterest(yearRate, times, principal);
            //本息合计
            var principalInterest          = $.toFixed(principal + avg * times);  //本金+所有期的利息
            //前n-1月 每月还款本息额
            var principalInterestPerMonth  = $.toFixed(avg); //每期利息
            //最后一月 本息还款额
            var principalInterestLastMonth = $.toFixed(principal+principalInterestPerMonth);     //本金+一期利息

            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                principalInterest: principalInterest,
                interest: $.toFixed(principalInterest - principal),
                records: {}
            };
            var restBase = principal;   //剩余本金
            var restBaseInterest = principalInterest;   //本息余额
            var refundSum = 0;          //还款总额求和
            var refundBaseSum = 0;      //返还本金求和
            var refundInterestSum = 0;  //已返回利息
            var timesList = '';         //期数信息列表内容
            var monthRefund = avg;      //月还款额
            var monthPerYear = 12;      //一年12个月
            var monthRate = yearRate / monthPerYear;  //月利率
            var monthRatePercente = monthRate / 100.0;  //月利率 12% = 0.12
            for(var i = 1; i <= times; i++) {
                var interest        = $.toFixed(monthRefund);    //本期返还利息
                if(i != times) {    //不是最后一个月
                    restBase    = principal;        //本期剩余本金
                    restBaseInterest = $.toFixed(restBaseInterest - monthRefund);                  //剩余本金利息
                    var refundBase      = $.toFixed(parseFloat(monthRefund) - parseFloat(interest));    //本期返还本金
                    var timesTotal      = $.toFixed(monthRefund);
                } else {
                    restBase            = 0;   //最后一期，剩余本金为0
                    restBaseInterest    = 0;   //剩余本金利息
                    var refundBase      = principal;    //最后一个月返还本金
                    var timesTotal      = $.toFixed(principal + monthRefund);
                }
                list['records'][i]  = {
                    times: i,                               //月份
                    restBase: restBase,                     //本金余额
                    restBaseInterest: restBaseInterest,     //本息余额
                    timesTotal: timesTotal,     //月返本息
                    refundBase: refundBase,                 //月返本金
                    interest: interest,                     //利息
                }
            }
            return list;
        },

        //循环投资
        getPrincipalInterestListCycleInvest: function(yearRate, times, principal) {
            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                records: {}
            };
            var restBase = principal;   //剩余本金
            var restBaseInterest = principal;   //本息余额
            var refundSum = 0;          //还款总额求和
            var refundBaseSum = 0;      //返还本金求和
            var refundInterestSum = 0;  //已返回利息
            var timesList = '';         //期数信息列表内容
            var monthRefund = 0;        //月还款额
            var monthPerYear = 12;      //一年12个月
            var monthRate = yearRate / monthPerYear;  //月利率
            var monthRatePercente = monthRate / 100.0;  //月利率 12% = 0.12
            for(var i = 1; i <= times; i++) {
                var preRestBase = restBase;
                monthRefund = $.toFixed($.getMonthRefundCycleInvest(yearRate, times, restBase));
                restBaseInterest = restBase + monthRefund;
                restBase   += monthRefund;
                var interest = $.toFixed(monthRefund);
                list['records'][i] = {
                    times: i,                               //月份
                    restBase: $.toFixed(preRestBase),                  //本金余额
                    restBaseInterest: $.toFixed(restBaseInterest),     //本息余额
                    timesTotal: monthRefund,                //月返本息
                    refundBase: 0,                          //月返本金
                    interest: interest,                     //利息
                }
            }
            list['principalInterest'] = $.toFixed(restBaseInterest);
            list['interest'] = $.toFixed(restBaseInterest - principal);
            return list;
        },

       //一次性到期还本息
        getPrincipalInterestListBaseInterest: function(yearRate, times, principal) {
            //每月返还本息
            var avg                        = $.getDayRefundBaseInterest(yearRate, times, principal);

            //利息
            var interest          		   = $.toFixed(avg * times);  //本金+所有期的利息

            //本息合计
            var principalInterest          = $.toFixed(principal + interest);  //本金+所有期的利息
           
            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                principalInterest: principalInterest,
                interest: $.toFixed(principalInterest - principal),
                records: {}
            };
           
            list['records'][1]  = {
                times: 1,                               //月份
                restBase: 0,                     		//本金余额
                restBaseInterest: 0,                    //本息余额
                timesTotal: principalInterest,     		//月返本息
                refundBase: $.toFixed(principal),       //月返本金
                interest: interest,                     //利息
            }
       
            return list;
        },

        //前置付息
        getPrincipalInterestListFirstInterest: function(yearRate, times, principal){
            //每天返还本息
            var avg                        = $.getDayRefundFirstInterest(yearRate, times, principal);
            //利息
            var interest          		   = $.toFixed(avg);
            //本息合计
            var principalInterest          = $.toFixed(principal + interest);  //本金+所有期的利息

            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                principalInterest: principalInterest,
                interest: $.toFixed(principalInterest - principal),
                records: {}
            };

            list['records'][1]  = {
                times: 1,                               //月份
                restBase: 0,                     		//本金余额
                restBaseInterest: interest,             //本息余额
                timesTotal: interest,     		        //月返本息
                refundBase: $.toFixed(interest),        //月返本金
                interest: interest                      //利息
            }
            list['records'][2]  = {
                times: 2,                               //月份
                restBase: principal,                    //本金余额
                restBaseInterest: principal,            //本息余额
                timesTotal: principal,     		        //月返本息
                refundBase: $.toFixed(principal),       //月返本金
                interest: 0                             //利息
            }

            return list;

        },

        //等额本息
        getEqualPrincipalInterest: function(yearRate, times, principal) {

            var i,monthInterest;
            var principalTotal=0;
            var pcash=0;
            var monthPercent    = $.getMonthPercent(yearRate);
            var equalCash       = $.getEqualRefundCash(principal, monthPercent, times);

            var list = {
                yearRate: yearRate,
                times: times,
                principal: $.toFixed(principal),
                principalInterest: $.toFixed(principal),
                interest: 0,
                records: {}
            };

            for(i=0; i<times; i++){

                //月利息计算
                monthInterest = ( principal * monthPercent - equalCash) * Math.pow((1+monthPercent),i) + equalCash;
                monthInterest = $.toFixed(monthInterest,2);

                principalTotal += (equalCash-monthInterest);



                list['principalInterest'] += monthInterest;
                list['interest']          += monthInterest;

                pcash = $.toFixed((principal-principalTotal),2);

                if(pcash > -0.05 && i+1==times){
                    list['interest'] = list['interest'] - pcash;
                }

                list['records'][i]  = {
                    times: i,                               //月份
                    timesTotal: equalCash,                  //月返本息
                    refundBase: equalCash-monthInterest,    //月返本金
                    interest: monthInterest                //利息
                }

            }

            return list;

        },

        /**
         * desc 月利率
         * @param per
         * @returns {number}
         */
        getMonthPercent: function (per){
            return (parseInt(per)/100)/12;
        },

        /**
         * desc 获取等额本息每个月的还款额
         * @param cash 投资金额
         * @param monthPercent 月利率
         * @param timeLimit 投资期限
         * @returns {*|Number|string}
         */
        getEqualRefundCash:function (cash, monthPercent, timeLimit){
            var equalCash = (cash * monthPercent * Math.pow((1+monthPercent),timeLimit))/(Math.pow((1+monthPercent),timeLimit)-1);
            return $.toFixed(equalCash,2);
        },
        
        //计算投资本金total返还的金额
        getPrincipalInterestList: function(yearRate, times, principal, investType, key) {
            yearRate = parseFloat(yearRate);
            if(isNaN(yearRate)) return false;
            
            principal = parseFloat(principal);
            if(isNaN(principal)) return false; 
            
            times = parseFloat(times);
            if(isNaN(times)) return false;
            
            investType = '' + investType;
            var list;
            switch(investType.toLowerCase()) {
                case 'interestwithbase':
                    list = $.getPrincipalInterestListWithBase(yearRate, times, principal);
                    break;
                case 'equalinterest':
                    //list = $.getPrincipalInterestListWithBase(yearRate, times, principal);
                    list = $.getEqualPrincipalInterest(yearRate, times, principal);
                    break;
                case 'onlyinterest':
                    // debugger;
                    list = $.getPrincipalInterestListOnlyInterest(yearRate, times, principal);
                    break;
                case 'cycleinvest':
                    list = $.getPrincipalInterestListCycleInvest(yearRate, times, principal);
                    break;
                case 'baseinterest':
                    list = $.getPrincipalInterestListBaseInterest(yearRate, times, principal);
                    break;
                case 'firstinterest':
                    list = $.getPrincipalInterestListFirstInterest(yearRate, times, principal);
                    break;
                default:
                    return false;
                    break;
            }
            
            if(typeof key != 'undefined') {
                if(typeof list[key] != 'undefined') return list[key];
                else return false;
            }

            return list;
        }
    });
})(jQuery);