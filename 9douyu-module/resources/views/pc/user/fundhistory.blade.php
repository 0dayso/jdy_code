@extends('pc.common.layout')

@section('title', '资金流水')

@section('content')

    <div class="m-myuser">
        <!-- account begins -->
        @include('pc.common.leftMenu')

        <div class="m-content mb30">
            <!--选项卡1导航-->
            <ul class="m-tabnav1">
                <li class="m-addstyle"><a href="/user/fundhistory">资金明细</a></li>
                <li class="ml-1"><a href="/user/bankcard">银行卡管理</a></li>
            </ul>
            <div class="m-showbox pt40">
                <!--选项卡1内容1-->
                <div class="m-tabtitle">

                    <div class="m-tabbox">
                        <div>
                            <table class="table table-theadbg table-textcenter mb26px">
                                <thead>
                                <tr>
                                    <td>交易描述</td>
                                    <td>收支</td>
                                    <td>可用余额</td>
                                    <td>时间</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if( !empty($list) )
                                    @foreach( $list as $fund )
                                        <tr>
                                            <td>{{ $fund['note'] }}</td>
                                            <td class="m-bluefont">{{ $fund['balance_change'] }}</td>
                                            <td>{{ $fund['balance'] }}</td>
                                            <td>{{ $fund['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td>暂无信息</td></tr>
                                @endif
                                </tbody>
                            </table>

                            <div class="web-page">
                                @include('scripts/paginate', ['paginate'=>$paginate])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection
