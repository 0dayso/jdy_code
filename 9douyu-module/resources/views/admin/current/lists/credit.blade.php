@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">零钱计划债权列表</a></li>
    </ul>

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
                <h2><i class="halflings-icon user"></i><span class="break"></span>零钱计划债权列表</h2>
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
                        <th>债权名称</th>
                        <th>借款金额</th>
                        <th>年利率</th>
                        <th>还款方式</th>
                        <th>到期日期</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                        <th>添加时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($paginate['data']) && !empty($paginate['data'])){
                        foreach($paginate['data'] as $key => $item){
                    ?>
                    <tr>
                        <td class="center"><?php echo $item['name'];?> </td>
                        <td class="center"><?php echo $item['total_amount'];?> 万元 </td>
                        <td class="center"><?php echo $item['percentage'];?> %</td>
                        <td class="center"><?php echo $refundTypeList[$item['refund_type']];?> </td>
                        <td class="center"><?php echo $item['end_time'];?> </td>
                        <td class="center"><?php echo $item['invest_time'];?>  月</td>
                        <td class="center"><?php echo $item['contract_no'];?> </td>
                        <td class="center"><?php echo $item['created_at'];?> </td>
                        <td class="center">
                            <a href="/admin/current/credit/detail/lists/{{ $item['id'] }}"><span class="label label-success">详情</span></a>
                            <a href="/admin/current/credit/edit/{{ $item['id'] }}"><span class="label label-warning">编辑</span></a>
                        </td>

                    </tr>
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                </table>
                @include('scripts/paginate', ['paginate'=>$paginate])

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop