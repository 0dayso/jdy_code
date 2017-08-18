@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">资金管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">资金流水统计</a></li>
    </ul>


    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2>
                    <i class="halflings-icon align-justify"></i>
                    <span class="break"></span>数据文字说明
                    <a href="#" class="btn-minimize" title="点击查看"><i class="halflings-icon chevron-down"></i></a>
                    <a href="#" class="btn-close" title="点击关闭"><i class="halflings-icon remove"></i></a>
                </h2>
            </div>
            <div class="box-content" style="display: none;">
                <pre>
                    所有的数据程序每天会自己计算一次，以上数据都是截止程序自动结算时刻的数据，统计时间设置在每天的0点开始,按照统计期为时间点
                    定期回款:用户投资定期项目的所有已经回款的数据
                    定期投资:用户投资定期的所有金额数据
                    零钱转入:用户主动行为进行投资零钱计划的数据
                    零钱转出:用户主动行为进行的零钱的转出
                    充值、提现:所有成功的充值、提现的金额
                    提现失败:新系统充值失败的数据统计从2016-09-22开始统计,网银提现失败的数据
                    取消提现:用户提现申请后,取消的部分金额
                    活动奖励:新系统中所有活动的奖励,包含合伙人,加币,奖励加息 (不包含加息券和红包)
                    债券转让:用户符合债转规则,并进行债转的金额
                    投资债转:用户投资变现宝项目
                    系统扣除:平台对用户的资金进行扣除的总计
                </pre>
            </div>
        </div><!--/span-->
    </div>
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span4">
                <label>
                    查询时间: <input name="start_time" style="width:150px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="{{ @isset($start_time) ? $start_time :""  }}" placeholder="开始时间" type="text"> －
                    <input name="end_time" style="width:150px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="{{ @isset($end_time) ? $end_time :""  }}" placeholder="结束时间" type="text">
                </label>
            </div>
            <div class="span2">

                <input type="checkbox" name="export" value="1">勾选导出 &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-small btn-primary">点击查询</button> &nbsp;&nbsp;&nbsp;&nbsp;

            </div>
        </div>
    </form>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>账户资金统计列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>


            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>统计日期</th>
                        @foreach($eventTitle as $key=> $title)
                        <th>{{$title}}</th>
                        @endforeach
                        <th>账号余额</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(empty($list))
                            <tr><td class="center" colspan="9">暂无信息</td>
                        @else
                        @foreach($list as $key => $item)

                            <tr>
                            @foreach($item as $val)
                                <td class="center">{{$val}} </td>
                            @endforeach
                                @unset($item['date'])
                                <td class="center">{{round(array_sum($item),2)}}</td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop