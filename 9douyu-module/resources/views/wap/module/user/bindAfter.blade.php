@extends('wap.common.wapBase')

@section('content')
    <article>
        <section class="wap2-dd-box clearfix">
            <div class="wap2-dd-info">
                <p>
                {{ $msg }}
                </p>
                <p class="center">3s自动跳回服务号<br/>打开页面~</p>
                <i class="wap2-arrow-2"></i>
            </div>
            <div class="wap-dd-block">
                <img src="{{ env('TMPL_PARSE_STRING.__PUBLIC2__')}}/weixin/images/wap2/wap2-dd.png" class="img">
            </div>
        </section>
    </article>
@endsection

@section('jsScript')
    <script type="text/javascript">
        function close() {
            WeixinJSBridge.call('closeWindow');
        }
        setTimeout("close()", 3000);
    </script>
@endsection