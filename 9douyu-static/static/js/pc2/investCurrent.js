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

        //零钱计划期加息券
        $("#bonus_id").change(function(){
            var rate = $(this).find("option:selected").attr('data-value');

            if(rate > 0){
                $("#useBonus").show();
                $("#rate").val(rate);
            }else{
                $("#useBonus").hide();
                $("#rate").val('');
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
            var investMax   = $.toFixed($("input[name=investMax]").val());
            var userBalance   = $.toFixed($("input[name=userBalance]").val());
            var leftAmount   = $.toFixed($("input[name=leftAmount]").val());
            var currentRate   = $.toFixed($("input[name=currentRate]").val());
            var rate          = $.toFixed($("input[name=addRate]").val());

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

            var planInterest = invest*(currentRate+rate)/365/100;
            $(".project-tips").html('当前投额预期每日收益'+$.formatMoney(planInterest.toFixed(2))+'元');

            return true;
        }

        $("#investForm").submit(function() {
            if(!$(this).data('canSubmit')) {
                var checkRs = calInvestSum();
                if (checkRs) {
                    var cash = $.toFixed($.trim($("input[name=cash]").val()));
                    var id = $("#project_id").val();
                    var bonusId = $.toFixed($("#bonus_id").val());
                    var rate    = $.toFixed($("input[name=addRate]").val());


                    $.ajax({
                        url: '/current/checkAjax',
                        type: 'POST',
                        dataType: 'json',
                        data: {cash: cash, id: id, bonusId:bonusId, addRate:rate},
                        success: function (data) {
                            if (data.status) {
                                $("#investForm").data('canSubmit', true).submit();
                            } else {
                                if (data.code == 2001) {
                                    $(".user-balance").html("用户已安全退出");
                                    $(".redirect-link").attr("href", $("#loginUrl").val()).html("登录");
                                } else {
                                    $(".project-tips").html(data.msg);
                                }
                            }
                        },
                        error: function (msg) {

                        }
                    });
                }
                return false;
            }

        });
    });
})(jQuery);

