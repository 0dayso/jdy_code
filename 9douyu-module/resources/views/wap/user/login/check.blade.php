@extends('wap.common.wapBase')

@section('title', '登录')

@section('cssStyle')
    <style>
        body{background-color: #fff;}
    </style>
@endsection

@section('content')
    <article class="js-username">
        <form action="/doCheckPhone" method="post" id="loginCheckPhone">
            <section>
                <?php if(!empty($adsList)){?>
                <div class="login-banner">
                    <!-- 动态展示banner图片区域-->
                    <img src="<?php echo $adsList['img'] ;?>" alt=""/>
                </div>
                <?php }?>
            </section>
            <section class="wap2-input-group mt1">
                <div class="wap2-input-box wap2-input-box3">
                    <span class="input-txt">手机号</span>
                    <input type="tel" name="username" id="username" placeholder="请输入手机号进行登录或注册" value="">
                    <span class="wap2-delete"></span>
                </div>
            </section>
            <p class="wap2-tip wap2-tip1 error">
                @if(Session::has('msg'))
                    {{ Session::get('msg') }}
                @endif
            </p>
            <section class="wap2-btn-wrap">
                <input type="submit" class="wap2-btn wap2-btn-blue disabled" id="submit-next" value="下一步">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
            </section>
            <p class="w3-text2 wap2-tip2"> <i></i><span>九斗鱼承诺不会在任何地方泄露您的手机号</span></p>
        </form>
    </article>
@endsection

@section('jsScript')
    @include('wap.common.js')
    <script src="{{ assetUrlByCdn('/static/weixin/js/wap2/loginForms.js') }} "></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $(".wap2-delete").click(function(){
                    $("#username").val("");
                    $(this).hide();
                })
                $("#loginCheckPhone").submit(function(){

                    var lockStatus  = $("#submit-next").attr("data-lock");
                    if( lockStatus  == 'lock' ) return false;

                    var phone   =   $("#username").val();
                    if( phone=="" || !phone ) return false;
                });
            });
        })(jQuery);
    </script>
@endsection
