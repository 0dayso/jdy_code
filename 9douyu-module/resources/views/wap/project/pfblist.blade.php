@extends('wap.common.wapBase')

@section('title','普付宝项目列表')

@section('content')

<article>
    <section class="Js_tab_box1">
        <div class="js_tab_content t2-list">
            <div class="Js_tab_main">
                @foreach($project as $v)
                <div class="t2-main-tab1">
                    <h3 class="t2-main-title2"><span></span>{{ $v['name'] }} {{ $v['id'] }}</h3>
                    <a href="/project/detail/{{ $v['id'] }}" class="t2-block">
                        <table class="t2-main-tab-1">
                            <tr>
                                <td width="31%" align="center">
                                    <p class="t2-project-2"><span>{{ $v['profit_percentage'] }}</span>%</p>
                                    <p class="t2-project-1">借款利率</p>
                                </td>
                                <td width="33%" align="center">
                                    <p class="t2-project-3"><span>{{ $v['format_invest_time'] }}</span>{{ $v['invest_time_unit'] }}</p>
                                    <p class="t2-project-1">期限</p>
                                </td>
                                <td>
                                    @if($v['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                        <a href="/project/detail/{{ $v['id'] }}" class="t2-pro-btn blue">立即出借</a>
                                    @elseif($v['status'] > \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                        <span class="ln-finish"></span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </a>
                </div>
                @endforeach

            </div>
        </div>
    </section>
</article>

@endsection

@section('footer')
    @include('wap.common.footer')
@endsection