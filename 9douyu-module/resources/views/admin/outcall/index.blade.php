@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">外呼数据管理</a></li>
    </ul>
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>上传外呼数据</h2>
            </div>
            <div class="box-content">
                <form id='upload_file' class="form-horizontal" method="post" action="" enctype="multipart/form-data" >
                    <div class="control-group">
                        <label class="control-label" for="fileInput">外呼文件</label>
                        <div class="controls">
                            <div class="uploader" id="uniform-fileInput">
                                <input class="input-file uniform_on" id="fileInput" type="file" name="outCall">
                                <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                <span class="action" style="-webkit-user-select: none;">Choose File</span>
                            </div>                                ( * 要求,上传表格文件:xlsx,xls,上传外呼数据表)

                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" >上传</button>
                        <button type="reset" class="btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection