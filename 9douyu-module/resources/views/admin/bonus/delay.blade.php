@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">红包延期</a></li>
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
                <h2><i class="fa fa-credit-card"></i><span class="break"></span>红包延期</h2>
            </div>
            <div class="box-content">
                <form action="" method="get">
                    <div class="control-group">
                        <div class="span1"></div><div class="span1">手机号</div><div class="span3"><input name="phone" type="text" value="{{$phone}}"></div><div class="span2"><button type="submit" id="search" class="btn btn-small btn-primary">点击查询</button></div>
                    </div>
                </form>
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 8%;">红包名</th>
                    <th style="width: 8%;">红包金额</th>
                    <th style="width: 8%;">红包利率</th>
                    <th style="width: 25%;">红包项目类型</th>
                    <th style="width: 17%;">红包投资端类型</th>
                    <th style="width: 12%;">获取时间</th>
                    <th style="width: 12%;">最晚使用时间</th>
                    <th style="width: 5%;">操作</th>
                    </thead>
                    @if(!empty($userBonusList))
                        @foreach($userBonusList  as $userBonus)
                        <tr>
                        <td>{{$userBonus['id']}}</td>
                        <td>{{$userBonus['bonus_info']['name']}}</td>
                        <td>{{$userBonus['bonus_info']['money']}}</td>
                        <td>{{$userBonus['bonus_info']['rate']}}</td>
                        <td>@if(empty($userBonus['bonus_info']['project_name']))项目类型:可投全部项目 @else {{$userBonus['bonus_info']['project_name']}} @endif</td>
                        <td>{{$userBonus['bonus_info']['client_name']}}</td>
                        <td>{{$userBonus['get_time']}}</td>
                        <td><input type="text" name="use_end_time" data-value="{{$userBonus['use_end_time']}}" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd',minDate: '{{$userBonus['use_end_time']}}',onpicked:function(){handleUseEndTimeChange($(this))}})" style="width:80px;" value="{{$userBonus['use_end_time']}}" placeholder="开始时间"></td>
                        <td><button type="submit" id="search" data-id="{{$userBonus['id']}}" class="btn hide btn-small btn-primary save">保存</button><div class="tips"></div></td>
                        </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".save").click(function(){
                var userBonusId  = $(this).attr('data-id');
                var userEndTime  = $(this).parents('tr').find("input[name=use_end_time]").val();
                $.ajax({
                    url : '/admin/bonus/doBonusDelay',
                    type: 'POST',
                    dataType: 'json',
                    data: {'userBonusId': userBonusId, 'userEndTime': userEndTime},
                    success : function(result) {
                        if(result.status){
                            $('.save').addClass("hide");
                            $('.alert-danger').slideDown();
                            $('.alert-danger').addClass('.alert-success').html(result.msg).show(300).delay(5000).hide(300);
                        }else{
                            $('.alert-danger').slideDown();
                            $('.alert-danger').html(result.msg).show(300).delay(2000).hide(300);
                        }

                    },
                    error : function(result) {alert('dsdssd');
                        $('.alert-danger').slideDown();
                        $('.alert-danger').html(result.msg).show(300).delay(2000).hide(300);
                    }
                });
            });
        });
        function handleUseEndTimeChange(obj) {
            if($.trim($(obj).attr("data-value")) == $.trim($(obj).val())) {
                $(obj).parents("tr").find(".btn-primary").addClass("hide");
            } else {
                $(obj).parents("tr").find(".btn-primary").removeClass("hide");
            }
        }
    </script>
@endsection