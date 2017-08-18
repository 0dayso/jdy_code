@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">更换手机号</a></li>
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
                <h2><i class="halflings-icon phone"></i><span class="break"></span>更换用户手机号码</h2>
            </div>
            <div class="box-content">
                <form action="" method="get" id="modifyPhone">
                {{--<div class="control-group">
                    <label>身份证号: <input name="id_card" type="text" ></label>
                </div>--}}
                <div class="control-group">
                    <div class="span4">旧手机号:  <input name="phone" type="text" value="{{$phone}}"></div>
                    <div class="span4">新手机号:  <input name="new_phone" type="text" value="{{$new_phone}}"></div>
                    <div class="span4"><button type="button" id="search1" class="btn btn-small btn-primary">点击查询</button>  @if(!empty($userInfo))<input type="button" id="search2" class="btn btn-small btn-primary" value="提交更改"> @endif</div>
                </div>
                </form>
                @if(!empty($userInfo))
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <th>原手机号的信息</th>
                        <th>姓名</th>
                        <th>身份证</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        </thead>
                        <tbody>
                        <td>{{$userInfo['phone']}}</td>
                        <td>{{$userInfo['real_name']}}</td>
                        <td>{{$userInfo['identity_card']}}</td>
                        <td>{{ App\Http\Models\User\UserModel::getUserStatus($userInfo['status']) }}</td>
                        <td>{{$userInfo['created_at']}}</td>
                        </tbody>
                    </table>
                @endif

                @if(!empty($userNewInfo))
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <th>新手机号的信息</th>
                        <th>姓名</th>
                        <th>身份证</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        </thead>
                        <tbody>
                        <td>{{$userNewInfo['phone']}}</td>
                        <td>{{$userNewInfo['real_name']}}</td>
                        <td>{{$userNewInfo['identity_card']}}</td>
                        <td>{{ App\Http\Models\User\UserModel::getUserStatus($userNewInfo['status']) }}</td>
                        <td>{{$userNewInfo['created_at']}}</td>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('jsScript')
<script type="text/javascript">
    $(document).ready(function(){
        $("#search1").click(function(){
            var phone = $('input[name=phone]').val();
            var new_phone = $('input[name=new_phone]').val();
            if (phone =='' || new_phone =='') {
                $('.alert-danger').slideDown();
                $('.alert-danger ul li').html('请完整填写输入框信息');
                return false
            }
            $("#modifyPhone").submit();
        });
        //提交更改手机号
        $("#search2").click(function(){
            var phone = $('input[name=phone]').val();
            var new_phone = $('input[name=new_phone]').val();
            $.ajax({
                url : '/admin/user/doChange',
                type: 'POST',
                dataType: 'json',
                data: {'phone': phone, 'new_phone':new_phone},
                success : function(result) {
                    if(result.status){
                        $('.alert-danger').addClass('alert-success');
                        $('.alert-success').show();
                        $('.alert-success ul li').html('更改成功');
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