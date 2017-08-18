<div class="w3-footer" id="footer-nav">
    <div class="w3-footer-1 ">
        <?php
        //登陆态下 浏览器端 真实登陆端
        $realClinet = \App\Tools\ToolAppLoginWap::getBrowserRealClient();
        if($realClinet == \App\Http\Logics\RequestSourceLogic::SOURCE_PUFUBAO){
        ?>
            <a href="/pfb/projectList" class="{{ $projectActive or '' }}"><i  class="nav-1 {{ $projectActive or '' }}" ></i>项目</a>
            <a href="/user" class="{{ $userActive or '' }}"><i  class="nav-2 {{ $userActive or '' }}"></i>资产</a>
            <a href="/information" class="{{ $noticeActive or '' }}"><i  class="nav-3 {{ $noticeActive or '' }}"></i>更多</a>

        <?php

         }else{

        ?>
            <a href="/" class="{{ $indexActive or '' }}" ><i class="nav-home {{ $indexActive or '' }}"></i>首页</a>
            <a href="/project/lists" class="{{ $projectActive or '' }}"><i  class="nav-1 {{ $projectActive or '' }}" ></i>项目</a>
            <a href="/user" class="{{ $userActive or '' }}"><i  class="nav-2 {{ $userActive or '' }}"></i>资产</a>
            <a href="/information" class="{{ $noticeActive or '' }}"><i  class="nav-3 {{ $noticeActive or '' }}"></i>更多</a>

        <?php
        }
        ?>
    </div>
</div>
<script>

    var readyRE = /complete|loaded|interactive/;
    var ready = window.ready = function (callback) {
        if (readyRE.test(document.readyState) && document.body) callback()
        else document.addEventListener('DOMContentLoaded', function () {
            callback()
        }, false)
    }
    //rem方法
    function ready_rem_nav() {
        var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
        var _html = document.getElementsByTagName('html')[0];

        //新算法
        ///*
        var fontSize = 0;
        if(view_width > 640) {
            fontSize = 640 / 16;
        } else if(screen.height>500){
            fontSize = view_width / 17;
            if(screen.height<670 || screen.height>740){
                fontSize = view_width / 18;
            }
            if(screen.height==1280 && screen.width==800){
                fontSize = view_width / 21;
            }
        } else {
            fontSize = 16;
        }

        var innerPagerFontSize = (Math.min(640, view_width)/ 16);

        var resize = (innerPagerFontSize / fontSize);

        if(typeof ready_rem == 'undefined') {   //如果已经有ready_rem，不变更html变化
            _html.style.fontSize = fontSize.toFixed(2) + "px";
        }


        var isHome = $(".isHome").size(); //class="{$isHome|default=''}"		$isHome = "isHome";
        if(!isHome) {	//内页进来（处理导航缩放）
            _html.style.fontSize = innerPagerFontSize.toFixed(2) + "px";
            var remAttr = ["height", "line-height", "font-size", "margin-top", "margin-left", "margin-right", "margin-button"];
            $("#footer-nav, #footer-nav *").each(function(){
                var obj = $(this);
                $.each(remAttr, function(i){
                    if((remAttr[i] == "height" || remAttr[i] == "line-height") && (obj.is("a") || obj.is(".w3-footer-1"))) return true;
                    var attrValue = obj.css(remAttr[i]);
                    if(typeof attrValue != 'undefined') {
                        attrValue = parseFloat(attrValue.replace(/px/g, ''));
                        if(attrValue != 0) {
                            obj.css(remAttr[i], (attrValue/resize).toFixed(2) + "px");
                        }
                        //console.log([obj, remAttr[i], attrValue, (attrValue/resize).toFixed(0)]);
                    }
                });
            });
            var remAttr = ["background-position", "width", "background-size"];
            $("#footer-nav i").each(function(){
                var obj = $(this);
                $.each(remAttr, function(i){
                    var attrValue = obj.css(remAttr[i]);
                    if(remAttr[i] == "background-position" || remAttr[i] == "background-size") {
                        if(attrValue.indexOf("px") != -1) {
                            var position = obj.css(remAttr[i]).split(" ")
                            $.each(position, function(i){
                                position[i] = (parseFloat(position[i].replace(/px/, '')) / resize).toFixed(2) + "px";
                            });
                            obj.css(remAttr[i], position.join(' '));
                            //console.log([obj, remAttr[i]], position.join(' '));
                        }
                    } else if(remAttr[i] == "width") {
                        attrValue = parseFloat(attrValue.replace(/px/g, ''));
                        if(attrValue != 0) {
                            obj.css(remAttr[i], (attrValue/resize).toFixed(2) + "px");
                        }
                    }
                });
            });
        }
        // */

    }
    ready(function () {
        ready_rem_nav();
    });

</script>
@if( formalEnvironment() == true)
<!--引入cnzz统计-->
<div style="display: none;">
<script>
	var cnzz_tag = document.createElement('script');
	cnzz_tag.type = 'text/javascript';
	cnzz_tag.async = true;
	cnzz_tag.charset = 'utf-8';
	cnzz_tag.src = 'https://s4.cnzz.com/z_stat.php?id=1259206554&async=1';
	var cnzz_root = document.getElementsByTagName('script')[0];
	cnzz_root.parentNode.insertBefore(cnzz_tag, cnzz_root);
</script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?bc62ca5d897247faea9a91bbc9f4e046";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</div>
@endif
