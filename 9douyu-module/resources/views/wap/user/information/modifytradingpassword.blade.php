@extends('wap.common.wapBase')

@section('title', '修改交易密码')

@section('content')
    <article>
        <form action="/user/doModifyTradingPassword" method="post" id="modifyTP-form" >
            <section class="wap2-input-group">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon3"></span>
                    <input type="password" name="oldPassword" placeholder="请输入原交易密码" role-value="6-16位的字母及数字组合" class="wapForm-check-checkPassword_old">
                </div>
            </section>

            <section class="wap2-input-group">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon3"></span>
                    <input type="password" name="tradepassword" placeholder="6-16位的字母及数字组合的新交易密码" role-value="6-16位的字母及数字组合" class='wapForm-check-checkPassword'>
                </div>
            </section>

            <section class="wap2-tip error">
                <p id="error_tip">
                    @if(Session::has('msg'))
                        {{ Session::get('msg') }}
                    @endif
                </p>
            </section>
            <section class="wap2-btn-wrap">
                <input type="submit" class="wap2-btn" value="确定">
{{--                <p class="tr mr1 mt10px"><a href="{{ App\Tools\ToolUrl::getUrl('information/forgetTradingPassword') }}" class="blue f13">忘记密码</a></p>--}}

                <input type="hidden" name="_token" value="{{csrf_token()}}">
            </section>
        </form>
    </article>
@endsection

@section('jsScript')
    <script src="{{ assetUrlByCdn('static/weixin/js/wap2-validate.js') }} "></script>
@endsection
