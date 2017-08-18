@extends('wap.common.wapBase')

@section('title', '验证身份')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
@endsection

@section('content')
    <div class="family-tel">{{ $familyRole }}</div>
    <form name="form1" id="form1" method="post" action="/family/doVerify">
    <section class="family-tel1">
        <div class="family-tel1-1 bbd3">
            <input type="text" name="real_name" placeholder="请输入对方姓名" value="{{ old('real_name') }}">
        </div>
        <div class="family-tel1-1 bbd3">
            <input type="text" name="identity_card" placeholder="请输入对方身份证号" value="{{ old('identity_card') }}">
        </div>
        <div class="family-tel1-1">
            <input type="text" name="card_number"  placeholder="请输入银行卡号" value="{{ old('card_number') }}">
        </div>

    </section>
        <section class="wap2-tip error m-tips f-c" id="m-tips" style=" width: auto; display:block">@if(Session::has('error')) {{ Session::get('error') }} @endif</section>
    {{--@if(Session::has('errors'))
        <section class="wap2-tip error m-tips f-c" id="m-tips" style=" width: auto; display:block">{{ Session::get('errors') }}</section>
    @endif--}}
    <section class="family-tel2">
        <input type="button" value="完成" class="family-btn">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
    </section>
    </form>
</block>
@endsection

@section('jsScript')
    <script>
        (function($){
            $('.family-btn').click(function(){
                var real_name     = $("input[name='real_name']").val();
                var identity_card = $("input[name='identity_card']").val();
                var card_number   = $("input[name='card_number']").val();
                if(real_name == ''){
                    $('.m-tips').text('请输入对方姓名');
                    return false;
                }
                if(identity_card == ''){
                    $('.m-tips').text('请输入对方身份证号');
                    return false;
                }
                if(card_number == ''){
                    $('.m-tips').text('请输入对方银行卡号');
                    return false;
                }
                $('.family-btn').attr('disabled',true);
                $("#form1").submit();
                /*$.ajax({
                    url : '/family/doVerify',
                    type: 'POST',
                    dataType: 'json',
                    data: {'identity_card': $("input[name=identity_card]").val(),'real_name':$("input[name=real_name]").val(),'card_number':$("input[name=card_number]").val()},
                    success : function(data) {
                        if(data.status===false) {
                            $('.m-tips').text(data.msg);
                            $('.family-btn').attr('disabled',false);
                            return false;
                        } else {
                            location.href='/family/accountList'
                        }
                    }
                });*/
            });
        })(jQuery);

    </script>
@endsection

