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
            <a href="#">批量列表</a>
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
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 失败提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif

    <div class="row-fluid sortable ui-sortable">
        <div class="box" >
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>批量操作</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content" style="display: none;">
                <form class="form-horizontal" method="post" action="/admin/batch/add" enctype="multipart/form-data">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">类别</label>
                            <div class="controls">
                                <select name="type">
                                    <option value="phone">短信</option>
                                    <option value="wx">微信</option>
                                    <option value="app">APP</option>
                                    <option value="bonus">红包/加息券</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">内容</label>
                            <div class="controls">
                                <input type="text" class="span8 typeahead" name="content" placeholder="推送填写内容,红包加息券填写红包ID,请核对正确!">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="fileInput">文件</label>
                            <div class="controls">
                                <div class="uploader" id="uniform-fileInput">
                                    <input class="input-file uniform_on" id="fileInput" type="file" name="file">
                                    <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                    <span class="action" style="-webkit-user-select: none;">Choose File</span>
                                </div>                                ( * 要求,TXT格式,一行一个手机号/或者用户id )

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">备注</label>
                            <div class="controls">
                                <input type="text" class="span4 typeahead" name="note" >
                                <span class="help-inline">操作说明,方便以后查询核对</span>
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

    <div class="box-content buttons">
        当前类型:
        @foreach($type as $value)
            <a href="/admin/batch/index?type={{$value}}" class="btn btn-small btn-success">{{$value}}</a>
        @endforeach

    </div>

    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>批量信息列表</h2>
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
                        <th>内容</th>
                        <th>类别</th>
                        <th>添加人</th>
                        <th>备注</th>
                        <th>创建时间</th>
                        <th>执行时间</th>
                        <th>附件内容</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($list) )
                        @foreach( $list as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['content'] }}</td>
                                <td class="center">
                                    @if($info['type'] == \App\Http\Dbs\Batch\BatchListDb::TYPE_PHONE)
                                        <button class="btn btn-mini">短信</button>
                                    @elseif($info['type'] == \App\Http\Dbs\Batch\BatchListDb::TYPE_APP)
                                        <button class="btn btn-mini btn-primary">APP</button>
                                    @elseif($info['type'] == \App\Http\Dbs\Batch\BatchListDb::TYPE_WX)
                                        <button class="btn btn-mini btn-danger">微信</button>
                                    @elseif($info['type'] == \App\Http\Dbs\Batch\BatchListDb::TYPE_BONUS)
                                        <button class="btn btn-mini btn-warning">红包加息券</button>
                                    @else
                                        <button class="btn btn-mini btn-inverse">未知类型</button>
                                    @endif
                                </td>
                                <td>{{ $info['admin_id'] }}</td>
                                <td>{{ $info['note'] }}</td>
                                <td>{{ $info['created_at'] }}</td>
                                <td>{{ $info['updated_at'] }}</td>
                                <td><a href="{{ $info['file_path'] }}" title="点击查看" target="_blank">{{ $info['file_path'] }}</a></td>
                                <td class="center">
                                    @if($info['status'] == \App\Http\Dbs\Batch\BatchListDb::STATUS_WAIT)
                                        <a class="label label-warning" href="/admin/batch/audit/{{ $info['id'] }}" onclick="return confirm('确定审核通过？')" >点击审核</a>
                                        &nbsp;&nbsp;
                                        <a class="label label-error" href="/admin/batch/del/{{ $info['id'] }}" onclick="return confirm('确定删除？')" >点击删除</a>
                                    @elseif($info['status'] == \App\Http\Dbs\Batch\BatchListDb::STATUS_SUCCESS) 已发送
                                    @else 等待发送
                                    @endif
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





@stop