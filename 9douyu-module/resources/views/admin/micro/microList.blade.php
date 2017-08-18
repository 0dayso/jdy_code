@extends('admin/layouts/default')

@section('content')

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">首页</a>
            <i class="icon-angle-right"></i>
        </li>
        <li>
            <i class="icon-eye-open"></i>
            <a href="#">微刊列表</a>
        </li>
    </ul>
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 成功提示！</h4>
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('fail'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 失败提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <a style="margin-bottom: 5px;" class="btn btn-small btn-primary" href="/admin/micro">全部列表</a>
        <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/micro/addMicro">添加微刊</a>

    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>微刊列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>


            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>微刊号</th>
                        <th>标题</th>
                        <th>链接</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($list) )
                        @foreach( $list as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['date'] }}</td>
                                <td>{{ $info['title'] }}</td>
                                <td>{{ $info['link'] }}</td>
                                <td class="center">
                                @if($info['status'] == \App\Http\Dbs\Micro\MicroJournalDb::RELEASE_STATUS_IS_CLOSED)
                                    <button class="btn btn-mini btn-primary">未开启</button>
                                @else
                                    <button class="btn btn-mini btn-success">开启</button>
                                @endif
                                </td>
                                <td>{{ $info['created_at'] }}</td>
                                <td>{{ $info['updated_at'] }}</td>
                                <td>
                                    <a href="{{$info['link'] }}" class="label label-success" target="_blank"> 预览</a>
                                    <a href="/admin/micro/editMicro?id={{$info['id']}}" class="label label-error"> 编辑</a>
                                    <a href="/admin/micro/delete?id={{$info['id']}}" class="label label-warning"> 删除</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">暂无信息</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="pagination pagination-centered" id="pagination-ajax">
                @include('scripts/paginate', ['paginate'=>$paginate])
            </div>
        </div><!--/span-->
    </div>
@section('jsScript')

@endsection
@stop