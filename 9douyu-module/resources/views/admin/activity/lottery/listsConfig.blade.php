@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">奖品列表</a></li>
    </ul>
    <div class="row-fluid sortable">

        <div class="box-header">
            <a href="/admin/lottery/addConfig" class="btn btn-primary">添加奖品</a>
        </div>

    </div>
        <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>奖品列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>奖品名词</th>
                        <th>奖品类型</th>
                        <th>数量</th>
                        <th>概率</th>
                        <th>位置</th>
                        <th>分组/等级</th>
                        <th>状态</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    @foreach($list as $key=>$val)
                    <tbody>
                        <td>{{$val['id']}}</td>
                        <td>{{$val['name']}}</td>
                        <td>{{$type[$val['type']]}}</td>
                        <td>{{$val['number']}}</td>
                        <td>{{$val['rate']}}</td>
                        <td>{{$val['order_num']}}</td>
                        <td>{{$val['group']}}</td>
                        @if($val['status'] ==\App\Http\Dbs\Activity\LotteryConfigDb::LOTTERY_STATUS_SURE)
                            <td>开启</td>
                        @else
                            <td>关闭</td>
                        @endif
                        <td>{{$val['created_at']}}</td>
                        <td><a href="/admin/lottery/editConfig?type={{$val['type']}}&id={{$val['id']}}"><span class="label label-warning">编辑</span></a></td>
                    </tbody>
                    @endforeach
                </table>
                {{--@include('admin/common/page')--}}
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div>
    </div>
@endsection