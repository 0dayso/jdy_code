@extends('admin/layouts/default')
@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">掉单处理</a></li>
    </ul>
    <h2 style="color:red;">该操作会根据订单号给用户添加同等额度的金额,请谨慎操作!</h2>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>掉单处理</h2>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div>
                @if(Session::has('message'))
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                        {{ Session::get('message') }}
                    </div>
                @endif
            </div>


            <div class="box-content">
                <form action="/admin/recharge/missOrderSearch" method="post">
                    <div class="row-fluid">
                        <div class="span4">
                            <label>订单号: <input type="text" name="order_id" ></label>
                        </div>
                        <div class="span4">
                            <button type="submit" class="btn btn-primary">查询</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection