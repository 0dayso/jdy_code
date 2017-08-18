@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">掉单查询</a></li>
    </ul>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>掉单查询</h2>
            </div>

            <div class="box-content">
                <form action="/admin/recharge/doMissOrderHandle" method="post">
                    <div class="row-fluid">
                        <div class="span4">
                            <label>订单号: <input type="text" name="order_id" value="{{$orderInfo['order_id']}}" ></label>
                        </div>
                        @if($orderInfo['order_type'] != \App\Http\Dbs\OrderDb::WITHDRAW_TYPE
                         && $orderInfo['status'] != \App\Http\Dbs\OrderDb::STATUS_SUCCESS )
                        <div class="span4">
                            <button type="submit" class="btn btn-primary">处理</button>
                        </div>
                        @endif
                    </div>
                </form>
                @if(!empty($orderInfo))
                    <table class="table table-striped table-bordered bootstrap-datatable">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>订单金额</th>
                            <th>下单时间</th>
                            <th>银行卡号</th>
                            <th>备注</th>
                        </tr>
                        </thead>
                        <tr><td>{{$orderInfo['order_id']}}</td>
                            <td>{{$orderInfo['cash']}}</td>
                            <td>{{$orderInfo['created_at']}}</td>
                            <th>{{$orderInfo['card_number']}}</th>
                            <td style="color:#ff0000;">{{$orderInfo['note']}}</td></tr>
                    </table>
                @endif
            </div>

        </div>
    </div>
@endsection