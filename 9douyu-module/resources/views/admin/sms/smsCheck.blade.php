
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
            <a href="#">短信内容检测</a>
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

            <div class="box-content">
                <div class="form-horizontal" >
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">检测短信的内容</label>
                            <div class="controls">
                                <textarea class="span5 typeahead" style="height:100px;" name="sms_content"></textarea>
                                <span class="help-inline-error" style="color:#ff0000"></span>
                                <span class="help-inline-right" style="color:#00A300"></span>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-primary doCheck"  >执行检测</button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div><!--/span-->
    </div>
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".doCheck").click(function(){
                var smsContent = $("textarea[name='sms_content']").val();
                if( smsContent == '' ){
                    $(".help-inline-right").html('');
                    $(".help-inline-error").html( "检测的短信短信内容为空" );
                    return false;
                }
                 $.ajax({
                        url:'/admin/sms/doCheck',
                        type:'POST',
                        data:{sms_content: smsContent },
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            if(result.status == false) {
                                $(".help-inline-right").html('');
                                $(".help-inline-error").html( result.msg );
                                return false;
                            } else {
                                $(".help-inline-error").html('');
                                $(".help-inline-right").html( "没有敏感词,可以发送" );
                            }
                        }
                    });
            });
        });
    </script>
@endsection
@stop
