@extends('wap.common.wapBase')

@section('title','回款计划')

@section('content')
<block name="content">
    <article>
        @if(!empty($lists))
            @foreach($lists as $year => $v)
            <div class="center mt15px mb8px"><span class="w-hk">{{ $year }}年</span></div>
            <section class="w-fff-bj">
                @foreach($v as $month => $item)
                    <div class="wap2-input-box bbd3 pd0px">
                        <a href="/RefundPlan/byDate/{{$item['date']}}/{{ $item['total'] }}/{{$item['projectNum'] or 1 }}" class="w-hk2">
                            <span class="w-hk1"><em class="gray-title-bj w-hk1">{{$item['date']}}月</em></span>
                            <span class="w-bule-color ml20px">共{{$item['projectNum'] or 1 }}个项目</span>
                            <span class="fr mr30px">共 {{ $item['total'] }} 元</span>
                            <span class="wap2-arrow-1"></span>
                        </a>
                    </div>
                @endforeach
            </section>
            @endforeach
                @else
        <section class="w-fff-bj">
            <div class="w-no-hk">
                <p class="center"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-logo.png')}}" class="no-img"></p>
                <p class="w-zw">暂无回款</p>
                
            </div>
        </section>
                @endif
    </article>
</block>
@endsection

