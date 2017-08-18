@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="/admin/current/limit/lists">零钱计划限额列表</a></li>
    </ul>
    @if(Session::has('fail'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa icon-ok"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <form id="addRate"  action="/admin/current/limit/doEdit" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="{{$id}}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>编辑零钱计划限额</h2>
                </div>
                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 用户手机号码 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="phone" readonly type="text" re name="phone" value="{{$phone}}" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 转入的最高额度</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="in_cash" type="text" name="in_cash" value="{{$in_cash}}" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 转出的最高额度</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="cash" type="text" name="cash" value="{{$cash}}" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 状态</label>
                        <div class="controls">
                            <select style="display: block;"   name="status">
                                <option value="20" @if($status ==\App\Http\Dbs\Current\CashLimitDb::STATUS_ACTIVATE) selected="true" @endif>开启</option>
                                <option value="10" @if($status ==\App\Http\Dbs\Current\CashLimitDb::STATUS_CLOSED) selected="true" @endif>关闭</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">确认修改</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){
                var phone = $("#phone").val();
                var cash = $("#cash").val();
                if(phone == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('手机号码不能为空');
                    return false;
                }
                if(cash == ''||cash =='0'){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写大于0的额度');
                    return false;
                }
                $("#addRate").submit();
            })
        });
    </script>
@endsection