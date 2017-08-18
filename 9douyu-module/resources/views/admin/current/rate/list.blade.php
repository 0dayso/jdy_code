@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">零钱计划利率</a></li>
    </ul>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>零钱计划利率列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>日期</th>
                        <th>年利率</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    @foreach($rateList as $key=>$rate)
                    <tbody>
                        <td>{{$rate['id']}}</td>
                        <td>{{$rate['rate_date']}}</td>
                        <td>{{$rate['profit_percentage']}} %</td>
                        <td>{{$rate['created_at']}}</td>
                        <td><a href="/admin/current/rate/edit/{{ $rate['id'] }}"><span class="label label-warning">编辑</span></a></td>
                    </tbody>
                    @endforeach
                </table>
                @include('admin/common/page')
            </div>
        </div>
    </div>
@endsection