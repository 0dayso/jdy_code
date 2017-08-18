@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">债权列表</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <form name="form1" action="" method="get">
        <div class="control-group">
            企业名称:
                <input type="text" class=" typeahead" name="credit_name" value="<?php echo (isset($pageParam['credit_name']) ? $pageParam['credit_name'] : null);?>" />
                债权人名:
                <input type="text" class=" typeahead" name="loan_username" value="<?php echo (isset($pageParam['loan_username']) ? $pageParam['loan_username'] : null);?>" />
                债权来源:
                <select id="selectSource" name="credit_source" data-rel="chosen">
                    <?php
                        echo '<option value="">全部</option>';
                    foreach($source as $key => $title){
                        echo "<option value=\"$key\" ". (($key == (isset($pageParam['credit_source']) ? $pageParam['credit_source'] : null)) ? 'selected = "selected"' : null) .">$title</option>";
                    }
                    ?>
                </select>

                产品线:
                <select id="selectTag" name="credit_tag" data-rel="chosen">
                    <?php
                    echo '<option value="">全部</option>';
                    foreach($productLine as $key => $title){
                        echo "<option value=\"$key\"  ". (($key == (isset($pageParam['credit_tag']) ? $pageParam['credit_tag'] : null)) ? 'selected = "selected"' : null) .">$title</option>";
                    }
                    ?>
                </select>
                <input style="margin-left: 30px;margin-bottom: 5px;" type="submit" class="btn btn-small btn-primary" value="点击搜索">

        </div>
    </form>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>债权列表</h2>
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
                        <th>ID</th>
                        <th>标签</th>
                        <th>【企业/计划】名称</th>
                        <th>借款金额</th>
                        <th>可用金额</th>
                        <th>利率</th>
                        <th>还款方式</th>
                        <th>到期日期</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                        <th>使用记录</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($list) && !empty($list)){
                    foreach($list as $key => $item){
                    ?>
                    <tr>
                        <td class="center">{{ $item->credit_id }}</td>
                        <td class="center"><?php echo $productLine[$item->credit_tag];?> </td>
                        <td class="center"><?php echo $item->credit_name;?> </td>
                        <td class="center"><?php echo $item->loan_amounts;?> 万元 </td>
                        <td class="center"><?php echo $item->can_use_amounts;?> 万元 </td>
                        <td class="center"><?php echo $item->interest_rate;?> </td>
                        <td class="center"><?php echo isset($repaymentMethod[$item->repayment_method]) ? $repaymentMethod[$item->repayment_method] : $item->repayment_method;?> </td>
                        <td class="center"><?php echo $item->expiration_date;?> </td>
                        <td class="center"><?php echo $item->loan_deadline . ' ['.$dayOrMonth[$item->repayment_method] .']';?> </td>
                        <td class="center"><?php echo $item->contract_no;?> </td>

                        <td class="center"><?php
                            if(!empty($item->projectLinks_array)){
                                foreach($item->projectLinks_array as $credit_key => $projectLink){
                                        echo '<table>
                                                <tr><td>项目Id：'.$projectLink['project_id'].'</td><td>使用金额 : ' . $projectLink['cash'] . '【万元】 </td></tr>
                                              </table>';
                                    }


                                }
                            ?>
                        </td>
                        <?php
                        $edit = '';
                            switch($item->type){
                                case 50:
                                    if($item->source == 10){
                                        $edit = 'factoring';
                                    }
                                    if($item->source == 20){
                                        $edit = 'loan';
                                    }
                                    if($item->source == 30){
                                        $edit = 'housing';
                                    }
                                    if($item->source == 40){
                                        $edit = 'third';
                                    }
                                    break;
                                case 60:
                                    $edit = 'group';break;
                                case 70:
                                    $edit = 'nine';break;
                            }

                        ?>
                        <td class="center">
                            {{--<a class="btn btn-success" href="#">--}}
                            {{--<i class="halflings-icon white zoom-in"></i>--}}
                            {{--</a>--}}
                            <a class="btn btn-info" href="/admin/credit/edit/<?php echo $edit;?>/<?php echo $item->credit_id;?> ">
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

                {!! $list->appends($pageParam)->render() !!}

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop