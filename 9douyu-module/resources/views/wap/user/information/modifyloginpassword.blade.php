@extends('wap.common.wapBase')

@section('title', '修改登录密码')

@section('content')
    <article>
        <form action="/user/doModifyLoginPassword" method="post" id="modifyLoginForm">
            <section class="wap2-input-group">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon3"></span>
                    <input type="password" name="oldPassword" placeholder="请输入原登录密码" class="wapForm-check-checkPassword_old" role-value="6-16位的字母及数字组合">
                </div>
            </section>
            <section class="wap2-input-group">
                <div class="wap2-input-box">
                    <span class="wap2-input-icon wap2-input-icon3"></span>
                    <input type="password" name="password" placeholder="6-16位的字母及数字组合的新登录密码" class="wapForm-check-checkPassword" role-value="6-16位的字母及数字组合">
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
                <input type="hidden" name="_token" value="{{csrf_token()}}">
            </section>
        </form>
    </article>
@endsection

@section('jsScript')
    <script src="{{ assetUrlByCdn('static/weixin/js/wap2-validate.js') }} "></script>
@endsection

