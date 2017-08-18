@extends('wap.common.wapBase')

@section('title', '新手狂欢鱼悦生财')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')

<meta name="format-detection" content="telephone=yes">

<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice-public.css')}}">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/novice2.css')}}">


@endsection

@section('content')
{{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <div class="re-form" data-channel="{{ $channel or null }}">
    </div>
    <div class="app11-con">
        <ul class="app11-data">
            <li>
                <span>平台注册用户：<strong>1,449,506</strong> 人</span>
            </li>
            <li>
                <span>累计出借金额：<strong>4,789,791,095</strong> 元</span>
            </li>
            <li>
                <span>帮助投资者赚取收益：<strong>84,197,903</strong> 元</span>
            </li>
        </ul>
    </div>
@if(!empty($project))
   <div class="app-project">
        <h4>明星产品</h4>
        <p class="title">{{ $project['product_line_note'] }}  <span>@if($project['type'] != 0) •  {{ $project['type'] }}月期 @endif {{ $project['id'] }}</span></p>
        <table>
          <tr>
            <td width="40%"><span>{{ $project['base_rate'] }}</span>％</td>
            <td width="20%">{{ $project['invest_time_note'] }}</td>
            <td rowspan="2">
                <a href="/project/detail/{{ $project['id'] }}" class="btn" attr-project-id="">立即出借</a>
            </td>
          </tr>
          <tr>
            <td>借款利率</td>
            <td>期限</td>
          </tr>
        </table>
    </div>
@endif
    <div class="ann2promote-work download">
        <p>客服时间：09:00-18:00</p>
        <p><span>400-6686-568</span></p>
        <p><i></i><small>理财有风险&nbsp;&nbsp;投资需谨慎</small><i></i></p>
    </div>


    <div id="checkcode1" data-img="/captcha/wx_register"  style="overflow: hidden;"></div>

@endsection

@section('jsScript')
    <script>
        var registerWord = "{{ $registerWord or '立即注册' }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{assetUrlByCdn('/static/js/common.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(".re-form form").find("input[name='aggreement']").before('<input name="_token" type="hidden" value="{{csrf_token()}}">');
            $(".re-form form").find("input[name='aggreement']").before('<input name="real_name_jump" type="hidden" value="1">');
         });
    </script>
@endsection

