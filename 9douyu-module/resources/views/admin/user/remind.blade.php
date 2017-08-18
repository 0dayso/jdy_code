@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">用户列表</a></li>
    </ul>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <!--start Content-->
    <!--搜索表单-->
    <form action="" method="get" name="user">
        <div class="control-group">
            <div class="span9">
                {{-- <label>--}}
                用户余额:   <input type="text" style="width:120px;" name="balance" value="{{$search_form['balance']}}" placeholder="用户余额">&nbsp;&nbsp;
                未投资天数:   <input type="text" style="width:120px;" name="days" value="{{$search_form['days']}}" placeholder="用户未投资天数">
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
                    <th>用户ID</th>
                    <th>手机</th>
                    <th>姓名</th>
                    <th>身份证号</th>
                    <th>余额</th>
                    <th>注册时间</th>
                    <th>最后投资时间</th>
                    </thead>
                    @if(!empty($user_list))
                        @foreach($user_list as $usrInfo)
                            <tbody>
                            <td>{{$usrInfo['id']}}</td>
                            <td>{{$usrInfo['phone']}}</td>
                            <td>{{$usrInfo['real_name']}}</td>
                            <td>{{$usrInfo['identity_card']}}</td>
                            <td>{{$usrInfo['balance']}}</td>
                            <td>{{$usrInfo['created_at']}}</td>
                            <td>{{$usrInfo['ctime']}}</td>
                            </tbody>
                        @endforeach
                    @else
                        <tbody>
                        <td colspan="9" style="text-align: center">暂无数据 </td>
                        </tbody>
                    @endif
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div>
    </div>
@endsection