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
            }
            
            //手续费
            if($("#free_handling").val() == "1") {
                var handlingFee = 0.00;
            } else {
                var handlingFee = $.toFixed(assignPrincipal * $("#handle_rate").val());
            }
            var handlingFeeStr = handlingFee.toString() + "元";
            $(".handling-fee").html(handlingFeeStr);
            
            //待收利息
            var interestSum     = $.toFixed($("#interest_sum").val());
            var interestDay     = $.toFixed($("#interest_day").val());
            var totalDay        = $.toFixed($("#total_day").val());
            var interestAccrual = $.toFixed($("#interest_accrual").val());
            var interest = $.toFixed((interestAccrual * (assignPrincipal / assignableCash)) + (interestSum * (interestDay / totalDay) * (assignPrincipal / assignableCash)));
            
            //预计到账金额
            var predictProfit = $.toFixed((assignPrincipal * (1 - (discountRate / 100.0))) + interest - handlingFee);
            var predictProfit =  $.formatMoney(predictProfit);
            var predictProfit = predictProfit.toString() + "元";
            $(".predict-profit").html(predictProfit);
            
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
        
        $("body").on("click", ".dec", function(){
            var discountRate = parseFloat($("input[name=discount_rate]").val());
            if(isNaN(discountRate)) {
                $("input[name=discount_rate]").val(0.0);
                return false;
            }
            discountRate     = Math.max(0.0, discountRate - 0.1);
            $("input[name=discount_rate]").val(discountRate.toFixed(1));
            
            calculatePredictProfit();
        });
        $("body").on("click", ".add", function(){
            var discountRate = parseFloat($("input[name=discount_rate]").val());
            
            if(isNaN(discountRate)) {
                $("input[name=discount_rate]").val('0.0');
                return false;
            }
            discountRate    = Math.min($("#max_discount_rate").val(), discountRate + 0.1);
            $("input[name=discount_rate]").val(discountRate.toFixed(1));
            
            calculatePredictProfit();
        });
        $("body").on("keyup change blur", "input[name=assign_cash], input[name=discount_rate]", function(){
            calculatePredictProfit();
        });
        
        $("body").on("keyup change blur", "input[name=discount_rate]", function(){
            $(this).formatInput(/^$|^\d+(?:\.\d{0,1})?$/);
            if(parseFloat($(this).val()) > parseFloat($("#max_discount_rate").val())) { //不能超过最大折让率
                $(this).val($("#max_discount_rate").val());
            }
        });
        
        //提交债权转让申请
        $("body").on("click", ".apply-submit-btn", function(){
            if($(this).data("lock")) {
                return false;
            }
            
            if(!calculatePredictProfit()) {
                $(".submit-tips").html('请正确填写“申请转让本金”。');
                return false;
            }
            
            var projectId       = $("#project_id").val();
            var assignPrincipal      = $.toFixed($("input[name=assign_cash]").val());
            var discountRate    = $.toFixed($("input[name=discount_rate]").val(), 1);
            var trading_password       = $("#trading_password").val();
            
            if(assignPrincipal <= 0) {
                $(".submit-tips").html('请正确填写“申请转让本金”。');
                return false;
            }

            var obj = $(this);
            $(obj).data("lock", true); //防止重复提交
            $.ajax({
                url: '/user/credit_assign/doCreditAssign',
                type: 'POST',
                dataType: 'json',
                data: {projectId: projectId, assignPrincipal: assignPrincipal, discountRate: discountRate, trading_password: trading_password},
                success: function(result) {
                    $(obj).data("lock", null);
                    if(result.status) {
                        ajustPopup(".credit-assign-success", true);
                    } else {
                        //ajustPopup(".credit-assign-fail", true);
                        $("#trading-tip").html(result.msg);
                    }
                },
                error: function(msg) {
                    $(obj).data("lock", null);
                    console.log(msg);
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
                    $(".m-transfer").html(html);

                    $(".m-blackbg").show();
                    $(".m-transfer").show();
                },
                error: function(msg) {
                    $(obj).data("lock", null);
                    console.log(msg);
                }
            });
            return false;
        });
        
        //取消转让
        $(".cancel-ctrl").click(function(){
            if($(this).data("lock")) return false;

            $(this).data("lock", true);
            ajustPopup(".credit-assign-loading");
            var obj = $(this);
            var url = $(this).attr("href");
            $.ajax({
                url: url,
                success: function(html) {
                    $(obj).data("lock", null);
                    $(".m-cancle").html(html);

                    ajustPopup(".m-cancle");
                },
                error: function(msg) {
                    $(obj).data("lock", null);
                    console.log(msg);
                }
            });
            return false;
        });
        
        //取消债权转让（取消按钮）
        $("body").on("click", ".cancel-cancel-btn", function(){
            $(".m-cancle").hide();
            $(".m-blackbg").hide();
        });
        
        //提交取消转让
        $("body").on("click", ".cancel-submit-btn", function(){
            if($(this).data("lock")) {
                return false;
            }
            
            var projectId       = parseInt($.trim($("input[name=project_id]").val()));

            if(isNaN(projectId) || projectId == 0) {
                $(".submit-tips").html('参数错误，请刷新页面。');
                return false;
            }

            var obj = $(this);

            $(obj).data("lock", true); //防止重复提交
            $.ajax({
                url: '/user/credit_assign/doCancel',
                type: 'POST',
                dataType: 'json',
                data: {projectId: projectId},
                success: function(result) {
                    $(obj).data("lock", null);
                    if(result.status) {
                        $("#item-" + projectId).find(".cancel-ctrl").remove();
                        $(".m-cancle").hide();
                        ajustPopup(".credit-cancel-success", true);
                    } else {
                        $(".m-cancle").hide();
                        ajustPopup(".credit-cancel-fail", true);
                        $(".failmsg").html(result.msg);
                    }
                },
                error: function(msg) {
                    $(obj).data("lock", null);
                    console.log(msg);
                }
            });
        });
        
        //弹层位置适应
        var ajustPopup = function(obj, autoClose, time) {
            $(".m-blackbg").show();
            $(".m-transfer").hide();
            $(obj).show();
            var height = $(obj).height();
            var mt = -height/2 + 'px';
            $(obj).css({"margin-top":mt});
            autoClose = autoClose || false;
            time = time || 3000;
            if(autoClose) {
                setTimeout(function() {
                    $(obj).hide('slow');
                    $(".m-blackbg").hide();
                    location.reload();
                }, time);
            }
        }
        
        //弹层关闭
        $("body").on("click", ".m-closeblackbg", function(){
            $(".m-blackbg").hide();
            $(".m-transfer").hide();
        });
        
    });
})(jQuery);