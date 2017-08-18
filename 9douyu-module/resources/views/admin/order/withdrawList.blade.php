@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">提现订单</a></li>
    </ul>


    <!-- start: Content -->
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>提现管理</h2>
            </div>
            <div class="box-content">

                <form action="/admin/withdraw" method="get" id="searchForm">
                    <div class="row-fluid">
                        <div class="span4">
                            <label>用户手机号: <input type="text" name="phone" @if(isset($params['phone'])) value="{{$params['phone']}}" @endif></label>
                        </div>
                        <div class="span4">
                            <label>订单状态:
                                <select id="selectError3" name="status" class="order_status">
                                    @foreach($status_list as $key=>$note)
                                        <option value="{{$key}}" @if(isset($params['status']) && $key==$params['status']) selected @endif >{{$note}}</option>
                                    @endforeach
                                </select>

                            </label>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            时间:  <input class="input-xlarge focused" id="date01" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:00:00'})" type="text" name="start_time" @if(isset($params['start_time'])) value="{{$params['start_time']}}" @endif >
                            --<input class="input-xlarge focused" id="date01" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:00:00'})" type="text" name="end_time" @if(isset($params['end_time'])) value="{{$params['end_time']}}" @endif >
                            <button type="submit" class="btn btn-primary">查询</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            勾选导出<input type="checkbox" name="export" value="1">
                        </div>
                    </div>
                </form>


                {{--<div>--}}
                    {{--<a href="javascript:"><span id="sendBatchMsg" class="label label-success">发送处理消息</span></a>--}}
                {{--</div>--}}

                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th><input type="checkbox" >订单ID</th>
                        <th>到账金额</th>
                        <th>手续费</th>
                        <th>创建人</th>
                        <th>姓名</th>
                        <th>卡号</th>
                        <th>银行</th>
                        <th>创建时间</th>
                        <th>处理时间</th>
                        <th>状态</th>
                        <th>来源</th>
                        <th>版本号</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($list)){
                        foreach($list as $key => $item){
                    ?>
                    <tr>
                        <td><input type="checkbox" >{{ $item['order_id'] }}</td>
                        <td class="center">{{ $item['cash'] }}</td>
                        <td class="center">{{ $item['handling_fee'] }}</td>
                        <td class="center">{{ $item['phone'] }}</td>
                        <td class="center">{{ $item['name'] }}</td>
                        <td class="center">{{ $item['card_number'] }}</td>
                        <td class="center">{{ $item['bank_name'] }}</td>
                        <td class="center">{{ $item['created_at'] }}</td>
                        <td class="center">{{ $item['updated_at'] }}</td>
                        <td class="center">{{ $item['status_note'] }}</td>
                        <td class="center">{{ $item['app_request'] }}</td>
                        <td class="center">{{ $item['version'] }}</td>
                        <td class="center"><a href="/admin/withdraw/info/{{$item['order_id']}}"><span class="label label-success">查看</span></a></td>
                    </tr>
                    <?php
                    }
                    }
                    ?>
                    <tr>
                        <td class="center" colspan="6"></td>
                        <td class="center" colspan="1">提现总额:</td>
                        <td class="center" colspan="2" >{{$total_cash}} 元</td>
                        <td class="center" colspan="1">手续费:</td>
                        <td class="center" colspan="2">{{$fee_total}} 元</td>
                    </tr>
                    </tbody>
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div><!--/span-->
    </div><!--/row-->

@stop

@section('jsScript')
    {{--<script src="{{ assetUrlByCdn('/') }}js/principalInterest.js"></script>--}}
    <script>
        (function($){

            $(document).ready(function(){

                $("#sendBatchMsg").click(function() {

                    $.ajax({
                        url:'/admin/withdraw/sendBatchMsg',
                        type:'POST',
                        data:{},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            if(result.status==false) {
                                alert(result.msg);
                            } else {
                                alert('发送成功');
                            }
                        }
                    });

                });

                $(".btn-primary").on('click',function(){

                    if( $("input[name='export']").is(':checked') == false ){

                        $("#searchForm").submit();

                        return false;
                    }

                    var status  = $(".order_status option:selected").val();
                    var stime  = $('#date01').val();
                    var etime  = $('#date02').val();

                    if( status == 0 || stime =='' || etime == '' ){

                        alert("查询导出数据的条件太宽泛,请优选查询条件")
                        return false;
                    }

                    $("#searchForm").submit();

                })

            });
        })(jQuery);
    </script>

@endsection