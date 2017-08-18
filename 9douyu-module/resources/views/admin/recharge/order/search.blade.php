@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">订单查询</a></li>
    </ul>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>订单查询</h2>
            </div>

            <div class="box-content">
                <form action="/admin/recharge/orderSearch" method="get">
                    <div class="row-fluid">
                        <div class="span4">
                            <label>订单号: <input type="text" name="order_id" @if(isset($order_id)) value="{{$order_id}}" @endif></label>
                        </div>
                        <div class="span4">
                            <button type="submit" class="btn btn-primary">查询</button>
                            @if(!empty($search_msg))
                                <span style="color: #ff1b3e;margin-left: 1.5rem;">{{$search_msg}}</span>
                            @endif
                        </div>
                    </div>
                </form>
                @if(!empty($orderInfo))
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>订单金额</th>
                            <th>下单时间</th>
                            <th>订单状态</th>
                        </tr>
                        </thead>
                        <tr><td>{{$orderInfo['order_id']}}</td><td>{{$orderInfo['cash']}}</td><td>{{$orderInfo['created_at']}}</td><td style="color:#ff0000;">{{$orderInfo['status_note']}}</td></tr>
                    </table>
                @endif
            </div>

        </div>
    </div>
@endsection