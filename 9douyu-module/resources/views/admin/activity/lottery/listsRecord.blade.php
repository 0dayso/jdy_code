@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">奖品列表</a></li>
    </ul>
    <div class="row-fluid sortable">

        <div class="box-header">
            <a href="/admin/lottery/addRecord" class="btn btn-primary">补充中奖记录</a>
        </div>

    </div>
        <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>中奖者列表</h2>
            </div>
            <div class="box-content">
                <form action="/admin/lottery/record" method="get" id="searchForm">
                    <div class="row-fluid">
                        <div class="span3">
                            <label> 手 机 号:  <input type="text" name="phone" @if(isset($params['phone'])) value="{{$params['phone']}}" @endif></label>
                        </div>
                        <div class="span3">
                            <label>活动类型:
                                <select id="selectError3" name="activity_id" class="activity_note">
                                    <option value=""  >全部</option>
                                    @foreach($activityNote as $key=>$note)
                                        <option value="{{$key}}" @if(isset($params['aid']) && $key==$params['aid']) selected @endif >{{$note}}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="span3">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-primary">查询</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            勾选导出<input type="checkbox" name="export" value="1">
                        </div>
                    </div>
                </form>

                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>中奖者ID</th>
                        <th>中奖者</th>
                        <th>手机号码</th>
                        <th>活动类型</th>
                        <th>奖品类型</th>
                        <th>奖品名词</th>
                        <th>操作状态</th>
                        <th>中奖时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( empty($list))
                    <tr><td colspan="8">暂无数据</td></tr>
                    @else
                    @foreach($list as $key=>$val)
                    <tr>
                        <td>{{$val['id']}}</td>
                        <td>{{$val['user_id']}}</td>
                        <td>{{$val['user_name']}}</td>
                        <td>{{$val['phone']}}</td>
                        <td>{{isset($activityNote[$val['activity_id']]) ? $activityNote[$val['activity_id']] : '抽奖活动'}}</td>
                        <td>{{$typeList[$val['type']]}}</td>
                        <td>{{$val['award_name']}}</td>
                        @if($val['status'] ==\App\Http\Dbs\Activity\LotteryRecordDb::LOTTERY_STATUS_SUCCESS)
                            <td>审核通过</td>
                        @elseif($val['status'] ==\App\Http\Dbs\Activity\LotteryRecordDb::LOTTERY_STATUS_FAILED)
                            <td>审核不通过</td>
                            @else
                            <td>未审核</td>
                        @endif
                        <td>{{$val['created_at']}}</td>
                        <td>
                            <a href="/admin/lottery/editRecord?r_id={{$val['id']}}"><span class="label label-warning">编辑</span></a>
                            <a href="#"><span class="label label-warning">审核</span></a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                {{--@include('admin/common/page')--}}
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div>
    </div>
@endsection