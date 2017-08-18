@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">T+0提现列表</a></li>
    </ul>


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
                <h2><i class="halflings-icon edit"></i><span class="break"></span>T+0提现列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>提现开始时间</th>
                        <th>提现开始时间</th>
                        <th>提现总金额</th>
                        <th>订单总数</th>
                        <th>状态</th>
                        <th>邮箱</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    @if($list)
                        @foreach($list as $key=>$val)
                            <tr>
                                <td>{{$val['id']}}</td>
                                <td>{{$val['start_time']}}</td>
                                <td>{{$val['end_time']}}</td>
                                <td>{{$val['cash']}}</td>
                                <td>{{$val['num']}}</td>
                                <td>{{$val['status_note']}}</td>
                                <td><input type="text" name="email" value="" id="email_{{$val['id']}}"/></td>
                                <td>
                                    @if($val['num'])
                                        @if(!$val['status'])
                                            <a href="/admin/withdraw/sendBatchMsg/{{$val['id']}}"><span class="label label-warning">发送处理消息</span></a>
                                        @elseif($val['status'])
                                            {{--<a onclick="getEmail({{$val['id']}})" href="javascript:void(0);"><span class="label label-info">发送邮件</span></a>   --}}
                                            {{--  <a onclick="getEmailSuma({{$val['id']}})" href="javascript:void(0);"><span class="label label-info">丰付邮件</span></a> --}}
                                            <a onclick="getEmailUcf({{$val['id']}})" href="javascript:void(0);"><span class="label label-info">先锋邮件</span></a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsScript')
    {{--<script src="{{ assetUrlByCdn('/') }}js/principalInterest.js"></script>--}}
    <script>

        function getEmail(id) {

            var email = $('#email_'+id).val();

            var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;

            if (!pattern.test(email)) {
                alert("请输入正确的邮箱地址。");
                return false;
            }

            $.ajax({
                url:'/admin/withdraw/sendEmail',
                type:'POST',
                data:{'email':email,'id':id},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result.status==false) {
                        alert(result.msg);
                    } else {
                        alert('请求已提交,请注意查收邮件!');
                    }
                }
            });

        }

        function getEmailSuma(id) {

            var email = $('#email_'+id).val();

            var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;

            if (!pattern.test(email)) {
                alert("请输入正确的邮箱地址。");
                return false;
            }

            $.ajax({
                url:'/admin/withdraw/sendEmailWithdraw',
                type:'POST',
                data:{'email':email,'id':id,'type':'suma'},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result.status==false) {
                        alert(result.msg);
                    } else {
                        alert('请求已提交,请注意查收邮件!');
                    }
                }
            });

        }

        function getEmailUcf(id) {

            var email = $('#email_'+id).val();

            var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;

            if (!pattern.test(email)) {
                alert("请输入正确的邮箱地址。");
                return false;
            }

            $.ajax({
                url:'/admin/withdraw/sendEmailWithdraw',
                type:'POST',
                data:{'email':email,'id':id,'type':'ucf'},
                dataType:'json',
                async: false,  //同步发送请求
                success:function(result){
                    if(result.status==false) {
                        alert(result.msg);
                    } else {
                        alert('请求已提交,请注意查收邮件!');
                    }
                }
            });

        }


        (function($){

            $(document).ready(function(){


            });
        })(jQuery);
    </script>

@endsection