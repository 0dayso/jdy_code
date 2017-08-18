@extends('wap.common.wapBase')

@section('title','回款计划')

@section('content')
<block name="content">
    <article>
        <section class="w-fff-bj">
            <div class="w-hk-1 mt15px">
                <span class="w-hk-2">共{{ $num }}个项目</span>
                <span class="fr mr30px">总计 {{ $total }}元</span></div>
                @if( isset($lists['refund']) && !empty($lists['refund']))
                    @foreach( $lists['refund'] as $v )
                    <div class="wap-back-list">
                        <a href="javascript:void(0)"  class="bbd3">
                            <table>
                                <tr>
                                    <td width="30%">{{  $v['name'] }}<br>(未回款)</td>
                                    <td width="20%" class="w-bule-color">{{ $v['cash'] }} 元</td>
                                    <td width="50%" class="w-bule-color">下期回款: {{ $v['date'] }}</td>
                                </tr>
                            </table>
                        </a>
                    </div>
                    @endforeach
                @endif
                @if( isset($lists['refunded']) && !empty($lists['refunded']))
                    @foreach( $lists['refunded'] as $v )
                    <div class="wap-back-list">
                        <a href="javascript:void(0)"  class="bbd3">
                            <table>
                                <tr>
                                    <td width="30%">{{  $v['name'] }}<br>(已回款)</td>
                                    <td width="20%" class="w-bule-color">{{ $v['cash'] }} 元</td>
                                    <td width="50%" class="w-bule-color">回款时间: {{ $v['date'] }}</td>
                                </tr>
                            </table>
                        </a>
                    </div>
                    @endforeach
                @endif
        </section>
    </article>
</block>
@endsection
