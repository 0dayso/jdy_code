@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">家庭账户解绑</a></li>
    </ul>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon family"></i><span class="break"></span>解绑家庭账户</h2>
            </div>
            <div class="box-content">
                <form action="" method="get" id="unbindFamily">
                    <div class="control-group">
                        <div class="span5">主账户手机号码: <input name="phone" type="text" value="{{$phone}}"></div>
                        <div class="span4"><button type="submit" id="search1" class="btn btn-small btn-primary">点击查询</button></div>
                    </div>
                </form>
                @if(!empty($familyList))
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <th>子账号手机号</th>
                        <th>家庭账户姓名</th>
                        <th>与主账户关系</th>
                        <th>注册时间</th>
                        <th>操作</th>
                        </thead>
                        @foreach($familyList as $family)
                            <tbody id="list{{ $family['id'] }}">
                            <td>{{$family['phone']}}</td>
                            <td>{{$family['family_name']}}</td>
                            <td>{{$family['call_name']}}</td>
                            <td>{{ $family['created_at'] }}</td>
                            <td><a style="cursor: pointer;"><span data-id= "{{ $family['id'] }}" class="label label-important">账户解绑</span></a></td>
                            </tbody>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.label-important').click(function(){
                var id = $(this).attr('data-id');
                $.ajax({
                    url : '/admin/user/doUnbindFamily',
                    type: 'POST',
                    dataType: 'json',
                    data: {'id': id},
                    success : function(result) {
                        if(result.status){
                            $("#list"+id).hide();
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger ul li').html(result.msg);
                        }

                    },
                    error : function(result) {
                        $('.alert-danger').slideDown();
                        $('.alert-danger ul li').html(result.msg);
                    }
                });
            });

        });
    </script>
@endsection