@extends('admin/layouts/default')
@section('content')
<script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
<ul class="breadcrumb">
    <li>
        <i class="icon-home"></i>
        <a href="index.html">控制台</a>
        <i class="icon-angle-right"></i>
    </li>
    <li><a href="javascript:void(0);">账户余额 加/扣 款</a></li>
</ul>
@if(Session::has('message'))
    <div class="alert alert-danger">
    @else
    <div class="alert alert-danger" style="display:none;">
@endif
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <ul>
        <li id="message">@if(Session::has('message')){{ Session::get('message') }}@endif</li>
    </ul>
</div>
<div class="row-fluid sortable ui-sortable">
    <div class="box span12">
        <div class="box-header">
            <h2><i class="halflings-icon phone"></i><span class="break"></span>账户余额 加/扣 款</h2>
        </div>
        <div class="box-content form-horizontal">
            <fieldset>
                <form action="/admin/user/doChangeBalance" method="post" id="modifyPhone">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="control-group" >
                        <label class="control-label" for="date02" style="color: red; font-size: 1.3em;">  </label>
                        <div class="controls">
                            <p style="color: red; font-size: 1.3em;">注意:本操作涉及用户【资产、资金】流水，一旦生成，不可删除，请慎重操作！！！</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02"> 用户手机号: </label>
                        <div class="controls">
                            <div class="input-prepend input-append">
                                <input class="input-xlarge focused" id="date02" type="text" name="phone" value="{{ Input::old('phone', $phone) }}" placeholder="输入手机号点击右侧进行查询用户信息"><span class="add-on"  id="search1">点击查询</span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02"> 操作类型: </label>
                        <div class="controls">
                            <select id="type" name="type" data-rel="chosen">
                                <option value="1">加钱</option>
                                <option value="2">扣款</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02"> 金额: </label>
                        <div class="controls">
                            <div class="input-prepend input-append">
                                <input class="input-xlarge focused" id="date02" type="text" name="cash" value="{{ Input::old('cash') }}" placeholder="金额">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02"> 备注: </label>
                        <div class="controls">
                            <div class="input-prepend input-append">
                                <input class="input-xlarge focused" id="date02" type="text" name="note" value="{{ Input::old('note') }}" placeholder="必填">
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02"> 安全验证码: </label>
                        <div class="controls">
                            <div class="input-prepend input-append">
                                <input class="input-xlarge focused" id="date02" type="text" name="code" value="{{ Input::old('code') }}" placeholder="将右侧安全验证码复制到此处"><span class="add-on"> {{$code}} </span>
                                <input type="hidden" name="confirmCode" value="{{$code}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> &nbsp; </label>
                        <div class="controls">
                            @if(!empty($userInfo))
                                <input type="button" id="search2" class="btn btn-primary" value="确认">
                            <button type="reset" class="btn">重置</button>
                            @endif
                        </div>

                    </div>
                </form>
                <fieldset>

                @if(!empty($userInfo))
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>名称</th>
                    <th>用户信息</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>手机号: </td>
                        <td>{{$userInfo['phone']}}</td>
                    </tr>
                    <tr>
                        <td>姓名:</td>
                        <td>{{$userInfo['real_name']}}</td>
                    </tr>
                    <tr>
                        <td>身份证号:</td>
                        <td>{{$userInfo['identity_card']}}</td>
                    </tr>
                    <tr>
                        <td>账户余额:</td>
                        <td>{{number_format($userInfo['balance'],2)}}</td>
                    </tr>
                    <tr>
                        <td>状态:</td>
                        <td>{{ App\Http\Models\User\UserModel::getUserStatus($userInfo['status']) }}</td>
                    </tr>
                    <tr>
                        <td>注册时间:</td>
                        <td>{{$userInfo['created_at']}}</td>
                    </tr>
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
            if (phone =='') {
                $('.alert-danger').slideDown();
                $('.alert-danger ul li').html('请完整填写输入框信息');
                return false
            }
            window.location.href = "{{env('APP_URL_ADMIN')}}/admin/user/changeBalance?phone="+phone;
        });

        //提交更改手机号
        $("#search2").click(function(){

            var phone = $('input[name=phone]').val();
            var cash  = $('input[name=cash]').val();
            var note  = $('input[name=note]').val();
            var code  = $('input[name=code]').val();
            var confirmCode =$('input[name=confirmCode]').val();
            var texts = $("#type option:selected").text();

            var msg = '';

            if(phone == ''){
                msg = '请填写手机号';
            }else if(cash == ''){
                msg = '请填写金额';
            }else if(note == ''){
                msg = '请填写手机号备注';
            }else if(code == ''){
                msg = '请填写安全验证码';
            }else if(code != confirmCode){
                msg = '安全验证码错误请重新填写';
            }

            if(msg != ''){
                $('.alert-danger').slideDown();
                $('.alert-danger ul li').html(msg);
                return false
            }else{
                $('.alert-danger').slideUp();
            }

            if(confirm('确定要给用户['+phone+']'+texts+'['+cash+'元]吗?')){
                $('#modifyPhone').submit();
            }

        });
    });
</script>
@endsection