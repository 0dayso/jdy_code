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
            <a href="#">广告列表</a>
        </li>
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
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>{{ $positionInfo['name'] }}广告列表</h2>
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
                        <th>名称</th>
                        <th>创建人</th>
                        <th>发布时间</th>
                        <th>结束时间</th>
                        <th>显示状态</th>
                        <th>显示类型</th>
                        <th>文字</th>
                        <th>排序ID</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($adList) )
                        @foreach( $adList as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['title'] }}</td>
                                <td>{{ $info['manage_id'] }}</td>
                                <td>{{ $info['publish_at'] }}</td>
                                <td>{{ $info['end_at'] }}</td>
                                <td class="center">
                                    @if( $info['publish_at'] > \App\Tools\ToolTime::dbNow() ) 未上线
                                    @elseif( $info['end_at'] < \App\Tools\ToolTime::dbNow() ) <a class="label">已到期</a>
                                    @else <a class="label label-success">正常</a>
                                    @endif
                                </td>
                                <td class="center">
                                    @if( $info['param']['show_type'] == 1 )
                                        <a class="label label-success" target="_blank" href="/admin/ad/viewPic?path={{ $info['param']['path'].$info['param']['name'] }}">查看图片</a>
                                    @else 文字 @endif </td>
                                <td class="center">{{ $info['param']['word'] }}</td>
                                <td class="center">{{ $info['sort'] }}</td>

                                <td class="center">
                                    <a class="label label-important" href="/admin/ad/editAd/{{ $info['id'] }}">编辑</a>
                                    <a class="label" href="/admin/ad/delAd?id={{ $info['id'] }}"  onclick="return confirm('确定删除？')">删除</a>
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
                @include('scripts/paginate', ['paginate'=>$paginate])

            </div>



        </div><!--/span-->
    </div>
    {{--<div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>添加广告位</h2>
            </div>


            <div class="box-content">
                <form class="form-horizontal" method="post" action="/admin/ad/addPosition" enctype="multipart/form-data">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">分类</label>
                            <div class="controls">
                                <select name="type">
                                    <option value="1">PC</option>
                                    <option value="2">WAP</option>
                                    <option value="3">APP</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">名称</label>
                            <div class="controls">
                                <input type="text" class="span4 typeahead" name="position_name" >
                                <span class="help-inline">请尽量写清楚介绍</span>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="fileInput">预览图</label>
                            <div class="controls">
                                <div class="uploader" id="uniform-fileInput">
                                    <input class="input-file uniform_on" id="fileInput" type="file" name="display_img">
                                    <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                    <span class="action" style="-webkit-user-select: none;">Choose File</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>

            </div>



        </div><!--/span-->
    </div>--}}




@stop