@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">红包/加息券列表</a></li>
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
                <h2><i class="halflings-icon user"></i><span class="break"></span>红包/加息券列表</h2>
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
                        <th>名称</th>
                        <th>类型</th>
                        <th>使用类型</th>
                        <th>利率/金额</th>
                        <th>期限</th>
                        <th>零钱计划计息天数</th>
                        <th>发布开始时间</th>
                        <th>发布结束时间</th>
                        <th>状态</th>
                        <th>是否可转让</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($list) && !empty($list)){
                    foreach($list as $key => $item){
                            $moneyRate = ($item->rate == 0) ? $item->money.'元' : $item->rate.'%';
                    ?>
                    <tr>
                        <td class="center"><?php echo $item->id;?> </td>
                        <td class="center"><?php echo $item->name;?> </td>
                        <td class="center"><?php echo $type[$item->type];?> </td>
                        <td class="center"><?php echo $useType[$item->use_type];?> </td>
                        <td class="center"><?php echo $moneyRate;?> </td>
                        <td class="center"><?php echo $item->expires;?>  </td>
                        <td class="center"><?php echo $item->current_day;?> </td>
                        <td class="center"><?php echo $item->send_start_date;?> </td>
                        <td class="center"><?php echo $item->send_end_date;?> </td>
                        <td class="center"><?php echo $status[$item->status];?> </td>
                        <td class="center"><?php echo $assignment[$item->give_type];?> </td>
                        <td class="center"><?php echo $item->created_at;?> </td>

                        <td class="center">

                            @if ($item->status != \App\Http\Dbs\Bonus\BonusDb::STATUS_PUBLIC)
                                <a href="/admin/bonus/publish/{{ $item->id }}"><span class="label label-success">发布</span></a>
                            @endif
                            <a href="/admin/bonus/update/{{ $item->id }}"><span class="label label-warning">编辑</span></a>
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