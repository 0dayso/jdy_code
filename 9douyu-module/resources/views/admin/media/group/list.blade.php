@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">自媒体管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">分组列表</a></li>
    </ul>

    <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/media/group/create">添加分组</a>
    <br/>


    <div style="margin-bottom: 20px"></div>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>分组列表</h2>
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
                        <th>序号</th>
                        <th>分组名称</th>
                        <th>描述</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($paginate['data']) && !empty($paginate['data'])){
                    foreach($paginate['data'] as $key => $item){
                    ?>
                    <tr>
                        <td class="center">{{$item['id']}} </td>
                        <td class="center">{{$item['name']}}</td>
                        <td class="center">{{$item['desc']}}</td>
                        <td class="center">{{$item['created_at']}}</td>
                        <td class="center">{{$item['updated_at']}}</td>

                        <td class="center">
                            <a href="/admin/media/group/edit/{{ $item['id'] }}"><span class="label label-success">编辑</span></a>
                            <a href="/admin/media/group/delete/{{ $item['id'] }}"><span class="label label-warning">删除</span></a>
                        </td>

                    </tr>
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                </table>
                @include('scripts/paginate', ['paginate'=>$paginate])

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop