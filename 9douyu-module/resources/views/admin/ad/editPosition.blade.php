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
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>编辑广告位</h2>
            </div>


            <div class="box-content">
                <form class="form-horizontal" method="post" action="/admin/ad/doEditPosition" enctype="multipart/form-data">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">分类</label>
                            <div class="controls">
                                @if($type ==  \App\Http\Dbs\Ad\AdPositionDb::TYPE_PC)
                                    PC
                                @elseif($type == \App\Http\Dbs\Ad\AdPositionDb::TYPE_WAP)
                                    WAP
                                @else
                                    APP
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="id" value="{{$id}}">
                        <input type="hidden" name="type" value="{{$type}}">
                        <div class="control-group">
                            <label class="control-label" for="typeahead">名称</label>
                            <div class="controls">
                                <input type="text" class="span4 typeahead" name="position_name" value="{{$name}}">
                                <span class="help-inline">请尽量写清楚介绍</span>
                            </div>
                        </div>

                        @if($img_url)
                            <div class="control-group">
                                <label class="control-label" for="fileInput">当前预览图</label>
                                <div class="controls">
                                    <img src="{{$img_url}}" width="300" height="200"/>
                                </div>
                            </div>
                        @endif

                        <div class="control-group">
                            <label class="control-label" for="fileInput">新预览图</label>
                            <div class="controls">
                                <div class="uploader" id="uniform-fileInput">
                                    <input class="input-file uniform_on" id="fileInput" type="file" name="display_img">
                                    <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                    <span class="action" style="-webkit-user-select: none;">Choose File</span>
                                </div>                                ( * 上传则使用新图片,不上传使用旧的图片 )

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