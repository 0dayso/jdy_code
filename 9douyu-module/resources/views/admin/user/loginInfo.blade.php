@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">用户登录列表</a></li>
    </ul>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <!--start Content-->
    <!--搜索表单-->

    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon user"></i><span class="break"></span>用户登录列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>用户ID</th>
                    <th>来源</th>
                    <th>设备类型</th>
                    <th>设备类型</th>
                    <th>登录ip</th>
                    <th>登录时间</th>
                    <th>备注</th>
                    </thead>
                    @if(!empty($loginList))
                    @foreach($loginList as $info)
                    <tbody>
                    <td>{{$info['user_id']}}</td>
                    <td>{{isset($source[$info['app_request']]) ?$source[$info['app_request']] : $info['app_request']}}</td>
                    <td>{{$info['client_type']}}</td>
                    <td>{{$info['client_version']}}</td>
                    <td>{{$info['login_ip']}}</td>
                    <td>{{$info['login_time']}}</td>
                    <td>{{$info['client_note']}}</td>
                    </td>
                    </tbody>
                    @endforeach
                        @else
                        <tbody>
                        <td colspan="7" style="text-align: center">暂无登录数据 </td>
                        </tbody>
                    @endif
                </table>
                @include('admin/common/page')
            </div>
        </div>
    </div>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){

            $(".lock, .unlock").on('click',function(){
                var user_id = $(this).attr('data-value');
                var status = $(this).attr('data-status');
                $.ajax({
                    url  : '/admin/user/doUserStatusBlock',
                    type : 'post',
                    dataType : 'json',
                    data : {'user_id' : user_id, status: status},
                    async: true,
                    success:function(result){
                        if(result.status){
                            $('.alert-danger').addClass('alert-success');
                            $('.alert-success').show();
                            $("#status"+user_id).html('已锁定');
                            $('.alert-success').html('锁定账户成功').show(300).delay(2000).hide(300);
                            location.reload();
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger ul li').html(result.msg);
                        }

                    },
                    error:function(result){
                        $('.alert-danger').slideDown();
                        $('.alert-danger ul li').html(result.msg);
                    }
                });
            });

            //冻结
            $(".frozen").on('click', function(){

                var user_id = $(this).attr('data-value');
                var status = $(this).attr('data-status');

                if( status == 400){

                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('账户已被冻结');

                    return false;

                }

                if(!confirm('确定要冻结ID为'+user_id+'的账户吗？')){
                    return false;
                }

                $.ajax({
                    url  : '/admin/user/doUserStatusFrozen',
                    type : 'post',
                    dataType : 'json',
                    data : {'user_id' : user_id,},
                    async: true,
                    success:function(result){
                        if(result.status){
                            $('.alert-danger').addClass('alert-success');
                            $('.alert-success').show();
                            $("#status"+user_id).html('已冻结');
                            $('.alert-success').html('ID为'+ user_id +'账户冻结成功').show(30000).delay(2000).hide(300);
                            location.reload();
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger ul li').html(result.msg);
                        }

                    },
                    error:function(result){
                        $('.alert-danger').slideDown();
                        $('.alert-danger ul li').html(result.msg);
                    }
                });
            });

            //解冻
            $(".unFrozen").on('click', function(){

                var user_id = $(this).attr('data-value');
                var status = $(this).attr('data-status');

                if( status == 200){

                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('账户已解冻');

                    return false;

                }

                if(!confirm('确定要解冻ID为'+user_id+'的账户吗？')){
                    return false;
                }

                $.ajax({
                    url  : '/admin/user/doUserStatusUnFrozen',
                    type : 'post',
                    dataType : 'json',
                    data : {'user_id' : user_id,},
                    async: true,
                    success:function(result){
                        if(result.status){
                            $('.alert-danger').addClass('alert-success');
                            $('.alert-success').show();
                            $('.alert-success').html('ID为'+ user_id +'账户解冻成功').show(30000).delay(2000).hide(300);
                            location.reload();
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger ul li').html(result.msg);
                        }

                    },
                    error:function(result){
                        $('.alert-danger').slideDown();
                        $('.alert-danger ul li').html(result.msg);
                    }
                });


            });
        });
    </script>
@endsection