
<div id="full-screen-slider">
    <div class="sliders_btn">
        <div class="wrap pr">
            <div id="slides_prev" class="sliders_prev"></div>
            <div id="slides_next" class="sliders_next"></div>
        </div>

    </div>
    <div class="slider-content-bj">

    </div>
    <div class="slider-content">
        <?php
            if(!empty($view_user)){
        ?>
        <div class="slider-title">
            <div class="slider-title-1"></div>
            <div class="slider-title-3"></div>
            <span class="slider-title-2">欢迎使用九斗鱼</span>
        </div>
        <p class="slider-account">账户余额: <span>{{number_format($view_user['balance'], 2) }}元</span></p>
        <p class="slider-account mb40px">累计收益: <span>{{ number_format($totalInterested, 2) }}元</span></p>
        <a class="btn btn-blue btn-block" href="/user">进入我的账户</a>
        <?php
            }else{
        ?>
        <!-- 登录前-->
        <p class="slider-num"><span>{{$indexButton['BANK_TIMES'] or 5}}</span>{{$indexButton['BANK_NOTE'] or '倍'}}</p>
        <p class="slider-text">{{$indexButton['CONTENT_WORD'] or '银行定期存款收益'}}</p>
        <a class="btn btn-blue btn-block" href="/register">{{$indexButton['BUTTON_TEXT'] or '立即注册'}}</a>
        <p class="slider-login">已有账户？<a href="/login"> 立即登录</a></p>
        <?php
            }
        ?>
    </div>
    <ul id="slides">
        @if( !empty( $bannerList ) )
            @foreach( $bannerList as $banner )
                <li style="background-image: url({{$banner['param']['file']}});  background-position: 50% 0%; background-repeat: no-repeat no-repeat;">
                    <a target="_blank" href="{{$banner['param']['url']}}">{{$banner['title']}}</a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
<div class="clearfix"></div>
