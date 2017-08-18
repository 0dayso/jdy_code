@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">检测银行卡实名信息</a></li>
    </ul>
    <div class="alert alert-danger" style="display: none;">
        <ul><li></li></ul>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>检测银行卡实名信息</h2>
            </div>
            <div class="box-content">
                <div style="padding-bottom: 20px;">
                功能说明：
                <ul>
                    <li>1. 查询用户信息：通过手机号获取用户的状态,若存在 自动输入用户的姓名,身份证；不存在，请手动输入用户的姓名，身份证号！</li>
                    <li>2. 查询：提交后台查询用户的四要素信息！</li>
                    <li>3. 返回结果：鉴权成功表示用户提供的信息在系统中查询为同一个用户！其他返回结果全部为不一致！！</li>
                    <li>1）鉴权成功
                        2）鉴权失败
                        3）银行卡错误
                        4）身份证格式错误</li>
                </ul>
                </div>
                <form action="/admin/bankcard/doCheckUserCard" method="post" id="checkCard">
                    <input type="hidden" name="search" value="1">
                    <div class="control-group">
                        <div class="span1"></div><div class="span2">手机号</div><div class="span9"><input name="phone" type="text" value="">&nbsp;&nbsp;&nbsp;<button type="button" style="margin-bottom: 8px;" id="searchUser" class="btn btn-primary btn-small">查询用户信息</button></div>
                    </div>
                    <div class="control-group">
                        <div class="span1"></div><div class="span2">姓名</div><div class="span9"><input name="real_name" type="text" value=""></div>
                    </div>
                    <div class="control-group">
                        <div class="span1"></div><div class="span2">身份证号</div><div class="span9"><input name="id_card" type="text" value=""></div>
                    </div>
                    <div class="control-group">
                        <div class="span1"></div><div class="span2">银行卡</div><div class="span9"><input name="bank_card" type="text" value=""></div>
                    </div>
                    <div class="control-group">
                        <div class="span3"></div><div class="span9"><button type="submit" id="search" class="btn btn-primary">点击查询</button><br/><br/></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            @if(Session::has('errorMsg'))
            $('.alert-danger').slideDown();
            $('.alert-danger').addClass('.alert-success').html("{{Session::get('errorMsg')}}").show(300).delay(5000).hide(300);
            @endif

            $("#search").click(function(){
                if($("input[name=phone]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入手机号').show(300).delay(2000).hide(300);
                    return false;
                }
                if($("input[name=id_card]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入身份证号').show(300).delay(2000).hide(300);
                    return false;
                }
                if($("input[name=bank_card]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入银行卡号').show(300).delay(2000).hide(300);
                    return false;
                }
                if($("input[name=real_name]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入姓名').show(300).delay(2000).hide(300);
                    return false;
                }

                $("#checkCard").submit();
            });
            $("#searchUser").click(function(){
                    var phone = $("input[name=phone]").val();
                if(phone == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入手机号').show(300).delay(2000).hide(300);
                    return false;
                }
                $.ajax({
                    url : '/admin/bankcard/getCheckUserInfo',
                    type: 'POST',
                    dataType: 'json',
                    data: {'phone': phone},
                    success : function(result) {
                        if(result.status){
                            $("input[name=real_name]").val(result.real_name);
                            $("input[name=id_card]").val(result.identity_card);
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger').html(result.msg).show(300).delay(2000).hide(300);
                            $("input[name=real_name]").val();
                            $("input[name=id_card]").val();
                        }

                    },
                    error : function(result) {
                        $('.alert-danger').slideDown();
                        $('.alert-danger').html(result.msg).show(300).delay(2000).hide(300);
                    }
                });
            });
        });
    </script>
@endsection