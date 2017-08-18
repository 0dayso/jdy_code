@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">投资记录</a></li>
    </ul>
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span4">
                <label>
                    手机号码: <input type="text" name="phone" style="width:100px;"  value="{{$search_form['phone']}}" placeholder="手机号">
                </label>
                <label>
                    投资时间: <input type="text" name="startTime" style="width:100px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="{{$search_form['startTime']}}" placeholder="开始时间"> － <input type="text" name="endTime" style="width:100px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="{{$search_form['endTime']}}" placeholder="结束时间">
                </label>
            </div>
            <div class="span1"><button type="submit" class="btn btn-small btn-primary">点击查询</button></div>
        </div>
    </form>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon user"></i><span class="break"></span>投资记录</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>序号</th>
                    <th>用户id</th>
                    <th>手机号</th>
                    <th>姓名</th>
                    <th>项目编号</th>
                    <th>项目名称</th>
                    <th>债转项目编号</th>
                    <th>投资类型</th>
                    <th>合同编号</th>
                    <th>项目来源</th>
                    <th>真实名称</th>
                    <th>状态</th>
                    <th>出借金额</th>
                    <th>利率</th>
                    <th>项目期限</th>
                    <th>回款方式</th>
                    <th>投资时间</th>
                    <th>投资端</th>
                    <th>红包金额</th>
                    <th>加息券</th>
                   {{-- <th>操作</th>--}}
                    </thead>
                    @if(!empty($investData))
                        @foreach($investData as $invest)
                            <tbody>
                            <td>{{$invest['id']}}</td>
                            <td>{{$invest['user_id']}}</td>
                            <td>@if(!empty($invest['userInfo']['phone'])){{$invest['userInfo']['phone']}}@endif</td>
                            <td>@if(!empty($invest['userInfo']['real_name'])){{$invest['userInfo']['real_name']}}@endif</td>
                            <td>{{$invest['project_id']}}</td>
                            <td>{{$invest['projectInfo']['name'] or null }}</td>
                            <td>{{$invest['assign_project_id'] or null }}</td>
                            <td>{{$invest['invest_type'] or null }}</td>
                            <td>@if(isset($invest['creditInfo'][0]['contract_no']) && !empty($invest['creditInfo'][0]['contract_no'])){{$invest['creditInfo'][0]['contract_no']}}@endif</td>
                            <td>@if(isset($invest['creditInfo']['source']) && !empty($creditSource[$invest['creditInfo']['source']])){{$creditSource[$invest['creditInfo']['source']]}}@endif</td>
                            <td>{{ $invest['creditInfo']['name'] or null }}</td>
                            <td>{{$invest['projectInfo']['status_note'] or null}}</td>
                            <td>{{$invest['cash']}}</td>
                            <td>@if(!empty($invest['projectInfo']['profit_percentage'])){{$invest['projectInfo']['profit_percentage']}}%@endif</td>
                            <td>{{$invest['projectInfo']['invest_time_note'] or null }}</td>
                            <td>{{$invest['projectInfo']['refund_type_note'] or null }}</td>
                            <td>{{$invest['created_at']}}</td>
                            <td>{{$invest['app_request']}}</td>
                            <td>@if($invest['bonus_type'] == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH){{$invest['bonus_value']}} @endif</td>
                            <td>@if($invest['bonus_type'] != \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH){{$invest['bonus_value']}}% @endif</td>
                            </tbody>
                        @endforeach
                    @endif
                </table>
                @include('admin.common.page')
            </div>
        </div>
    </div>
@endsection