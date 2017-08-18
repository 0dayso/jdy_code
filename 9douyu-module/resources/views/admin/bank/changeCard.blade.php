@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">更换银行卡</a></li>
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
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>更换银行卡</h2>
            </div>
            <div class="box-content">
                <form action="/admin/bankcard/doChange" method="post" id="changeCard">
                    <input type="hidden" name="search" value="1">
                <div class="control-group">
                    <div class="span1"></div><div class="span2">手机号</div><div class="span9"><input name="phone" type="text" value="{{ Input::old('phone') }}"></div>
                </div>
                <div class="control-group">
                    <div class="span1"></div><div class="span2">身份证号</div><div class="span9"><input name="id_card" type="text" value="{{ Input::old('id_card') }}"></div>
                </div>
                <div class="control-group">
                    <div class="span1"></div><div class="span2">旧银行卡</div><div class="span9"><input name="old_card" type="text" value="{{ Input::old('old_card') }}"></div>
                </div>
                <div class="control-group">
                    <div class="span1"></div><div class="span2">新银行卡</div><div class="span9"><input name="new_card" type="text" value="{{ Input::old('new_card') }}"></div>
                </div>
                <div class="control-group">
                    <div class="span1"></div><div class="span2">新银行卡银行</div>
                    <div class="span9">
                        <select id="selectSource" name="bank_id" data-rel="chosen">
                            <option value="">选择银行卡</option>
                            @foreach($banks as $bank)
                                <option value="{{$bank['id']}}" @if( $bank['id'] == Input::old('bank_id')) selected @endif>{{$bank['name']}}</option>
                            @endforeach
                        </select><br/><br/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="span3"></div><div class="span9"><button type="button" id="search" class="btn btn-primary">点击查询</button><br/><br/></div>
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
            $('.alert-danger').html("{{Session::get('errorMsg')}}").show(300).delay(2000).hide(300);
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
                if($("input[name=old_card]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入旧银行卡').show(300).delay(2000).hide(300);
                    return false;
                }
                if($("input[name=new_card]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请输入新银行卡').show(300).delay(2000).hide(300);
                    return false;
                }
                if($("select[name=bank_id]").val()==''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger').html('请选择银行').show(300).delay(2000).hide(300);
                    return false;
                }
                $("#changeCard").submit();
            });
        });
    </script>
@endsection
