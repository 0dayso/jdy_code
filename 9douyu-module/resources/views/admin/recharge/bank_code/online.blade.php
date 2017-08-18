@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">网银银行列表</a></li>
    </ul>


    <div style="margin-bottom: 20px"></div>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>网银银行列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>


            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>银行名称</th>
                        <th>银行编码</th>
                        <th>当前状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($list as $item)

                        <tr>
                            <td class="center">{{$item['name']}} </td>
                            <td class="center">{{$item['code']}}</td>
                            <td class="center">
                                @if($item['status'] == \App\Http\Dbs\Bank\BankListDb::STATUS_SHOW)
                                    显示
                                @else
                                    隐藏
                                @endif
                            </td>
                            <td class="center">
                                <a href="/admin/bankcode/online/doEditStatus/{{ $item['bank_id'] }}/{{$item['status']}}/{{$item['type']}}">
                                    <span class="label label-warning">
                                        @if($item['status'] == \App\Http\Dbs\Bank\BankListDb::STATUS_SHOW)
                                            隐藏
                                        @else
                                            显示
                                        @endif

                                    </span>
                                </a>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop