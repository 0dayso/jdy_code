@if(isset($userStatus) && $userStatus == false )
<span id='none-user'>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="include-page">
    <div class="re-form" data-channel="{{ $channel or null }}">
         <div class="expand" id="expand"></div>
    </div>
    <div class="download-box">
        <div class="ann2promote-download">
            <span></span>
            <p>九斗鱼值得托付的互联网金融平台</p>
        <a href="{{$package or '/zt/appguide.html' }}">立即下载</a>

        </div>
    </div>
</div>

<div id="checkcode1" data-img="/captcha/wx_register"  style="overflow: hidden;"></div>
<span>
@endif
<!--script src="{{ assetUrlByCdn('/static/weixin/js/jquery-1.9.1.min.js') }}"></script-->
