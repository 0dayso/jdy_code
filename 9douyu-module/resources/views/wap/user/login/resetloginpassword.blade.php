@extends('wap.common.wapBase')

@section('title', '找回登录密码')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/novice.css') }}">
    <style>
        body{background: #f2f2f2;}
    </style>
@endsection

@section('content')
<article>
    <form action="{{ url('/doResetLoginPassword') }}" method="post" id="registerForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="phone" value="{{ $phone }}" />
        <input type="hidden" name="code" value="{{ $code }}" />
        <section class="wap2-input-group2 mt1">
            <div class="wap2-input-box">
                <span class="wap2-input-icon wap2-input-iconset"></span>
                <input type="password" name="password" id="password" autocomplete="off" placeholder="请设置新的登录密码" value="">
            </div>
        </section>
        <p class="wap2-tip wap2-tip1 error" id="tipMsg">
            @if(Session::has('errorMsg'))
                {{Session::get('errorMsg')}}
            @endif
        </p>
        <input type="hidden" name="request_source" value="wap" class="mr5">
        <section class="wap2-btn-wrap">
            <input type="submit" class="wap2-btn wap2-btn-blue2 disabled" id="submit-next" value="确认">
        </section>
    </form>
</article>
@endsection
@section('jsScript')

    <script type="text/javascript">
            $(document).ready(function(){

                //输入或者失去焦点判断
                $("input[name=password]").on({
                    keyup: function(){
                        if($.trim($("#password").val()) != '') {
                            $("#submit-next").removeClass("disabled");
                        }else{
                            $("#submit-next").addClass("disabled");
                        }
                    },
                    blur: function() {
                        $(this).keyup();
                    }
                });

            });
    </script>
@endsection
