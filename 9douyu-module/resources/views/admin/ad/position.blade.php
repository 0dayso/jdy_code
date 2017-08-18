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
            <a href="#">广告位管理</a>
        </li>
    </ul>
    类型:
        <a class="btn btn-large @if( $type == 1 ) btn-success @endif" href="/admin/ad/positionList?type={{ \App\Http\Dbs\Ad\AdPositionDb::TYPE_PC }}">PC</a>
        <a class="btn btn-large @if( $type == 2 ) btn-success @endif" href="/admin/ad/positionList?type={{ \App\Http\Dbs\Ad\AdPositionDb::TYPE_WAP }}">WAP</a>
        <a class="btn btn-large @if( $type == 3 ) btn-success @endif" href="/admin/ad/positionList?type={{ \App\Http\Dbs\Ad\AdPositionDb::TYPE_APP }}">APP</a>
    <br>
    <br>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    @if(Session::has('fail'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif

    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>广告位列表</h2>
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
                        <th>创建时间</th>
                        <th>广告数量</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($list) )
                        @foreach( $list as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['name'] }}</td>
                                <td class="center">{{ $info['created_at'] }}</td>
                                <td class="center"><i class="halflings-icon signal"></i> {{ $info['ad_num'] }}</td>
                                <td class="center">
                                    <a class="label label-warning" href="/admin/ad/addAd?type={{ $type }}&position_id={{ $info['id'] }}">添加广告</a>
                                    <a class="label label-success" href="/admin/ad/adList?position_id={{ $info['id'] }}">广告列表</a>

                                    @if($info['show_img'])
                                        <a class="label label-info" target="_blank" href="/admin/ad/viewPic?path={{ $info['show_img'] }}">查看效果</a>
                                    @endif
                                    <a class="label label-important" href="/admin/ad/editPosition/{{ $info['id'] }}">
                                        编辑
                                    </a>
{{--
                                    <a class="label" href="/admin/ad/delPosition?id={{ $info['id'] }}&type={{ $type }}"  onclick="return confirm('确定删除？')">删除</a>
--}}
                                    <button class="btn btn-mini noty" data-noty-options='{"text":"请复制该代码【AdLogic::getUseAbleListByPositionId({{ $info["id"] }});】","layout":"top","type":"information"}'>
                                        <i class="halflings-icon white bell"></i> 点击调用
                                    </button>
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



        </div><!--/span-->
    </div>
    <div class="row-fluid sortable ui-sortable">
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
    </div>




@stop