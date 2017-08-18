@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">耀盛信贷列表</a></li>
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
                <h2><i class="halflings-icon user"></i><span class="break"></span>常规信贷债权列表</h2>
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
                        <th>标签</th>
                        <th>企业名称</th>
                        <th>借款金额</th>
                        <th>利率</th>
                        <th>还款方式</th>
                        <th>到期日期</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($list) && !empty($list)){
                    foreach($list as $key => $item){
                    ?>
                    <tr>
                        <td class="center"><?php echo $productLine[$item->credit_tag];?> </td>
                        <td class="center"><?php echo $item->company_name;?> </td>
                        <td class="center"><?php echo $item->loan_amounts;?> 万元 </td>
                        <td class="center"><?php echo $item->interest_rate;?> </td>
                        <td class="center"><?php echo $repaymentMethod[$item->repayment_method];?> </td>
                        <td class="center"><?php echo $item->expiration_date;?> </td>
                        <td class="center"><?php echo $item->loan_deadline . ' ['.$dayOrMonth[$item->repayment_method] .']';?> </td>
                        <td class="center"><?php echo $item->contract_no;?> </td>

                        <td class="center">
                            {{--<a class="btn btn-success" href="#">--}}
                            {{--<i class="halflings-icon white zoom-in"></i>--}}
                            {{--</a>--}}
                            <a class="btn btn-info" href="/admin/credit/edit/loan/<?php echo $item['id'];?> ">
                                <i class="halflings-icon white edit"></i>
                            </a>
                            {{--<a class="btn btn-danger" href="#">--}}
                            {{--<i class="halflings-icon white trash"></i>--}}
                            {{--</a>--}}
                        </td>
                    </tr>
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                </table>

                {!! $list->render() !!}

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop