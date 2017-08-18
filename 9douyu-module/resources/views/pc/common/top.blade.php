<div class="top">
    <div class="wrap">
        <div class="top-txt">
            客服热线：400-6686-568   服务时间：9:00~18:00
            <div class="new-hover">
                <i class="t1-icon iconfont">&#xe624;</i>
                <div class="new-code">
                    <div class="new-web-code"></div>
                </div>
            </div>
        </div>
        <div class="top-nav-wrap">
            <ul class="top-nav">
                <!--<li><a href="#">最新活动</a></li>-->
                <li>
                    <?php
                    if(empty($view_user)){
                        echo '<a href="/login">登录</a>｜';
                    }else{
                        echo '<em>您好，'. (!empty($view_user['real_name']) ? '<a href="/user">'.$view_user['real_name'].'</a>' : $view_user['phone']) . '</em><a href="/logout">［退出］</a>';
                    }
                    ?>
                </li>
                    <?php
                        if(empty($view_user)){
                    ?>
                    <li><a href="/register" >注册</a>｜</li>
                    <?php
                        }
                    ?>
                {{-- <li><a href="/content/article/newentrance">新手指引</a>｜</li> --}}
                <li><a href="/help">帮助中心</a>｜</li>
                <li class="top-nav-app"><i class="icon-t1 iconfont">&#xe623;</i>手机客户端<div class="top-nav-appimg"></div></li>

            </ul>

        </div>
        <div class="clear"></div>
    </div>
</div>