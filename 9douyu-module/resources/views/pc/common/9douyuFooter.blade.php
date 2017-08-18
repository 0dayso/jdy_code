
<div class="footer-bottom js-bottom">
    <div class="wrap hidden">
        <div class="footer-copyright">
            <p>星果时代信息技术有限公司版权所有   京ICP备16011752号-1   京公网安备11010502025626 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <p>Copyright© 2017 9douyu. All Right Reserved&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                法律顾问：万商天勤律师事务所&nbsp;&nbsp;&nbsp;&nbsp; 网贷有风险，投资需谨慎
            </p>
        </div>
        <div class="footer-checkwebsite">
            <ul>
                {{--<li><a href="/home/util/cnnic" target="_blank" rel="nofollow" class="footer-icon"></a></li>--}}
                {{--<li><a href="http://webscan.360.cn/index/checkwebsite?url=www.9douyu.com" target="_blank" rel="nofollow" class="footer-icon1"></a></li>--}}
                {{--<li><a href="https://trustsealinfo.verisign.com/splash?form_file=fdf/splash.fdf&dn=www.9douyu.com&lang=zh_cn" target="_blank" rel="nofollow" class="footer-icon2"></a></li>--}}
                {{--<li><a href="http://gawa.bjchy.gov.cn/websearch/" target="_blank" rel="nofollow" class="footer-icon3"></a></li>--}}
                <li><a href="http://www.itrust.org.cn/Home/Index/itrust_certifi?wm=1A00257T3R" target="_blank" rel="nofollow" class="footer-icon4"></a></li>
            </ul>
        </div>
    </div>
</div>
<style type="text/css">
    .t-add-footer{
        position: fixed;bottom: 0; width: 100%;
    }
</style>
<script type="text/javascript">
    $(function(){
        if($(window).height()>$(document.body).height()){
            $(".js-bottom").addClass("t-add-footer")
        }else{
            $(".js-bottom").removeClass("t-add-footer")
        }
        window.onresize = function () {
            if($(window).height()>$(document.body).height()){
                $(".js-bottom").addClass("t-add-footer")
            }else{
                $(".js-bottom").removeClass("t-add-footer")
            }
        }
    });
</script>
<!--引入cnzz统计-->
@if( formalEnvironment() == true||formalEnvironment()== false)
    <div style="display: none;">
<script type="text/JavaScript">
	var cnzz_tag = document.createElement('script');
	cnzz_tag.type = 'text/javascript';
	cnzz_tag.async = true;
	cnzz_tag.charset = 'utf-8';
	cnzz_tag.src = 'https://s11.cnzz.com/z_stat.php?id=1259180573&async=1';
	var cnzz_root = document.getElementsByTagName('script')[0];
	cnzz_root.parentNode.insertBefore(cnzz_tag, cnzz_root);
</script>
    </div>
    <!--Google Analytics-->
    <div style="display: none;overflow: hidden;">
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-97207782-1', 'auto');
            ga('send', 'pageview');
        </script>
    </div>
@endif
<!-- 在线客服 -->
@include('pc.common/qqService')

<script src="{{ assetUrlByCdn('static/js/pc2.js') }} "></script>
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            $(".qq-qun").hoverShow(".qun-content");
            $(".menu-item").hoverShow(function(){
                $(".menu-item.cur").removeClass("cur");
                $(this).addClass("cur").children(".menu-content").show();
            }, function(){
                $(".menu-item.cur").removeClass("cur");
                $(this).children(".menu-content").hide();
            });
            $(".other").hoverShow(function(){
                $(this).addClass("hover");
                $(".other-content").show();
            }, function(){
                $(this).removeClass("hover");
                $(".other-content").hide();
            });
        });
    })(jQuery);
    (function($){
        $(document).ready(function(){
            $(".web-data-reserve i").poshytip({showTimeout: 1,});
            $(".web-fisher-tab li").on('click',function(){
                var index = $(".web-fisher-tab li").index(this);
                $(this).addClass('on').siblings().removeClass('on');
                $('.web-fisher').eq(index).show().siblings('.web-fisher').hide();
            });

        });
    })(jQuery);
    $(".web-data").click(function(){
    });

    (function($){

        $(".new-web-data dl i").poshytip({showTimeout: 1,});
// 滚动公告
        jQuery(".web-notice-txt").jCarouselLite({
            auto:1000,
            speed:1200,
            visible:1,
            stop:$(".web-notice-txt"),
            btnGoOver:true,
            scroll:1,
            vertical:true
            //circular:false
        });

// 投资PK弹层显示时，body滚动条禁止，弹层消失，滚动条正常
        function ishidden(id){
            if($(id).size() && $(id).is(':visible')){
                $("body").css({"overflow":"hidden"});
            }else {
                $("body").css({"overflow":"auto"});
            }
        };

        $(document).ready(function(){

            var tag = 0;
            if(tag == 0){
                $(".x-pop-wrap2").hide();
            }else{
                $(".x-pop-wrap2").show();
            }


            // pop
            ishidden(".x-pop-wrap2");
            $(".x-close").click(function(){
                $(".x-pop-wrap2").hide();
                ishidden(".x-pop-wrap2");
            });

            $(".x-pop-mask1").click(function(){
                $(".x-pop-wrap2").hide();
                ishidden(".x-pop-wrap2");
            })


            $(".project-detail-info-q").poshytip({showTimeout: 1,});
            Groupbuy_Calculation_Time_Init();
            $(".homeproject-box,.project-9xs-box").hover(function(){
                $(this).addClass("on")
            },function(){
                $(this).removeClass("on")
            });

            $(".riskcalc-right").hover(function(){
                $(this).addClass("hover")
            },function(){
                $(this).removeClass("hover")
            });

            $(".report img").each(function(){
                $(this).load(function(){  //图片加载
                    var h = parseInt($(this).css("height").replace(/px/, ''));
                    if(h < 60){
                        var mt = (60 - h)/2 + "px";
                        $(this).addClass("loaded").css("margin-top",mt);
                    }
                });
                if(!$(this).hasClass("loaded")) {  //图片缓存
                    var h = parseInt($(this).css("height").replace(/px/, ''));
                    if(h < 60){
                        var mt = (60 - h)/2 + "px";
                        $(this).css("margin-top",mt);
                    }
                }
            });
            $(".riskcalc-right").click(function(){
                window.location='/zt/statistics'; return false;
            });

            $(".project-current-box").click(function(){
                window.location=$(this).find("a").attr("href"); return false;;
            });
        });
    })(jQuery);


</script>

