@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">邀请管理</a></li>
    </ul>
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span4">
                <label>
                    邀请人手机号：<input type="text" name="phone" style="width:200px;"  value="{{$phone}}" placeholder="手机号">
                    {{--手机号<input type="text" name="phone" style="width:100px;"  value="{{$search_form['phone']}}" placeholder="手机号">--}}
                </label>
            </div>
            <div class="span1"><button type="get" class="btn btn-small btn-primary">点击查询</button></div>
        </div>
    </form>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon user"></i><span class="break"></span>邀请记录</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>邀请人</th>
                    <th>被邀请人</th>
                    <th>来源</th>
                    <th>类型</th>
                    <th>注册时间</th>
                     <th>操作</th>
                    </thead>
                    @if(!empty($inviteData))
                        <tbody>
                            @foreach($inviteData as $item)
                                <tr>
                                    <td>{{$phone}}</td>
                                    <td>{{$item['phone'] or ''}}</td>
                                    <td>{{$item['type_str']}}</td>
                                    <td>{{$item['user_type_str']}}</td>
                                    <td>{{$item['register_time'] or ''}}</td>
                                    {{--<td><a href="/admin/invite/doDel">解除邀请关系</a></td>--}}
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
                <div class="pagination">
                @if(!empty($pageInfo))
                    @include('scripts/paginate', ['paginate'=>$pageInfo])
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection