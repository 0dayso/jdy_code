@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">用户详情</a></li>
    </ul>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon user"></i><span class="break"></span>用户详情</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>用户ID</th>
                    <th>手机</th>
                    <th>可用余额</th>
                    <th>总资产</th>
                    <th>待回收本息</th>
                    <th>红包</th>
                    <th>充值总额</th>
                    <th>成功提现总额</th>
                    </thead>
                    <tr>
                        <td>{{$userInfo['id']}}</td>
                        <td>{{$userInfo['phone']}}</td>
                        <td>{{number_format($userInfo['balance'], 2)}}元</td>
                        <td>{{number_format($userInfo['total_amount'],2)}}元</td>
                        <td>{{number_format($userInfo['refundTotal'],2)}}元</td>
                        <td>
                            <div class="widget-box">
                                <div class="widget-title">
                                    <ul class="nav nav-tabs" style="margin-bottom: 0px;">
                                        <li class="active"><a data-toggle="tab" href="#useful">可使用</a></li>
                                        <li class=""><a data-toggle="tab" href="#used">已使用</a></li>
                                        <li class=""><a data-toggle="tab" href="#past">已过期</a></li>
                                    </ul>
                                </div>
                                <div class="widget-content tab-content" style="max-height: 14.0rem;">
                                    @foreach($bonusInfo as $key=>$item)
                                    @if( $key == 'useful')
                                    <div id="{{$key}}" class="tab-pane active">
                                    @else
                                    <div id="{{$key}}" class="tab-pane">
                                    @endif

                                    @if( !empty($item) )

                                    @foreach( $item as $val )
                                    <b>[{{$val['bonus_info']['label_name']}}]</b>({{$val['bonus_info']['name']}}){{$val['bonus_info']['using_desc']}}({{$val['user_status']}})<br/>
                                    @endforeach

                                    @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>{{number_format($userInfo['rechargeAmount'],2)}}元</td>
                        <td>{{number_format($userInfo['withdrawAmount'],2)}}元</td>
                    </tr>
                </table>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>用户地址</th>
                    </thead>
                    <tr>
                        <td>@if(!empty($userInfo['user_info'])){{$userInfo['user_info']['address_text']}}@endif</td>
                    </tr>
                </table>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>直投项目</th>
                    <th>零钱计划</th>
                    <th>可用余额</th>
                    <th>累计投资总额</th>
                    <th>累计充值总额</th>
                    <th>累计提现总额</th>
                    <th>累计收益</th>
                    </thead>
                    <tr>
                        <td>{{number_format($userAccount['project']['total_amount'], 2)}}元</td>
                        <td>{{number_format($userInfo['current']['total_amount'], 2)}}元</td>
                        <td>{{number_format($userInfo['balance'], 2)}}元</td>
                        <td>{{number_format($userInfo['invest_total'], 2)}}</td>
                        <td>{{number_format($userInfo['rechargeAmount'],2)}}元</td>
                        <td>{{number_format($userInfo['withdrawAmount'],2)}}元</td>
                        <td>{{number_format($userInfo['total_interest'], 2)}} 元</td>
                    </tr>
                </table>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>项目信息</th>
                    <th>回款日</th>
                    <th>回款金额</th>
                    <th>本金</th>
                    </thead>
                @if(!empty($refund))
                    @foreach($refund as $key=>$val)
                    <tr>
                        <td>{{$val['project_id']}}{{$val['project_name']}}</td>
                        <td>{{$val['times']}}</td>
                        <td>{{number_format($val['cash'],2)}}元</td>
                        <td>{{number_format($val['principal'],2)}}元</td>
                    </tr>
                    @endforeach
                @endif
                </table>
            </div>
        </div>
    </div>
@endsection