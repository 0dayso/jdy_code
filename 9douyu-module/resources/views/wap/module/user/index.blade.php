@extends('wap.common.wapBase')

@section('content')
    <article>
        <section class="wap2-input-group mt1">
            <form action="/wechat/doLoginBind" method="post">
                <div class="pl20 hidden lineheight34 mt10">
                    <div class="wap2-input-box bbd3">
                        <span class="wap2-input-icon wap2-input-icon6"></span>
                        <input type="text" class="m-input" name="username" placeholder="请输入手机号码" role-value="6-30位字母或数字"><p class="m-input-tips">手机号</p>
                    </div>
                    <div class="clear"></div>
                    <div class="wap2-input-box pwdTip">
                        <span class="wap2-input-icon wap2-input-icon5"></span>
                        <input type="password" class="m-input" name="password" role-value="6-16位的字母及数字组合"  placeholder="请输入登录密码（6-16位数字及字母）"><p class="m-input-tips">登录密码</p>
                        <input type="hidden"  name="openid" value="{{ $bindOpenid }}">
                    </div>
                </div>
                <div class="clear"><input type="hidden" name="returnUrl" value="{{ $returnUrl or null }}"></div>

                <section class="wap2-tip error m-tips">
                    <p></p>
                </section>
                <section class="wap2-btn-wrap mb2">
                    <input type="submit" class="wap2-btn" value="绑定微信账号"/>
                </section>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <p class="tr mr1">没有九斗鱼账号？<a href="/register" class="blue f13">立刻注册</a></p>
            </form>
        </section>
    </article>
@endsection

@section('jsScript')
    @include('wap.common.js')

    @if(Session::has('msg'))
        <script>
            $(document).ready(function () {
                $(this).mobileTip("{{ Session::get('msg') }}");
            });
        </script>
    @endif
@endsection
