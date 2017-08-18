@extends('admin/layouts/default')

@section('content')
    <script src="{{assetUrlByCdn('/theme/metro/My97DatePicker/WdatePicker.js')}}"></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">零钱计划数据统计</a></li>
    </ul>
    <div class="row-fluid sortable">
        <div class="box span12">


            <div class="row-fluid sortable ui-sortable">
                <div class="box span12">
                    <div class="box-header" data-original-title="">
                        <h2><i class="halflings-icon edit"></i><span class="break"></span>零钱计划数据统计</h2>
                        <div class="box-icon">
                            <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                            <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                            <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                        </div>
                    </div>
                    <div class="box-content" style="">
                        <form class="form-horizontal" method="get" action="/admin/current/fund">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="typeahead">起始日期</label>
                                    <div class="controls">
                                        <input type="text" class="span2" name="start_time" id="start_time" value="{{ $_GET['start_time'] or null }}" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="开始时间">
                                        ——
                                        <input type="text" class="span2" name="end_time" id="end_time" value="{{ $_GET['end_time'] or null }}" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="结束时间">
                                        —— <input type="checkbox" name='export' value="1" >(勾选导出)
                                    </div>
                                </div>


                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">提交</button>
                            </fieldset>
                        </form>

                    </div>
                </div><!--/span-->

            </div>

            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>转入</th>
                        <th>转出</th>
                        <th>零钱计划金额</th>
                        <th>当日利息</th>
                        <th>总利息</th>
                        <th>加息券成本</th>
                        <th>利率</th>
                    </tr>
                    </thead>
                    @if( empty($list) )
                        <tbody>
                            <td colspan="8"> 暂无信息 </td>
                        </tbody>
                    @else
                        @foreach($list as $key=>$rate)
                            <tbody>
                                <td>{{$rate['date']}}</td>
                                <td>{{$rate['invest_in']}}</td>
                                <td>{{$rate['invest_out']}}</td>
                                <td>{{$rate['cash']}} </td>
                                <td>{{$rate['day_interest']}}</td>
                                <td>{{$rate['interest']}}</td>
                                <td>{{$rate['cost']}}</td>
                                <td>{{$rate['rate']}}%</td>
                            </tbody>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection