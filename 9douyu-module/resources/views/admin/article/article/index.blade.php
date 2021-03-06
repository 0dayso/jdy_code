@extends('admin.layouts.default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="#">{{$home}}</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">{{$title}}</a></li>
    </ul>
    <!-- start: Content -->

    <div>
        @if(Session::has('message'))
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>  <i class="icon icon fa fa-warning"></i> 提示： {{ Session::get('message') }} </h4>

            </div>
        @endif
    </div>
    <div class="row-fluid sortable ui-sortable">

        <div class="box span12">
            <div class="box-content">
                <a style="margin-left: 50px;margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/article/create">添加文章</a>

                <div class="row-fluid sortable ui-sortable">
                    <div class="box span12">
                        <div class="box-header">
                            <h2><i class="halflings-icon align-justify"></i><span class="break"></span>{{$title}}</h2>
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
                                    <th>标题</th>
                                    <th>分类ID</th>
                                    <th>浏览量</th>
                                    <th>状态</th>
                                    <th>排序</th>
                                    <th>创建时间</th>
                                    <th>修改时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(is_array($list))
                                @foreach( $list as $key => $item )
                                    <tr>
                                        <td class="center">{{ $item['id'] }}</td>
                                        <td class="center">{{ $item['title'] }}</td>
                                        <td class="center">{{ $item['category_id'] }}</td>
                                        <td class="center">{{ $item['hits'] }}</td>
                                        <td class="center">@if($item['status'] == 100 ) 未发布 @else 已发布 @endif</td>
                                        <td class="center">{{ $item['sort_num'] }}</td>
                                        <td class="center">{{ $item['created_at'] }}</td>
                                        <td class="center">{{ $item['updated_at'] }}</td>
                                        <td class="center">
                                            <a href="/admin/article/update?id={{ $item['id'] }}"><span class="label label-info">编辑</span></a>
                                            <a href="javascript:void(0)" onclick="if(confirm('确定要删除文章{{$item['id']}}吗?')){ window.location.href='/admin/article/doDelete/{{ $item['id'] }}'}" ><span class="label label-error">删除</span></a>
                                            <a href="{{env('APP_URL_PC')}}/article/{{$item['id']}}" target="_blank"><span class="label label-warning">浏览</span></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                            @include('scripts.paginate', ['paginate'=>$paginate])
                        </div>
                    </div><!--/span-->
                </div>


        </div><!--/span-->
    </div>

@stop