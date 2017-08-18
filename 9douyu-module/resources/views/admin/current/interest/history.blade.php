@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">活期利息列表</a></li>
    </ul>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span9">
                {{-- <label>--}}
                手机号:   <input type="text" style="width:120px;" name="phone" value="{{$search_form['phone']}}" placeholder="选择手机号">&nbsp;&nbsp;
                计息时间: <input type="text" name="startTime" style="width:100px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="{{$search_form['startTime']}}" placeholder="开始时间"> － <input type="text" name="endTime" style="width:100px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="{{$search_form['endTime']}}" placeholder="结束时间">
                {{--</label>--}}
            </div>
            <div class="span1"><button type="submit" class="btn btn-small btn-primary">点击查询</button></div>
        </div>
    </form>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon user"></i><span class="break"></span>用户列表</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>ID</th>
                    <th>用户ID</th>
                    <th>手机</th>
                    <th>姓名</th>
                    <th>利率</th>
                    <td>利息</td>
                    <th>计息日期</th>
                    <th>计息本金</th>
                    <th>利息来源</th>
                    <th>创建时间</th>
                    </thead>
                    @if(!empty($interest_list))
                        @foreach($interest_list as $interest)
                            <tbody>
                            <td>{{ $interest['id'] }}</td>
                            <td>{{ $interest['user_id'] }}</td>
                            <td>@if(!empty($interest['phone'])) {{ $interest['phone'] }} @endif</td>
                            <td>@if(!empty($interest['real_name'])) {{ $interest['real_name'] }} @endif</td>
                            <td>{{ $interest['rate'] }}</td>
                            <td>{{ $interest['interest'] }}</td>
                            <td>{{ $interest['interest_date'] }}</td>
                            <td>{{ $interest['principal'] }}</td>
                            <td>@if(!empty($interest['type'])) {{ $interest['type']==1 ? '活期基准利息' : '加息券利息' }} @endif</td>
                            <td>{{ $interest['created_at'] }}</td>
                            </tbody>
                        @endforeach
                    @endif
                </table>
                @include('admin/common/page')
            </div>
        </div>
    </div>
@endsection