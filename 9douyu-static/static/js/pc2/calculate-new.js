/**
 * 使用条件：
 * 1. 依赖jQuery库
 * 2. class="calculator" 触发点击打开计算器窗口
 * 3. class="calculator" 所在标签带有属性：
 (1) data-base   初始计算总额
 (2) data-month  初始计算期数
 (3) data-rate   初始计算年利率
 4. 引用calculator模块
 *
 */
(function($){
    $(document).ready(function(){
        var projectProfitUnit, projectInvestTime, projectProfitPercentage;
        //计算器效果
        $(".calculator-box").hide();

        var timeUnit = function(investType) {
            if(investType == 'baseInterest') {
                $('.timeUnit').html("天");
            } else {
                $('.timeUnit').html("月");
            }
        };

        var currCalculator;

        //打开收益计算器
        $(".detail-calculator").click(function(){
            //$('body,html').animate({ scrollTop: 0 }, 800);
            currCalculator = $(this);
            var base        = $(this).attr("data-base");
            var month       = $(this).attr("data-month");
            var rate        = $(this).attr("data-rate");
            var refundType  = $(this).attr("data-type");
            timeUnit(refundType);
            $("input[name=base]").attr("value", base).val(base);
            $("input[name=month]").attr("value", month).val(month);
            $("input[name=yearRate]").attr("value", rate).val(rate);
            $("select[name=investType] option").each(function(i){
                $(this).attr("selected", false);
                if($(this).val() == refundType) {
                    $(this).attr("selected", true);
                    return true;
                }
            });

            $(".calculator-box").show();
            $("#calculateProfit").find("input[type=reset]").click();  //重置计算表单数据
            $("#calculateProfit").submit();
        });

        //利息记录悬停
        $("body").on("mouseover", ".calculator-data-ul li", function(){
            $(this).addClass("hover");
        }).on("mouseout", ".calculator-data-ul li", function(){
            $(this).removeClass("hover");
        });

        //柱状图块下拉
        $(".calculator-earnings p").removeClass("all");
        $(".calculator-earnings-chart").hide();
        $(".calculator-earnings span").click(function(){
            if($(".calculator-earnings span").hasClass("start")){
                $(this).removeClass("start").parent("p").removeClass("all");
                $(".calculator-earnings-chart-box").each(function(){
                    $(this).css({height: 0}).hide();
                });
                $(".calculator-earnings-chart").slideUp();
            }else{
                $(this).addClass("start").parent("p").addClass("all");
                $(".calculator-earnings-chart").slideDown();
                calculateProfit();
            }
        });

        //关闭计算器
        $(".calculator-title span").click(function(){
            $(".calculator-earnings span").removeClass("start").parent("p").removeClass("all");
            $(".calculator-earnings-chart").hide();
            $(".calculator-box").hide();
            $(".calculator-all .sum").html('0.00元');
            $(".calculator-all .rate").html('0.00%');
            $(".calculator-data-ul ul").html('');
        });

        //数据输入检测
        $("input[name=base], input[name=month]").blur(function(){
            var value = parseInt($.trim($(this).val()));
            if(isNaN(value) || value == '' || value <= 0) {
                $(this).parent().siblings("i").show();
            } else {
                $(this).val(value).parent().siblings("i").hide();
            }
        });
        $("input[name=yearRate]").blur(function(){
            var value = parseFloat($.trim($(this).val()));
            if(isNaN(value) || value == '' || value <= 0) {
                $(this).parent().siblings("i").show();
            } else {
                $(this).val(value).parent().siblings("i").hide();
            }
        });

        //计算收益，绘制表格
        var calculateProfit = function(){
            var base = parseInt($.trim($("input[name=base]").val()));
            var month = parseInt($.trim($("input[name=month]").val()));
            var yearRate = parseFloat($.trim($("input[name=yearRate]").val()));
            var flag = true;

            if(isNaN(base) || base == '' || base <= 0) {
                $("input[name=base]").parent().siblings("i").show();
                flag = false;
            }

            if(flag && (isNaN(month) || month == '' || month <= 0)) {
                $("input[name=month]").parent().siblings("i").show();
                flag = false;
            }

            if(flag && (isNaN(yearRate) || yearRate == '' || yearRate <= 0)) {
                $("input[name=yearRate]").parent().siblings("i").show();
                flag = false;
            }

            if(!flag) {
                //数据清零
                $(".calculator-all .sum").html('0.00元');
                $(".calculator-all .rate").html('0.00%');
                $(".calculator-data-ul ul").html('');
                $(".calculator-earnings-chart-box").hide();
                return false;
            }

            $("input[name=base]").parent().siblings("i").hide();
            $("input[name=month]").parent().siblings("i").hide();
            $("input[name=yearRate]").parent().siblings("i").hide();

            var investType = $("select[name=investType]").val();
            var principalInterestList = $.getPrincipalInterestList(yearRate, Math.abs(month), Math.abs(base), investType);
            var profit = parseFloat(principalInterestList['interest']);

            var profitArr = getProfitList(yearRate, month, base);//profitExchange(profit, base, month, yearRate);

            drawProfit(profitArr);

            return true;
        }
        //一年12个月
        var monthPerYear = 12;
        var profitUnit   = 100;        //100元为单位的换算率
        var rateBase      = 13.8;

        //当前收益与100元收益比转换
        var profitExchange = function(profit, base, month, rate, arr) {
            var profitPerUnit = parseFloat($.toFixed((profit / base) * profitUnit));
            arr = arr || [profitPerUnit, '15', '0.35', '3', '4.68'];   //100元年利率
            //期数收益比
            for(var i = 1; i < arr.length; i++) {
                arr[i]  = (arr[i]/monthPerYear) * month;
            }

            //利率收益比（循环出借）
            parseFloat($(currCalculator).attr("data-rate"));
            arr[1]  = (arr[1]/rateBase) * rate;   //当前项目期数100元收益比

            var exchangeRate = profit/arr[0];   //收益转换
            for(var i = 0; i < arr.length; i++) {
                arr[i] = $.toFixed(exchangeRate * arr[i]);
            }
            return arr;
        }

        var getProfitList = function(rate, month, base) {
            var investType = $("select[name=investType]").val();
            if(investType == 'baseInterest') {
                var days  = month;
                month     = days/30;
            } else {
                var days  = 0;
                for(var i=0;i<month;i++) {
                    days += getCountDays(i);
                }
            }
            var profitArr = [
                parseFloat($.toFixed($.getPrincipalInterestList(rate, month, base, 'interestwithbase', 'interest'))),
                parseFloat($.toFixed($.getPrincipalInterestList(rate, month, base, 'cycleinvest', 'interest'))),
                parseFloat($.toFixed($.getPrincipalInterestList(rate, month, base, 'onlyinterest', 'interest'))),
                parseFloat($.toFixed($.getPrincipalInterestList(rate, days,  base, 'baseinterest', 'interest'))),
                parseFloat($.toFixed($.getPrincipalInterestBankCurrent(rate, month, base))),
                parseFloat($.toFixed($.getPrincipalInterestBankRegular(rate, month, base))),
                parseFloat($.toFixed($.getPrincipalInterestFund(rate, month, base)))
            ];
            return profitArr;
        }


        var timesExchange = function(a) {
            var b = 99999999999.99,
                f = '零',
                h = '一',
                g = '二',
                e = '三',
                k = '四',
                p = '五',
                q = '六',
                r = '七',
                s = '八',
                t = '九',
                l = '十',
                d = '百',
                i = '千',
                m = '万',
                j = '亿',
                o = '',
                c = '',
                n = '',
                v = '',
                u = '';

            if(typeof a == 'undefined') {
                return ''
            }

            a = a.toString();
            if (a == '') {
                // alert('请输入数字!');
                return ''
            }
            if (a.match(/[^,.\d]/) != null) {
                // alert('请不要输入其他字符！');
                return ''
            }
            if (a.match(/^((\d{1,3}(,\d{3})*(.((\d{3},)*\d{1,3}))?)|(\d+(.\d+)?))$/) == null) {
                // alert('非法格式！');
                return ''
            }
            a = a.replace(/,/g, '');
            a = a.replace(/^0+/, '');
            if (Number(a) > b) {
                // alert('对不起,你输入的数字太大了!最大数字为99999999999.99！');
                return ''
            }
            b = a.split('.');
            if (b.length > 1) {
                a = b[0];
                b = b[1];
                b = b.substr(0, 2)
            } else {
                a = b[0];
                b = ''
            }
            h = new Array(f, h, g, e, k, p, q, r, s, t);
            l = new Array('', l, d, i);
            m = new Array('', m, j);
            n = new Array(c, n);
            c = '';
            if (Number(a) > 0) {
                for (d = j = 0; d < a.length; d++) {
                    e = a.length - d - 1;
                    i = a.substr(d, 1);
                    g = e / 4;
                    e = e % 4;
                    if (i == '0') j++;
                    else {
                        if (j > 0) c += h[0];
                        j = 0;
                        c += h[Number(i)] + l[e]
                    }
                    if (e == 0 && j < 4) c += m[g]
                }
                c += o
            }
            if (b != '') for (d = 0; d < b.length; d++) {
                i = b.substr(d, 1);
                if (i != '0') c += h[Number(i)] + n[d]
            }
            if (c == '') c = f + o;
            if (b.length < 2) c += v;
            c = u + c
            return c.replace(/^一十/, '十');
        }

        //绘制柱状图和期数利息列表
        var drawProfit = function(arr) {
            //期数利息列表
            var rowTpl = '<li><p class="w50">{times}期</p><p class="w80">{restBase}</p><p class="w80">{restBaseInterest}</p><p class="w80">{timesTotal}</p><p class="w80">{refundBase}</p><p class="bnone w50">{interest}</p></li>';
            var base = parseInt($.trim($("input[name=base]").val()));
            var month = parseInt($.trim($("input[name=month]").val()));
            var yearRate = parseFloat($.trim($("input[name=yearRate]").val()));
            var investType = $("select[name=investType]").val();
            var monthRate = yearRate / monthPerYear;  //月利率
            var principalInterestList = $.getPrincipalInterestList(yearRate, month, base, investType);
            var totalRefund = $.formatMoney(principalInterestList['principalInterest']);
            var timesList = '';         //期数信息列表内容
            var records = principalInterestList['records'];
            var n = month;
            if(investType == 'baseInterest') {
                n = 1;
            }
            for(var i = 1; i <= n; i++) {
                var record = records[i];
                //拼接每期内容
                timesList += rowTpl.replace(/{times}/, timesExchange(record['times'])).replace(/{restBase}/, $.formatMoney(record['restBase']))
                    .replace(/{restBaseInterest}/, $.formatMoney(record['restBaseInterest'])).replace(/{timesTotal}/, $.formatMoney(record['timesTotal']))
                    .replace(/{refundBase}/, $.formatMoney(record['refundBase'])).replace(/{interest}/, $.formatMoney(record['interest']));
            }
            $(".calculator-data-ul ul").html(timesList);
            //返还总额
            $(".calculator-all .sum").html(totalRefund);
            //收益百分比
            var rate = (totalRefund - base)/base * 100;
            $(".calculator-all .rate").html($.toFixed(rate)+"%");

            //柱状图
            var maxnum = Math.max.apply(null, arr);
            $(".calculator-earnings-chart-box").each(function(){
                var index = $(this).index();
                var height =arr[index]*100/maxnum  ;
                if(index < 1){
                    var left = 38;
                }else if(index == 1){
                    var left = 127;
                }else if(index == 2){
                    var left = 237;
                }else if(index == 3){
                    var left = 346;
                }else if(index == 4){
                    var left = 447;
                }else if(index == 5){
                    var left = 546;
                }else if(index == 6){
                    var left = 634;
                };

                $(this).find("span").text($.formatMoney(arr[index])+"元");
                $(this).hide().css({"height":height + "%","left":left + "px"});
                var toHeight = $(this).css("height").replace(/px/, '');
                $(this).data("toHeight", toHeight); //存储每个柱状图高度，setTimeout调取
            });

            setTimeout(function(){
                $(".calculator-earnings-chart-box").each(function(){
                    $(this).css({height: 0}).show().animate({height: $(this).data("toHeight")},200);
                });
            }, 500);
        }

        //计算触发
        $("#calculateProfit").submit(function(){
            $(".calculator-earnings span").removeClass('start').click();

            return false;
        })

        //清空输入
        $(".calculator-empty").click(function(){
            $("input[name=base]").val('');
            $("input[name=month]").val('');
            $("input[name=yearRate]").val('');
            $(".calculator-all .sum").html('0.00元');
            $(".calculator-all .rate").html('0.00%');
            $(".calculator-data-ul ul").html('');
            $(".calculator-earnings-chart-box").hide();
        })

        //选择类型
        $("select[name='investType']").change(function(){
            var val = $(this).val();
            timeUnit(val);
        })

        var getCountDays = function(n) {
            var curDate = new Date();
            /* 获取当前月份 */
            var curMonth = curDate.getMonth();
            /*  生成实际的月份: 由于curMonth会比实际月份小1, 故需加1 */
            curDate.setMonth(curMonth + 1 + parseInt(n));
            /* 将日期设置为0, 这里为什么要这样设置, 我不知道原因, 这是从网上学来的 */
            curDate.setDate(0);
            /* 返回当月的天数 */
            return curDate.getDate();
        }
    });
})(jQuery);