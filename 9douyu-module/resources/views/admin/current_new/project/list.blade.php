@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">新版零钱计划项目</a></li>
    </ul>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>新版零钱计划项目列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>项目名称</th>
                        <th>融资总额</th>
                        <th>已出借金额</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    @if(!empty($projectList))
                    @foreach($projectList as $key=>$item)
                    <tbody>
                        <td>{{$item['id']}}</td>
                        <td>{{$item['name']}}</td>
                        <td>{{$item['total_amount']}}</td>
                        <td>{{$item['invested_amount']}}</td>
                        <td>
                            @if($item['status']==\App\Http\Dbs\CurrentNew\ProjectNewDb::STATUS_PUBLISH)
                                已发布
                                @elseif($item['status']==\App\Http\Dbs\CurrentNew\ProjectNewDb::STATUS_UN_PUBLISH)
                                未发布
                            @endif
                        </td>

                        <td>{{$item['created_at']}}</td>
                        <td><a href="/admin/currentNew/project/edit/{{ $item['id'] }}"><span class="label label-warning">编辑</span></a></td>
                    </tbody>
                    @endforeach
                    @endif
                </table>
                @if(!empty($projectList))
                    @include('admin/common/page')
                @endif
            </div>
        </div>
    </div>
@endsection