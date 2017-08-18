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
            <a href="#">对账记录列表</a>
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
        <a style="margin-bottom: 5px;" class="btn btn-small btn-warning" href="/admin/micro">全部列表</a>
        <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/micro/addMicro">添加微刊</a>

        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>添加微刊数据</h2>
            </div>

            <div class="box-content">
                <form id='addMicro' class="form-horizontal" method="post" action="/admin/micro/doAddMicro" enctype="multipart/form-data" >
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">微刊刊号</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" name="date"  id="micro_date">
                                <span class="help-inline">微刊号:如2016年9月 请填写201609</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">微刊链接</label>
                            <div class="controls">
                                <input type="text" class="span4 typeahead" name="link" id="micro_link">
                                <span class="help-inline">添加带有http开头的链接</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">微刊标题</label>
                            <div class="controls">
                                <input type="text" class="span4 typeahead" name="title" id="micro_title">
                                <span class="help-inline">本期微刊的主体,用于分享</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fileInput">文件</label>
                            <div class="controls">
                                <div class="uploader" id="uniform-fileInput">
                                    <input class="input-file uniform_on" id="fileInput" type="file" name="img">
                                    <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                    <span class="action" style="-webkit-user-select: none;">Choose File</span>
                                </div>                                ( * 要求,普通的图片格式 )
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">微刊分享内容</label>
                            <div class="controls">
                                <textarea class="span4 typeahead" name="content"></textarea>
                                <span class="help-inline">微刊的简介,用于分享使用</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">是否开启</label>
                            <div class="controls">
                                <select name="status" >
                                    <option value="200">开启</option>
                                    <option value="100">关闭</option>
                                </select>
                                <span class="help-inline">微刊状态</span>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" >添加</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div><!--/span-->
    </div>
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){

                var date = $("#micro_date").val();

                if(date == null || date ==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写微刊刊号');
                    return false;
                }

                var link = $("#micro_link").val();

                if(link == null || link ==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写微刊链接');
                    return false;
                }

                $("#addMicro").submit();
            })
        });
    </script>
@endsection
@stop