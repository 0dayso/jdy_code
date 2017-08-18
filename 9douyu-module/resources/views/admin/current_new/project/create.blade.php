@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">添加零钱计划项目</a></li>
    </ul>
    @if(Session::has('fail'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <form id="addRate"  action="/admin/currentNew/project/doCreate" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加零钱计划项目</h2>
                </div>
                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 项目名称 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="name" type="text" name="name" value="" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 融资金额 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="total_amount" type="text" name="total_amount" value="" >元
                        </div>
                    </div>
                   {{-- <div class="control-group">
                        <label class="control-label" for="selectError"> 发布时间 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="publish_at" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})" type="text" name="publish_at" value="" >
                        </div>
                    </div>--}}
                    <div class="control-group">
                        <label for="type" class="control-label">是否可以转增：</label>
                        <div class="col-lg-6">
                            <select id="assignment" name="status" data-rel="chosen">
                                <option value="200" selected>发布</option>
                                <option value="100">待发布</option>
                            </select>

                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">添加项目</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endsection