@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">{{$title}}</a></li>
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
                    <h2><i class="halflings-icon phone"></i><span class="break"></span>{{$title}}</h2>
                </div>
                <div class="box-content form-horizontal">
                    <fieldset>
                        <form action="/admin/contract/doCreateDownLoad" method="post" id="doCreateDownLoad">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="control-group" >
                                <label class="control-label" for="date02" style="color: red; font-size: 1.3em;">  </label>
                                {{--<div class="controls">
                                    <p style="color: red; font-size: 1.3em;">注意:下载合同将会以短信形式通知用户,且合同已保全有法律效应，请慎重操作！！！</p>
                                </div>--}}
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date02"> 投资ID </label>
                                <div class="controls">
                                    <div class="input-prepend input-append">
                                        <input class="input-xlarge focused" id="date02" type="text" name="invest_id" value="" placeholder="投资Id">{{--<span class="add-on"  id="search1">输入手机号点击右侧进行查询用户信息点击查询</span>--}}                     <input name="token" type="hidden" value="" />
                                    </div>
                                </div>
                            </div>
                            {{--<div class="control-group">
                                <label class="control-label" for="date02"> 用户ID </label>
                                <div class="controls">
                                    <div class="input-prepend input-append">
                                        <input class="input-xlarge focused" id="date02" type="text" name="user_id" value="" placeholder="输入手机号点击右侧进行查询用户信息">
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="date02"> 项目ID </label>
                                <div class="controls">
                                    <div class="input-prepend input-append">
                                        <input class="input-xlarge focused" id="date02" type="text" name="project_id" value="" placeholder="输入手机号点击右侧进行查询用户信息">
                                    </div>
                                </div>
                            </div>--}}
                            <div class="control-group">
                                <label class="control-label"> &nbsp; </label>
                                <div class="controls">
                                    <input type="button" id="search2" class="btn btn-primary" value="合同下载">
                                    <button type="reset" class="btn">重置</button>
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

                if(confirm('确定要生成合同嘛')){
                    $('#doCreateDownLoad').submit();
                }

            });
        });
    </script>
@endsection