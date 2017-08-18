(function($){
    $(document).ready(function(){
        //菜单根据url高亮
        $(".left-sidabar a, .inner-header-right a").each(function(){
            var href = location.href.replace(/^.+\.com[^\/]*?\//i, '/');
            var end  = href.indexOf('?');
            if(end != -1) {
                var href = href.substr(0, end);
            }
            var pattern = new RegExp('^'+$(this).attr("href").replace(/\./, '\\\.') + '(?:\\\.html)?$');     //把路径中的.替换成正则的\.
            if(href.match(pattern)) {
                $(this).addClass("on");
            }
        });

        //选中dd时高亮前面的dt
        if($(".left-sidabar dd a.on").size()) {
            //获取选中dd的上一个dd或dt
            var prevObj = $(".left-sidabar dd a.on").parent().prev();

            while(prevObj.is("dt") || prevObj.is("dd")) {
                //找到dt，标记on然后退出循环
                if(prevObj.is("dt")) {
                    prevObj.find("a").addClass("on");
                    break;
                }

                //没有找到dt就继续往前找
                prevObj = prevObj.prev();
            }
        }

        //输入框中强制去掉空格
        $("input[type=text],input[type=password]").keydown(function(event){
            event = event || window.event;
            var code = event.keyCode || event.which;
            if(code == 32){
                $(this).val($(this).val());
                return false;
            }
        });

        //我的账户新版
        $(".investment-list li,.finished-list li").hover(function(){
                $(".investment-list li").removeClass("hover");
                $(this).addClass("hover");
            },function(){

            });

        //下一页
        $("#goPage").click(function() {
            var page = $(this).parent().find("input[name=p]").val();
            location.href = $(this).attr('link').replace(/_page_/, page);
        });

        //index page && list page
        $(".grid").hover(function(){
            $(this).addClass("grid-hover");
        },function(){
            $(this).removeClass("grid-hover");
        });
        
        $(".grid").click(function(){
            window.location=$(this).find("a").attr("href"); return false;
        });



        //页面自动跳转
        $(".redirect").doRedirect(3);

        //已认证提示
        $(".account-middle span,.login-info span,.account-newinfo-right span").each(function(){
            if($(this).hasClass("name-on")){$(this).attr("title","已认证")}
            else if($(this).hasClass("bank-on")){$(this).attr("title","已绑定银行卡")}
            else if($(this).hasClass("password-on")){$(this).attr("title","已设置交易密码")}
            else if($(this).hasClass("name-off")){$(this).attr("title","保证您的投资受法律保护")}
            else if($(this).hasClass("bank-off")){$(this).attr("title","方便您及时进行充值或提现")}
            else if($(this).hasClass("password-off")){$(this).attr("title","保障你的账户资金安全")};
        });



        //刷新页面时格式化所有的form表单提示宽度
        if(!$(".form-tips").hasClass('text-')) $(".form-tips").css({width: $(this).find("table:first").width()}).show();
    
        //account page graph
        $(".graph-bar").hover(function(){
            $(".graph-bar-mouseout").removeClass("graph-bar-mouseout").removeClass("graph-bar-hover");
            $(".graph-bar.graph-bar-hover").removeClass("graph-bar-hover");
            $(this).addClass("graph-bar-hover");
        },function(){
            $(this).addClass("graph-bar-mouseout");
        });

        //返回链接，点击返回上一步
        $(".back-link").click(function(){
            history.back(-1);

            //取消原有链接功能
            return false;
        });

        //详情页隔行换色
        $(".account-newbox tr:odd").addClass("table-graybg");
        $(".right-main-box table").each(function(){
            $(this).find("tr:first").addClass("table-graybg");
            $(this).find("tr:even").not("tr:first").addClass("table-lightgraybg");
        });
        $(".detail-tab table").each(function(){
            $(this).find("tr:even").addClass("table-lightgraybg")
        });
        //detail hover
        $(".detail-icon").hover(function(){
            $(this).find("span").show();
        },function(){
            $(this).find("span").hide();
        });

        //我要融资页面表单、登录页面、修改交易密码、设置交易密码、修改登录密码、修改手机号码 、找回登录密码
        $("#modifyTP-form input,#setTP-form input,#modifyLoginForm input,#modifyPhone input,#setNewPhone input[type=text],#setNewPhone input[type=password],#resetLP-form input[type=text],#resetLP-form input[type=password],#setEmail input,#forgetTP-form input[type=text],#forgetTP-form input[type=password],input[name=tradingPassword],input[name=rand_cash]").focus(function(){
            $(this).addClass("focus").parents("tr").find(".tips-msg").show();
            $(this).removeClass("wrong").parents("tr").find(".tips-msg").removeClass("tips-success tips-error");
        }).blur(function(){
            $(this).removeClass("focus").parents("tr").find(".tips-msg").hide();

        });

        $("#login-form input").focus(function(){
            $(this).parent(".btn-box").addClass("focus").siblings(".tips-msg").show();
            $(this).parent(".btn-box").removeClass("wrong").siblings(".tips-msg").removeClass("tips-success tips-error");
        }).blur(function(){
            $(this).parent(".btn-box").removeClass("focus").siblings(".tips-msg").hide();
        });

        $("#financing-form input").focus(function(){
            $(this).addClass("focus");
        }).blur(function(){
            $(this).removeClass("focus");
        });
        $("#financing-form textarea").focus(function(){
            $(this).addClass("focus")
        }).blur(function(){
            $(this).removeClass("focus")
        });

        //表单
        $("#setTP-form,#modifyTP-form,#doWithdrawForm,#doRechargeForm,#modifyLoginForm,#resetLP-form,#setNewPhone,#forgetTP-form,#investConfirm").submit(function(){
            if($(this).data("lock")) return false;
            var flag    = true;
            $.each($(this).find("input[type=text], input[type=password]"), function(){
                if($.trim($(this).val()) == '' || $(this).data("error")) {
                    flag = false;
                }
            });

            if(!flag){
                return false;
            } else {
                $(this).data("lock", true);
            }
        });
        //login form
        $("#login-form").submit(function(){
            var textArr = {
            'username': '用户名不能为空',
            'password': '密码不能为空'

            }

            var failFlag = false;
            $("input[type=text],input[type=password]").each(function(){
                if($(this).val() == '') {
                    $(this).btnShowTips(textArr[$(this).attr("name")]);
                    failFlag = true;
                    return false;
                }
            });

            if(failFlag) return false;

            if($(this).data("lock")) return false;
            var flag    = true;
            $.each($(this).find("input[type=text], input[type=password]"), function(){
                if($.trim($(this).val()) == '' || $(this).data("error")) {
                    flag = false;
                }
            });

            if(!flag){
                return false;
            } else {
                $(this).data("lock", true);
            }
        });
        //修改登录密码  #modifyTP-form input[name=oldPassword],
        $("#setTP-form input[name=oldPassword],#modifyLoginForm input[name=password],#modifyLoginForm input[name=oldPassword],#modifyLoginForm input[name=password]").blur(function(){
            var password = $.trim($(this).val());
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0) {
                return false;
            }

            if(!password.match(pattern)){
                $(this).tableShowTips('6到16位的字母及数字组合');
            }else {
                $(this).tableShowTips('','success');
            }
        });
        //修改交易密码、设置交易密码、修改手机号码、修改登录密码
        $("#setTP-form input[name=password],#modifyPhone input[name=password],#resetLP-form input[name=password],input[name=tradingPassword]").blur(function(){
            var password = $.trim($(this).val());
            //var pattern = /^[0-9]{6}$/i;
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0) {
                return false;
            }

            if(!password.match(pattern)){
                $(this).tableShowTips('6到16位的字母及数字组合');
            }else {
                $(this).tableShowTips('','success');
            }
        });
        //修改交易密码
        $("#modifyTP-form input[name=password]").blur(function(){
            var password = $.trim($(this).val());
            //var pattern = /^[0-9]{6}$/i;
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0) {
                return false;
            }
            if(!password.match(pattern)){
                $(this).tableShowTips('6到16位的字母及数字组合');
            }else if($.trim($("input[name=password]").val()) == $.trim($("input[name=oldPassword]").val())){
                $("input[name=password]").tableShowTips('新密码不能与原密码一样');
            }else{
                $(this).tableShowTips('','success');
            }
        });
        //设置交易密码
        $("#setTP-form input[name=password]").blur(function(){
            var password = $.trim($(this).val());
            //var pattern = /^[0-9]{6}$/i;
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0) {
                return false;
            }
            if(!password.match(pattern)){
                $(this).tableShowTips('6到16位的字母及数字组合');
            }else if($.trim($("input[name=password]").val()) == $.trim($("input[name=oldPassword]").val())){
                $("input[name=password]").tableShowTips('交易密码不能与登录密码一样');
            }else{
                $(this).tableShowTips('','success');
            }
        });
        var checkPasswordConfirm = function () {
             if($.trim($("input[name=password]").val()) == '') {
                return false;
            };
            if( $.trim($("input[name=password]").val()) != $.trim($("input[name=password2]").val())){
                $("input[name=password2]").tableShowTips('两次密码输入不一致');
            }else {
                $("input[name=password2]").tableShowTips('','success');
            }
        }
        $("#setTP-form input[name=password2],#modifyTP-form input[name=password2],#modifyLoginForm input[name=password2],#resetLP-form input[name=password2]").blur(function(){
            if($(this).val() != ''){
                checkPasswordConfirm();
            }else{
                return false;
            }
        });
        //登录页面
        $("#login-form input[name=password]").blur(function(){
            var password = $.trim($(this).val());
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0) {
                return false;
            }

            if(!password.match(pattern)){
                $(this).btnShowTips('6到16位的字母及数字组合');
            }else {
                $(this).btnShowTips('','success');
            }
        });
        //购买债权转让时的交易密码
        $("#investConfirm input[name=tradingPassword]").blur(function(){
            var password = $.trim($(this).val());
            //var pattern  = /^[0-9]{6}$/i;
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if(password.length == 0){
                return false;
            }
            if(!password.match(pattern)){
                $(this).tableShowTips('6到16位的字母及数字组合');
                return false;
            }else{
                $(this).tableShowTips('','success');
            }
        });
        $("#login-form input[name=username]").blur(function(){
            var username = $.trim($(this).val());
            var pattern = /^[0-9a-z]{6,30}$/i;
            if(username.length == 0) {
                return false;
            };
            if(!username.match(pattern)) {
                $(this).btnShowTips('6-30位字母或数字','error');
                return false;
            }else {
                $(this).btnShowTips('','success');
            };
        });

        //推荐好友
        $("input[name=copy]").click(function(){
            $("input[name=textlink]").get(0).select();
        });
        $("input[name=textlink]").click(function(){
            $("input[name=textlink]").get(0).select();
        });
        $("input[name=textlink]").mouseover(function(){
            $("input[name=textlink]").get(0).select();
        });


        //首页登录状态验证
        /*$(".login-info span").hover(function(){
            $(this).find("i").show();
        },function(){
            $(this).find("i").hide();
        }); */
        $(".new-username input,.new-password input,.verify-code input,.new-phone input").focus(function(){
            $(this).addClass("hover");
        }).blur(function(){
            $(this).removeClass("hover");
        });
        //项目列表页面条件选择滑动效果
        if($(".project-list-condition-box").size()){
            $(".project-list-condition-box li").click(function(){
                $(this).addClass("on").siblings().removeClass("on");
            });
            var $projectBg=$("<span class='project-title-bg'></span>");
            $projectBg.insertBefore($(".project-list-condition-box ul"));
            var $projectLi=$(".project-list-condition-box li");
            var firstLeft   = $(".project-list-condition-box ul").position().left;

            var $hovered_pos=$(".project-list-condition-box li.on").position(".project-list-condition-box");
            function init () {
                if($hovered_pos) {
                        $projectBg.css('right',$hovered_pos);
                        var index = 0;
                        /* search for the selected menu item*/
                        for(i=0; i<$projectLi.length; i++) {
                            if($(projectLi[i]).hasClass('on')) {
                                index = i;
                            }
                        }

                }

                /*mouseenter funtion*/
                $projectLi.each(
                    function( intIndex ){
                        $(this).on (
                            "mouseenter",
                                function(event){
                                    var x = $(this).position('.project-list-condition-box');
                                    x = x.left;
                                    $projectBg.stop();
                                    $projectBg.animate({
                                        left: firstLeft+x
                                      },600);
                                }
                            );

                        }
                 );

                /* mouseout function*/
                $projectLi.each(
                    function( intIndex ){
                        $(this).on (
                            "mouseleave",
                                function(event){
                                    $projectBg.stop();
                                    var x = 2014;   //鼠标离开，背景框恢复到右边消失的状态
                                    /*if($hovered_pos) {
                                        x = $hovered_pos;
                                        var index = 0;
                                        for(i=0; i<$projectLi.length; i++) {
                                            if($($projectLi[i]).hasClass('on')) {
                                                index = i;
                                            }
                                        }


                                    } */
                                $projectBg.animate({
                                        left:x
                                      },600);
                                }
                            );
                        }
                 );
            }
            /* call init our function*/
            init();
        }
    });
})(jQuery);