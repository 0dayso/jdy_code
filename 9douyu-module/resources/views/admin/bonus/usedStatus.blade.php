@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">红包使用数据</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2>
                    <i class="halflings-icon align-justify"></i>
                    <span class="break"></span>使用说明
                    <a href="#" class="btn-minimize" title="点击查看"><i class="halflings-icon chevron-down"></i></a>
                    <a href="#" class="btn-close" title="点击关闭"><i class="halflings-icon remove"></i></a>
                </h2>
            </div>
            <div class="box-content" style="display: none;">
                <pre>
                    1,该功能默认是不进行数据查询;
                    2,请选择时间查询的开始时间、结束时间;
                    3,统计的时间段建议不要超出一个月
                    4,加息券的统计数据暂不进行统计
                </pre>
            </div>
        </div><!--/span-->
    </div>
    <form action="" method="get" name="usedStatus">
        <div class="control-group">
            <div class="span5">
                {{-- <label>--}}
                统计时间: <input type="text" name="start_time"  onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="{{$params['start_time']}}" placeholder="开始时间"> －
                <input type="text" name="end_time"  onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="{{$params['end_time']}}" placeholder="结束时间">
                {{--</label>--}}
            </div>
            <div class="span1"><button type="submit" class="btn btn-small btn-primary">点击查询</button></div>
        </div>
    </form>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>红包使用数据</h2>
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
                        <th>红包ID</th>
                        <th>名称</th>
                        <th>类型</th>
                        <th>利率/金额</th>
                        <th>发放数据</th>
                        <th>使用数据</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($bonusList) && !empty($bonusList))
                    @foreach($bonusList as $key => $item)
                    <tr>
                        <td class="center">{{$item['bonus_id']}}</td>
                        <td class="center">{{$item['name']}}</td>
                        @if( $item['type'] ==\App\Http\Dbs\Bonus\BonusDb::TYPE_CASH )
                        <td class="center">红包</td>
                        @elseif($item['type'] ==\App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_INTEREST)
                        <td class="center">定期加息券</td>
                        @else
                        <td class="center">零钱计划加息券</td>
                        @endif
                        <td class="center">{{$item['rate_money']}}</td>
                        <td class="center">{{$item['total']}}</td>
                        <td class="center">{{$item['used_total']}}</td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                        <td class="center" rowspan="2">合计</td>
                        <td class="center">红包</td>
                        <td class="center" colspan="4" >{{$bonus_total}}元</td>
                    </tr>
                    <tr>
                        <td class="center">加息券</td>
                        <td class="center" colspan="4" >加息券数据过于复杂,暂无展示</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop