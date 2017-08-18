@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
        </li>
        <li>
            <a href="/admin/partner/index">合伙人</a>
        </li>
        <li>
            <a href="javascript:void(0);">合伙人详情</a>
        </li>
    </ul>
    <div>

        @if(Session::has('message'))
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>  <i class="icon icon fa fa-warning"></i> 提示! </h4>
                {{ Session::get('message') }}
            </div>
        @endif

    </div>
    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>用户{{$partner['phone']}}-参与合伙人活动详细信息</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>参与时间</th>
                    <th>当前邀请合伙人数</th>
                    <th>当前邀请合伙人待收本金</th>
                    <th>当前佣金率</th>
                    <th>累计佣金收益</th>
                    </thead>
                    @if(!empty($partner))
                            <tbody>
                            <td>{{$partner['real_name']}}</td>
                            <td>{{$partner['phone']}}</td>
                            <td>{{$partner['created_at']}}</td>
                            <td>{{$partner['invite_num']}}</td>
                            <td>{{$partner['principal']}}</td>
                            <td>{{$partner['rate']}}%</td>
                            <td>{{$partner['interest']}}</td>
                            </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>1.邀请合伙人详情（仅展示好友当前数据）</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>被邀请人用户ID</th>
                    <th>合伙人手机号码</th>
                    <th>注册时间</th>
                    <th>邀请方式</th>
                    <th>待收本金</th>
                    <th>操作</th>
                    </thead>
                    @if(!empty($partnerInvite))
                        <tbody>
                        @foreach($partnerInvite as $data)
                            <tr>
                                <td>{{$data['other_user_id'] or ''}}</td>
                                <td>{{$data['phone'] or ''}}</td>
                                <td>{{$data['register_time'] or ''}}</td>
                                <td>@if($data['type'] == 8) 合伙人邀请 @else 邀请好友 @endif</td>
                                <td>{{$data['refund_cash'] or 0}}</td>
                                <td><a class="label label-important" href="/admin/partner/unbindInvite?user_id={{$data['user_id']}}&other_user_id={{$data['other_user_id']}}" onclick="return confirm('确定解绑？')">解绑</a></td>
                            </tr>
                        @endforeach

                        </tbody>
                    @endif
                </table>
                @if(!empty($invPageInfo))
                    @include('scripts/paginate', ['paginate'=>$invPageInfo])
                @endif
            </div>
        </div>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>2.佣金收益记录(暂时只展示30天的数据)</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>


                    <th>日期</th>
                    <th>好友累计待收本金（元）</th>
                    <th>佣金率</th>
                    <th>佣金收益（元）</th>
                    </thead>
                    @if(!empty($partnerIncList))
                        <tbody>
                        @foreach($partnerIncList as $items)
                            <tr>
                                <td>{{date('Y-m-d',strtotime($items['created_at']))}}</td>
                                <td>{{$items['principal']}}</td>
                                <td>{{$items['rate']}}</td>
                                <td>{{$items['balance_change']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>3.佣金转出记录 (暂时只展示30天的数据)</h2>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <th>时间</th>
                    <th>类型</th>
                    <th>金额</th>
                    </thead>
                    @if(!empty($partnerOutList))
                        <tbody>
                        @foreach($partnerOutList as $item)
                            <tr>
                                <td>{{$item['created_at']}}</td>
                                <td>转出</td>
                                <td>{{$item['balance_change']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>4.添加合伙人</h2>
            </div>
            <div class="box-content">
                <form action="/admin/partner/addInvite" method="post" id="checkPost">
                    <label>被邀请人手机号:<input name="invite_phone" id="invite_phone" type="text" value="{{ Input::old('invite_phone') }}"></label>
                    <input name="user_id" type="hidden" value="{{$partner['id']}}" />
                    <input name="phone" type="hidden" id="phone" value="{{$partner['phone']}}" />
                    <input name="sub" type="submit" value="保存"/>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('jsScript')
    <script >

        (function($){
            $(function () {
                $('#checkPost').submit(function(){

                    var invitePhone = $('#invite_phone').val();
                    var phone       = $('#phone').val();

                    if(invitePhone == phone){

                        alert('邀请人手机号与被邀请人手机号不能一致!');

                        return false;

                    }

                    if(!confirm("确定要给"+phone+"添加邀请"+invitePhone+"的关系吗？"))
                    {

                        return false;

                    }

                });
            })
        })(jQuery)

    </script>
@endsection