@extends('admin/layouts/default')

@section('content')
    <script src="{{ URL::asset('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="/admin/lottery/record">中奖者列表</a></li>
    </ul>
    @if(Session::has('fail'))
        <div class="alert alert-sucess alert-dismissable">
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
    <form id="addLotteryRecord"  action="/admin/lottery/doEditRecord" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="{{ $lotteryRecord['id']}}" />
        <input type="hidden" name="old_phone" value="{{$lotteryRecord['phone']}}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>修改补充中奖记录</h2>
                </div>

                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError">活动标示</label>
                        <div class="controls">
                            <select name="activity_group" id="activity_group">
                                <option value="">请选择活动</option>
                                @foreach($activityNote as $key => $value)
                                    <option value="{{$key}}_{{$value['group']}}" @if($key==$lotteryRecord['activity_id']) selected @endif>{{$value['name']}}</option>
                                @endforeach
                            </select>
                            <span style="color:red;margin-left: 30px;">请选择活动的标示</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">奖品名称</label>
                        <div class="controls">
                            <select name="prizes_id" id="activity_lottery">
                                <option value="0">请选择</option>
                                @if( !empty($lotteryList))
                                @foreach($lotteryList as $key => $lottery)
                                <option value="{{$lottery['id']}}" @if($lotteryRecord['prizes_id'] == $lottery['id']) selected @endif>{{$lottery['name']}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span style="color:red;margin-left: 30px;">请选择活动的奖品名称</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">中奖者手机号码</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="number" type="text" name="phone" value="{{$lotteryRecord['phone']}}" >
                            <span style="color:red;margin-left: 30px;">请填写中奖者的手机号码</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">中奖者姓名</label>
                        <div class="controls">
                            <input class="input-xlarge focused" readonly type="text" name="user_name" value="{{$lotteryRecord['user_name']}}" >
                            <span style="color:red;margin-left: 30px;">中奖者的姓名</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">中奖时间</label>
                        <div class="controls">
                            <input type="text" name="lottery_time"  onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})" value="{{$lotteryRecord['created_at']}}" placeholder="中奖时间">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">添加说明</label>
                        <div class="controls">
                            <textarea name="note" id="lottery">{{$lotteryRecord['note']}}</textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 状态</label>
                        <div class="controls">
                            <select style="display: block;"   name="status">
                                <option value="10" @if($lotteryRecord['status'] == 10) selected @endif>待审核</option>
                                <option value="20" @if($lotteryRecord['status'] == 20) selected @endif>审核通过</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">更新记录</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <pre>
        添加中奖记录说明:
        1,添加中奖记录的功能只是针对特定的抽奖活动(用户不进行抽奖,管理员随机筛选);
        2,添加中奖记录只是为了方便查询,不做实际奖品发放的依据
    </pre>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){
                var group = $("#activity_group").val();
                var lottery = $("#activity_lottery").val();
                var order_num = $("#order_num").val();
                if(lottery == '' || lottery=='0'){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请选择中奖的信息');
                    return false;
                }
                if(phone == ''|| phone.length <11 ){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写中奖者的手机号码');
                    return false;
                }
                if(group == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请设置活动标示');
                    return false;
                }
                $("#addLotteryRecord").submit();
            })

            $("#activity_group").change(function () {
                var activity_group   =   $(this).val();
                if( activity_group ==''){
                    return false
                }
                $.ajax({
                    url      :"/admin/lottery/lotteryJson",
                    dataType :'json',
                    type     :'post',
                    data     : { _token:'{{csrf_token()}}',group_id:activity_group},
                    success : function(json) {

                        if( json.code ==200 && json.status == true ) {
                            var selectHtml = '<option value="">请选择</option>';
                            for (var i=0;i < json.data.length;i++){
                                selectHtml  += '<option value="'+json.data[i].id+'">'+json.data[i].name+'</option>'
                            }

                            $("#activity_lottery").empty().html(selectHtml);
                        }else{
                            alert(json.msg);
                        }
                    },
                    error : function(msg) {
                        alert('网络异常,请联系网站管理员')
                    }
                })
            })
        });
    </script>
@endsection