@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">资金管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">交易记录</a></li>
    </ul>


    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span6">
                <label>
                    用户:   <input style="width:120px;" name="phone" value="{{ @isset($params['phone']) ?$params['phone'] :""  }}" placeholder="输入手机号" type="text">&nbsp;&nbsp;
                    类型:   <select id="selectSource" name="type" style="width:120px;">
                            <option value="">请选择类型</option>
                            @foreach($transactionType as $key=> $name)
                                <option value="{{$key}}" {{ @isset($params['type'])&&$params['type'] ==$key ? "selected" : '' }} >{{$name}}</option>
                            @endforeach
                            </select>
                    时间: <input name="start_time" style="width:120px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="{{ @isset($params['start_time']) ?$params['start_time'] :""  }}" placeholder="开始时间" type="text"> － <input name="end_time" style="width:120px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="{{ @isset($params['end_time']) ?$params['end_time'] :""  }}" placeholder="结束时间" type="text">
                </label>
            </div>
            <div class="span3">
                <button type="submit" class="btn btn-small btn-primary">点击查询</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="export" value="1">勾选导出
            </div>
        </div>
    </form>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>交易记录</h2>
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
                        <th>ID</th>
                        <th>用户ID</th>
                        <th>手机号</th>
                        <th>姓名</th>
                        <th>时间</th>
                        <th>类型</th>
                        <th>变前的账户金额</th>
                        <th>变更金额</th>
                        <th>变更后账户金额</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(empty($list))
                            <tr><td class="center" colspan="9">暂无信息</td>
                        @else
                            @foreach($list as $key => $item)
                                <tr>
                                    <td class="center">{{$item['id']}} </td>
                                    <td class="center">{{$item['user_id']}}</td>
                                    <td class="center">{{$item['phone']}}</td>
                                    <td class="center">{{$item['name']}}</td>
                                    <td class="center">{{$item['created_at']}}</td>
                                   {{-- <td class="center">{{$item['note']}}</td>--}}
                                    <td class="center">{{$item['event_id_label']}}</td>
                                    <td class="center">{{$item['balance_before']}}</td>
                                    <td class="center">{{$item['balance_change']}}</td>
                                    <td class="center">{{$item['balance']}} </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="center" colspan="7"></td>
                                <td class="center">资金变动合计:</td>
                                <td class="center">{{$summary['balance_change_summary']}}</td>
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
