@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">资金管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">账户资金统计</a></li>
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
                    平台资金：账户余额+活期余额+定期再投本金+待收收益,即九斗鱼平台的现金流
                    账户余额：截止到数据统计时间，用户账号内的余额
                    在投本金: 投资定期的本金+投资活期金额（生成数据时候的活期余额）
                    累计收益： 已经返回到用户账号内的收益,包含活期收益和定期累计收益所有的数据程序每天会自己计算一次，以上数据都是截止程序自动结算时刻的数据，统计时间设置在每天的0点开始
                    总充值金额: 截止到数据统计时间的总充值金额
                    今日充值: 统计日期内的充值成功的金额
                    总提现金额: 截止到数据统计时间的总提现金额
                    今日提现:统计日期内的提现总金额,(与实际操作提现的周期有时间差)
                    今日加息: 统计日期内的定期的出借金额,活动的奖励,零钱计划的计息
                    今日投资:统计时间内定期的投资,零钱计划的转入转出
                    今日回款: 统计时间内成功回款的金额
                    今日奖励:活动的奖励金额,目前只针对合伙人进行数据统计

                    <span style="color:red;">注:</span> 表中的 总 == 合计,定 == 定期,活 == 零钱计划, 今 == 今天(即统计日期) 投资== 今日投资 回款== 今日回款 奖励== 活动奖励

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
                        <th>账户数据</th>
                        <th>数据汇总</th>
                        <th>定期数据</th>
                        <th>零钱计划</th>
                        <th>今日加息</th>
                        <th>今日投资</th>
                        <th>充值金额</th>
                        <th>提现金额</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(empty($list))
                            <tr><td class="center" colspan="9">暂无信息</td>
                        @else
                        @foreach($list as $key => $item)
                            <tr>
                                <td class="center">{{$item['stat_date']}} </td>
                                <td class="center">
                                    平台:{{$item['total_cash']}}<br>
                                    账号:{{$item['total_balance']}}
                                </td>
                                <td class="center">
                                    本金:{{$item['investing_cash']}}<br>
                                    收益:{{$item['total_interest']}}
                                </td>
                                <td class="center">
                                    满标:{{isset($item['full_scale_cash'])? $item['full_scale_cash']:'0.00'}}<br>
                                    收益:{{$item['investRefundInterest']}}<br>
                                    本金:{{$item['regular_cash']}}
                                </td>
                                <td class="center">
                                    本金:{{$item['current_cash']}}<br>
                                    收益:{{$item['current_interest']}}<br>
                                </td>
                                <td class="center">
                                    回款:{{isset($item['refund_today']) ? $item['refund_today'] : "0.00"}}<br>
                                    活期:{{isset($item['yesterday_interest']) ? $item['yesterday_interest'] : "0.00"}}<br>
                                    奖励:{{isset($item['activity_partner']) ? $item['activity_partner'] : "0.00"}}
                                </td>
                                <td class="center">
                                    定:{{$item['invert_today']}}<br>
                                    活(入):{{isset($item['current_invest_in']) ? $item['current_invest_in']: "0.00"}}<br>
                                    活(出):{{isset($item['current_invest_out']) ? $item['current_invest_out']: "0.00"}}
                                </td>
                                <td class="center">
                                    总:{{$item['total_recharge']}}<br>
                                    今:{{$item['today_recharge']}}
                                </td>
                                <td class="center">
                                    总:{{$item['total_withdraw']}}<br>
                                    今:{{$item['today_withdraw']}}
                                </td>
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