@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">资金管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">中金云数据统计</a></li>
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

                    借款人数：借款人数=还款中的保理，房抵，信贷项目个数+未到期的第三方债权行数
                    出借人数：(还款中的定期投资人数&活期账户余额大于0的用户数)去重复的
                    平均借款期限: 单个项目借款人数*项目期限）/借款总人数（在借的项目期限及人数）
                    平均借款额度：借款余额/借款人数
                    企业/法人平均借款额度：企业借款余额/企业借款人数
                    自然人平均借款额度:  （借款余额-企业借款余额）/（借款人数-企业借款人数）
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
                <h2><i class="halflings-icon user"></i><span class="break"></span>中金云数据统计</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i  class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i    class="halflings-icon remove"></i></a>
                </div>
            </div>


            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>统计日期</th>
                        <th>借款余额</th>
                        <th>未偿还利息</th>
                        <th>借款人数</th>
                        <th>出借人数</th>
                        <th>平均借款期限</th>
                        <th>平均借款额度</th>
                        <th>企业平均借款额度</th>
                        <th>自热人平均借款额度</th>
                        <th>平均借款利率</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(empty($list))
                            <tr><td class="center" colspan="9">暂无信息</td>
                        @else
                        @foreach($list as $key => $item)
                            <tr>
                                <td class="center">{{$item['stat_date']}} </td>
                                <td class="center">{{$item['surplusPrincipal']}} </td>
                                <td class="center">{{$item['surplusInterest']}} </td>
                                <td class="center">{{$item['loanUserNum']}} </td>
                                <td class="center">{{$item['investUserNum']}} </td>
                                <td class="center">{{$item['loanAvgTime']}} </td>
                                <td class="center">{{$item['loanAvgPrincipal']}} </td>
                                <td class="center">{{$item['companyAvgPrincipal']}} </td>
                                <td class="center">{{$item['personAvgPrincipal']}} </td>
                                <td class="center">{{$item['loanAvgRate']}} </td>

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