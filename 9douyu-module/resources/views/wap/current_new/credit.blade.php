@extends('wap.common.wapBase')
@section('title', '债权匹配结果')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <div class="t-current1-3 w-q-mt">持有债权金额列表</div>
        <section class="wap2-input-group ">
            <div class="wap2-input-box2 ">
                <p class="fr" ><span>持有金额</span></p>
                <p>债权名称</p>
            </div>
            @forelse($data as $item)
                <div class="wap2-input-box2 ">
                    <p class="fr" ><span>{{ number_format($item->match_amount,2) }}</span> 元</p>
                    <p>{{ $item->credit_name }}</p>
                </div>
            @empty
                <div class="wap2-input-box2 ">
                    <p class="fr" ></p>
                    <p>暂无信息</p>
                </div>
            @endforelse


            {!! $data->render() !!}

        </section>
    </article>
@endsection
