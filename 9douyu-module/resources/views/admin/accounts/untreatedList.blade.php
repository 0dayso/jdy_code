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

    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/accounts/checkList">全部列表</a>
        <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/accounts/handled">已处理列表</a>
        <a style="margin-bottom: 5px;" class="btn btn-small btn-warning" href="/admin/accounts/untreated">未处理列表</a>

        @if( $checkRecord !=0 || $checkRecord)

            <a style="margin-bottom: 5px;" class="btn btn-small btn-danger" href="/admin/accounts/untreated">未处理订单数量: {{$checkRecord}}</a>
        @endif
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>未处理对账的信息列表</h2>
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
                        <th>订单号</th>
                        <th>姓名</th>
                        <th>手机号码</th>
                        <th>充值渠道</th>
                        <th>订单金额</th>
                        <th>备注</th>
                        <th>创建时间</th>
                        <th>完成时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( !empty($list) )
                        @foreach( $list as $info )
                            <tr>
                                <td>{{ $info['id'] }}</td>
                                <td>{{ $info['order_id'] }}</td>
                                @if($info['user_id'] !=0)
                                    <td>{{ $info['info']['real_name'] }}</td>
                                @else
                                    <td>--</td>
                                @endif

                                @if($info['user_id'] !=0)
                                    <td>{{ $info['info']['phone'] }}</td>
                                @else
                                    <td>--</td>
                                @endif
                                <td class="center">
                                    <button class="btn btn-mini btn-primary">{{$accountsList[$info['pay_channel']]}}</button>
                                </td>
                                <td>{{ $info['cash'] }}</td>
                                <td>{{ $info['note'] }}</td>
                                <td>{{ $info['created_at'] }}</td>
                                <td>{{ $info['updated_at'] }}</td>
                                <td class="center">
                                    @if($info['is_check'] == \App\Http\Dbs\Order\CheckOrderRecordDb::CHECK_STATUS_PENDING)
                                        <a class="label label-warning" id='process_{{$info['id']}}' onclick="processOrder('{{$info['id']}}')" >点击处理</a>
                                    @else
                                        <a class="label label-success" href="javascript:;" >已经处理</a>
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

        function processOrder(id){

            var note=prompt("确定处理该对账吗？请填写备忘。","");

            if(note===null){

            }else{
                $.ajax({
                    type: 'post',
                    url: '/admin/accounts/doHandled',
                    data: "id="+id+'&note='+note,
                    dataType:'json',
                    success: function(json) {

                        if( json.status == true){

                            $('#process_'+id).html('已经处理').removeClass('label-warning').addClass('label-success');

                        }

                    }
                });
            }
        }
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