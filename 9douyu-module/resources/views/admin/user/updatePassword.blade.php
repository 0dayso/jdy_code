@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">修改密码</a></li>
    </ul>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <!--start Content-->
    <!--搜索表单-->
    @if( !empty(Session('fail')) )
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
            {{ Session('fail') }}
        </div>
    @endif
    @if( !empty(Session('msg')) )
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa icon-ok"></i> 提示！</h4>
            {{ Session('msg') }}
        </div>
    @endif
    <form class="form-horizontal form-bordered" action="doUpdatePassword" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="panel-body panel-body-nopadding">
            <div class="form-group">
                <label class="col-sm-3 control-label">旧密码 <span class="asterisk">*</span></label>

                <div class="col-sm-6">
                    <input type="password"  data-toggle="tooltip" name="old_password"
                           data-trigger="hover" class="form-control tooltips"
                           placeholder="请输入旧密码"
                           data-original-title="请输入旧密码" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">新密码 <span class="asterisk">*</span></label>

                <div class="col-sm-6">
                    <input type="password"  data-toggle="tooltip" name="new_password"
                           data-trigger="hover" class="form-control tooltips"
                           placeholder="长度为8位以上，并由大、小写字母、数字组成"
                           data-original-title="请输入新密码" >
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">确认密码 <span class="asterisk">*</span></label>

                <div class="col-sm-6">
                    <input type="password"  data-toggle="tooltip" name="second_password"
                           data-trigger="hover" class="form-control tooltips"
                           placeholder="与新密码保持一致"
                           data-original-title="请再次输入新密码" >
                </div>
            </div>

        </div><!-- panel-body -->

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <button class="btn btn-primary">确认修改</button>
                    &nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default">取消</button>
                </div>
            </div>
        </div><!-- panel-footer -->

    </form>
@endsection()
