@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">充值记录</a></li>
    </ul>


    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header" data-original-title="">
                <h2><i class="halflings-icon edit"></i><span class="break"></span>充值导出(目前支持财务部需求数据导出)</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content" style="display:none;">
                <form class="form-horizontal" method="post" action="/admin/recharge/exportTotal" id="exportOrder">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">充值时间</label>
                            <div class="controls">
                                <input type="text" class="span2" name="start_time" id="start_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="开始时间" />
                                ——
                                <input type="text" class="span2" name="end_time" id="end_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="结束时间" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">订单状态:</label>
                            <div class="controls">
                                <select name="status" class="order_status">
                                    @foreach($status_list as $key=>$note)
                                        <option value="{{$key}}" @if(isset($params['status']) && $key==$params['status']) selected @endif >{{$note}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">渠道列表:</label>
                            <div class="controls">
                                <select name="channel" class="order_type">
                                    @foreach($channel_list as $key=>$val)
                                        <option value="{{$key}}" @if(isset($params['pay_type']) && $key==$params['pay_type']) selected @endif >{{$val['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-warning">导出</button>
                            <button type="reset" class="btn">取消</button></div>
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->

    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2>
                    <i class="halflings-icon align-justify"></i>
                    <span class="break"></span>对账标示说明
                    <a href="#" class="btn-minimize" title="点击查看"><i class="halflings-icon chevron-down"></i></a>
                    <a href="#" class="btn-close" title="点击关闭"><i class="halflings-icon remove"></i></a>
                </h2>
            </div>
            <div class="box-content" style="display: none;">
                <pre>
                    1,✔️ 表示今天之前的订单成功对账或者没有异常的订单;
                    2,× 表示异常未处理的定点杆
                    3,? 表示 截止前一天到现在没有对账的订单
                    4,所有订单标示只有未处理的异常订单未准确数据,所有未进行对账的数据请及时进行对账操作
                </pre>
            </div>
        </div><!--/span-->
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>充值管理</h2>
            </div>

            <div class="box-content">

                <form action="/admin/recharge/lists" method="get" id="searchForm">
                    <div class="row-fluid">
                        <div class="span4">
                            <label> 手 机 号:  <input type="text" name="phone" @if(isset($params['phone'])) value="{{$params['phone']}}" @endif></label>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span4">
                            <label> 订 单 号:  <input type="text" name="order_id" @if(isset($params['order_id'])) value="{{$params['order_id']}}" @endif></label>
                        </div>
                    </div>
                    <div class="row-fluid">
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
                        <div class="span4">
                            <label>渠道列表:
                                <select id="selectError3" name="pay_type" class="order_type">
                                    @foreach($channel_list as $key=>$val)
                                        <option value="{{$key}}" @if(isset($params['pay_type']) && $key==$params['pay_type']) selected @endif >{{$val['name']}}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="span12">
                            起始时间: <input type="text" class="input-xlarge datepicker" id="date01" name="start_time" @if(isset($params['start_time'])) value="{{$params['start_time']}}" @endif>
                            --<input type="text" class="input-xlarge datepicker" id="date02" name="end_time" @if(isset($params['end_time'])) value="{{$params['end_time']}}" @endif>

                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary">查询</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            勾选导出<input type="checkbox" name="export" value="1">
                        </div>
                    </div>
                </form>


                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>订单号</th>
                        <th>金额</th>
                        <th>姓名</th>
                        <th>手机号</th>
                        <th>交易流水号</th>
                        <th>时间</th>
                        <th>状态</th>
                        <th>充值类型</th>
                        <th>银行名称</th>
                        <th>银行卡号</th>
                        <th>三端来源</th>
                        <th>版本号</th>
                        <th>备注</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if( empty($list) )
                            <tr><td class="center" colspan="13">暂无信息</td></tr>
                        @else
                            @foreach($list as $key => $item)
                                <tr>
                                    <td class="center">{{$item['order_id']}} </td>
                                    <td class="center">{{$item['cash']}}</td>
                                    <td class="center">{{$item['name']}}</td>
                                    <td class="center">{{$item['phone']}}</td>
                                    <td class="center">{{$item['trade_no']}}</td>
                                    <td class="center">
                                        {{$item['created_at']}}<br/>
                                        {{$item['updated_at']}}
                                    </td>
                                    @if($item['status'] ==\App\Http\Dbs\OrderDb::STATUS_SUCCESS )
                                        <td class="center">{{$item['status_note']}}
                                            @if($orderStatus[$item['order_id']] ==\App\Http\Dbs\Order\CheckOrderRecordDb::CHECK_ORDER_FAILED )
                                            <span class="btn btn-danger btn-mini"><i class="icon-remove" alt="异常未处理的订单"></i></span>
                                            @elseif($orderStatus[$item['order_id']] ==\App\Http\Dbs\Order\CheckOrderRecordDb::CHECK_ORDER_SUCCESS && $checkTime > strtotime($item['created_at']))
                                            <span class="btn btn-success btn-mini"><i class="icon-ok" title="未对账或者成功的订单"></i></span>
                                            @else
                                            <span class="btn btn-warning btn-mini"><i class="icon-warning-sign" ></i></span>
                                            @endif
                                        </td>
                                    @else
                                        <td class="center">{{$item['status_note']}}<i class="icon-ok"></i> </td>
                                    @endif
                                    <td class="center">{{isset($item['type_name']) ? $item['type_name']: ""}} </td>
                                    <td class="center">{{isset($item['bank_name']) ? $item['bank_name'] : ""}} </td>
                                    <td class="center">{{$item['card_number']}} </td>
                                    <td class="center">{{$item['app_request']}} </td>
                                    <td class="center">{{$item['version']}} </td>
                                    <td class="center">{{$item['note']}} </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="center" colspan="10"></td>
                                <td class="center" colspan="1">总计：</td>
                                <td class="center" colspan="2">{{$total_cash}} 元</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div><!--/span-->

    </div><!--/row-->
@section('jsScript')
    <script src="{{assetUrlByCdn('/theme/metro/My97DatePicker/WdatePicker.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){

                if( $("input[name='export']").is(':checked') == false ){

                    $("#searchForm").submit();

                    return false;
                }

                var status  = $(".order_status option:selected").val();

                var type    = $(".order_type option:selected").val();
                var stime  = $('#date01').val();
                var etime  = $('#date02').val();

                if( status == 0 && type==0  && stime =='' && etime == '' ){

                    alert("查询导出数据的条件太宽泛,请优选查询条件")
                    return false;
                }

                $("#searchForm").submit();

            })
        });
    </script>
@endsection
@stop