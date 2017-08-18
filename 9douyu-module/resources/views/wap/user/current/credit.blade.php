@extends('wap.common.wapBase')
@section('title', '债权列表')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>

        <section class="w-box-show mt15px pd" >
            <h3 class="w-title pb15px bb-1px mb20px pt15px"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon11.png')}}">债权列表</h3>

            <table class="wap2-table-1">
                @if($status)
                    <tr>
                        <th width="33%">借款人姓名</th>
                        <th width="33%">身份证号</th>
                        <th>出借金额(元)</th>
                    </tr>
                    @foreach($data['creditList'] as $val)
                        <tr>
                            <td>{{$val['name']}}</td>
                            <td>{{$val['id_card']}}</td>
                            <td>{{number_format($val['credit_cash'],2)}}</td>
                        </tr>
                    @endforeach
                @else
                <tr><td colspan="5">{{$msg}}</td></tr>
                @endif
            </table>
        </section>
    </article>
@endsection

