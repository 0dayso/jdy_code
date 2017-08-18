@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">零钱计划限额列表</a></li>
    </ul>
    <div class="row-fluid sortable">

        <div class="box-header">
            <a href="/admin/current/limit/create" class="btn btn-primary">添加零钱计划限额</a>
        </div>

    </div>
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span3">
                <label>
                    用户手机号:   <input style="width:150px;" name="phone" value="{{$phone}}" placeholder="选择手机号" type="text">&nbsp;&nbsp;
                </label>
            </div>
            <div class="span1"><button type="submit" class="btn btn-small btn-primary">点击查询</button></div>
        </div>
    </form>
        <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>个人零钱计划额度列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>用户id</th>
                        <th>用户姓名</th>
                        <th>用户手机</th>
                        <th>额度</th>
                        <th>状态</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    @foreach($list as $key=>$val)
                    <tbody>
                        <td>{{$val['id']}}</td>
                        <td>{{$val['user_id']}}</td>
                        <td>{{ isset($val['info']['real_name']) ? $val['info']['real_name'] : "--"}}</td>
                        <td>{{isset($val['info']['phone']) ?$val['info']['phone']: "--" }}</td>
                        <td>转出:{{$val['cash']}}</br>转入:{{$val['in_cash']}}</td>
                        @if($val['status'] ==\App\Http\Dbs\Current\CashLimitDb::STATUS_ACTIVATE)
                        <td>开启</td>
                        @else
                        <td>关闭</td>
                        @endif
                        <td>{{$val['created_at']}}</td>
                        <td><a href="/admin/current/limit/edit?id={{$val['id']}}"><span class="label label-warning">编辑</span></a></td>
                    </tbody>
                    @endforeach
                </table>
                @include('admin/common/page')
            </div>
        </div>
    </div>
@endsection