@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">添加零钱计划利率</a></li>
    </ul>
    @if(Session::has('fail'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <form id="addRate"  action="/admin/current/rate/doCreate" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加零钱计划利率</h2>
                </div>
                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 日期 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="rate_date" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" type="text" name="rate_date" value="" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 借款利率 </label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="rate" type="text" name="rate" value="" > +  <input class="input-xlarge focused" id="profit" type="text" name="profit" value="" > %
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">添加利率</button>
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
                var rate_date = $("#rate_date").val();
                var rate = $("#rate").val();
                if(rate_date == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('日期不能为空');
                    return false;
                }
                if(rate == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('基础利率不能为空');
                    return false;
                }
                $("#addRate").submit();
            })
        });
    </script>
@endsection