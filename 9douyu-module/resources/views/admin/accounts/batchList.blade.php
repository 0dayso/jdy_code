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
        <div class="box">
            <div class="box-header">
                <h2>
                    <i class="halflings-icon align-justify"></i>
                    <span class="break"></span>使用说明
                    <a href="#" class="btn-minimize" title="点击查看"><i class="halflings-icon chevron-down"></i></a>
                    <a href="#" class="btn-close" title="点击关闭"><i class="halflings-icon remove"></i></a>
                </h2>
            </div>
            <div class="box-content" style="display: none;">
                <pre>
                    1,上传的对账文件必须是表格文件:xlsx,xls;
                    2,选择的充值通道与对账文件内的订单一一致
                    3,文件上传成功后 点击  对账 按钮 (对账前请确认文件与通道是否一致)
                    4,对账成功后:如有异常订单则会显示未处理订单数据,请点去查看操作
                </pre>
            </div>
        </div><!--/span-->
    </div>
    <div class="row-fluid sortable ui-sortable">
        <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/accounts/checkList">充值对账列表</a>

        @if( $checkRecord !=0 || $checkRecord)

            <a style="margin-bottom: 5px;" class="btn btn-small btn-danger" href="/admin/accounts/untreated">未处理订单数量: {{$checkRecord}}</a>
        @endif
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>添加对账记录</h2>
            </div>

            <div class="box-content">
                <form id='upload_file' class="form-horizontal" method="post" action="/admin/accounts/batch/add" enctype="multipart/form-data" >
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label">充值通道</label>
                            <div class="controls">
                                <select name="pay_channel" id="recharge_channel">
                                    <option value="">请选择通道</option>
                                    @if( !empty($accountsList))

                                        @foreach($accountsList as $key => $accounts )
                                            <option value="{{$key}}">{{$accounts}}</option>
                                        @endforeach

                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="fileInput">文件</label>
                            <div class="controls">
                                <div class="uploader" id="uniform-fileInput">
                                    <input class="input-file uniform_on" id="fileInput" type="file" name="file">
                                    <span class="filename" style="-webkit-user-select: none;">No file selected</span>
                                    <span class="action" style="-webkit-user-select: none;">Choose File</span>
                                </div>                                ( * 要求,上传表格文件:xlsx,xls )

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
                            <button type="submit" class="btn btn-primary" >上传</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div><!--/span-->
    </div>

    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>对账的信息列表</h2>
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
                        <th>文件名称</th>
                        <th>类别</th>
                        <th>备注</th>
                        <th>创建时间</th>
                        <th>完成时间</th>
                        <th>附件内容</th>
                        <th>添加人</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($list) )
                        @foreach( $list as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['name'] }}</td>
                                <td class="center">
                                    <button class="btn btn-mini btn-primary">{{$accountsList[$info['pay_channel']]}}</button>
                                </td>
                                <td>{{ $info['note'] }}</td>
                                <td>{{ $info['created_at'] }}</td>
                                <td>{{ $info['updated_at'] }}</td>
                                <td>
                                    <a href="{{ $info['file_path'] }}" title="点击查看" target="_blank" >{{ $info['file_path'] }}</a>
                                </td>
                                <td>{{ $info['admin_id'] }}</td>
                                <td class="center">
                                    @if($info['status'] == \App\Http\Dbs\Order\CheckBatchDb::STATUS_PENDING)
                                        <a class="label label-warning" href="/admin/accounts/batch/review?id={{ $info['id'] }}" onclick="return confirm('确定审核通过？')" >对账</a>
                                        &nbsp;&nbsp;
                                        <a class="label label-error" href="/admin/accounts/batch/delete?id={{ $info['id'] }}" onclick="return confirm('确定删除？')" >删除</a>
                                    @else
                                        <a class="label label-success" href="javascript:;" >{{$reviewStatus[$info['status']]}}</a>
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
            <div class="pagination pagination-centered" id="pagination-ajax">
                @include('scripts/paginate', ['paginate'=>$paginate])
            </div>
        </div><!--/span-->
    </div>
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){

                var recharge = $("#recharge_channel option:selected").val();

                if(recharge == null || recharge ==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请选择需要对账的通道');
                    return false;
                }

                var fileInfo = $("#fileInput").val();

                if(fileInfo == ''||fileInfo ==null){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请选择对账的文件');
                    return false;
                }

                $("#upload_file").submit();
            })
        });
    </script>
@endsection
@stop