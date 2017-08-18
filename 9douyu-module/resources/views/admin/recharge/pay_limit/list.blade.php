@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">支付限额列表</a></li>
    </ul>

    <a style="margin-bottom: 5px;" class="btn btn-small btn-success" href="/admin/paylimit/create">添加银行</a>
    <br/>

    <div style="margin-bottom: 20px;color:red;">0表示无限额 </div>

    <div class="box-content">

        <form action="/admin/paylimit/lists" method="get">
            <div class="row-fluid">
                <div class="span4">银行名称:
                    <select name="bank_id" class="bank_id">
                        <option value="0">请选择银行</option>

                        @foreach($bankList as $val)
                            <option value="{{$val['id']}}" @if(isset($params['bank_id']) && $val['id']==$params['bank_id']) selected @endif >{{$val['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="span4">
                    <button type="submit" class="btn btn-primary">查询</button>
                </div>
            </div>
        </form>

    </div>

    <h3 style="color:red;">说明:当APP端版本号大于指定版本号时,该渠道才显示</h3>

    支付通道 : <a href="/admin/paylimit/lists/all/{{$params['bank_id']}}"><span class="label label-success">全部</span></a>


    @foreach($typeList as $list)

        <a href="/admin/paylimit/lists/{{ $list['alias'] }}/{{$params['bank_id']}}"><span class="label label-success">{{$list['name']}}</span></a>

    @endforeach

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
                <h2><i class="halflings-icon user"></i><span class="break"></span>支付限额列表</h2>
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
                        <th>支付通道</th>
                        <th>单笔限额</th>
                        <th>单日限额</th>
                        <th>单月限额</th>
                        <th>维护开始时间</th>
                        <th>维护结束时间</th>
                        <th>版本号</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($paginate['data']) && !empty($paginate['data'])){
                        foreach($paginate['data'] as $key => $item){
                    ?>
                    <tr>
                        <td class="center">{{ $item['bank_name'] }} </td>
                        <td class="center">{{ $item['type_name'] }} </td>
                        <td class="center">{{ $item['limit'] }}     </td>
                        <td class="center">{{ $item['day_limit'] }} </td>
                        <td class="center">{{ $item['month_limit'] }}</td>
                        <td class="center">{{ $item['start_time'] }}</td>
                        <td class="center">{{ $item['end_time'] }}  </td>
                        <td class="center">{{ $item['version'] }}   </td>
                        <td class="center">
                            @if($item['status'] == \App\Http\Dbs\Order\PayLimitDb::STATUS_FORBIDDEN)
                                不可用
                            @else
                                可用
                            @endif
                        </td>
                        <td class="center">
                            <a href="/admin/paylimit/edit/{{ $item['id'] }}"><span class="label label-success">编辑</span></a>
                            <a href="/admin/paylimit/doEditStatus/{{ $item['id'] }}/{{$item['status']}}">
                                <span class="label label-warning">
                                    @if($item['status'] == \App\Http\Dbs\Order\PayLimitDb::STATUS_FORBIDDEN)
                                        启用
                                    @else
                                        禁用
                                    @endif

                                </span>
                            </a>
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