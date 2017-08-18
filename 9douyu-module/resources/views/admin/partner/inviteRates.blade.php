@extends('admin/layouts/default')
@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="#">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0);">邀请加息利率</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示! </h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row-fluid sortable ui-sortable">
        <div class="box">
            <div class="box-header">
                <h2><i class="halflings-icon align-justify"></i><span class="break"></span>点击展开/执行添加</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-down"></i></a>
                </div>
            </div>
            <div class="box-content" style="display: none;">
                <form class="form-horizontal" method="post" action="/admin/addInviteRates">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">手机号</label>
                            <div class="controls">
                                <input type="text" class="span2 typeahead" name="phone" placeholder="请填写手机号!">
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="typeahead">加息天数</label>
                            <div class="controls">
                                <input type="text" class="span2 typeahead" name="days" placeholder="例如:3天">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">利率</label>
                            <div class="controls">
                                <input type="text" class="span2 typeahead" name="rate" placeholder="例如:2.0">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">截止日期</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" name="use_expire_time" onclick="WdatePicker()" placeholder="限制用户使用的最后期限,例如:2016-11-30">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->
    </div>

    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header">
                <h2><i class="halflings-icon credit-card"></i><span class="break"></span>信息列表</h2>
            </div>
            <div class="box-content">
                {{--<div class="control-group">
                    <div class="span3">手机号&nbsp;&nbsp;<input name="phone" style="width: 140px;" type="text" value="@if(!empty($search['phone'])){{$search['phone']}}@endif" placeholder="手机号"></div>
                    <div class="span5">加入合伙人时间&nbsp;&nbsp;<input type="text" name="startTime" style="width: 120px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="@if(!empty($search['startTime'])){{$search['startTime']}}@endif" placeholder="开始时间"> － <input type="text" name="endTime" style="width: 120px;" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" id="date02" value="@if(!empty($search['endTime'])){{$search['endTime']}}@endif" placeholder="结束时间"></div>
                    <div class="span3"><button type="submit" class="btn btn-small btn-primary">点击查询</button></div>
                </div>--}}
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <th>用户ID</th>
                        <th>状态</th>
                        <th>天数</th>
                        <th>添加人</th>
                        <th>利率</th>
                        <th>加息开始时间</th>
                        <th>加息截止时间</th>
                        <th>使用有效期</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </thead>
                    @if(!empty($list))
                        @foreach($list as $item)
                          <tr>
                              <td>{{$item['user_id']}}</td>
                              <td>@if( $item['status'] == \App\Http\Dbs\Invite\InviteRatesDb::STATUS_USED ) 已使用 @else 未使用 @endif</td>
                              <td>{{$item['days']}}</td>
                              <td>{{$item['admin_id']}}</td>
                              <td>{{$item['rate']}}</td>
                              <td>{{$item['rate_start_time']}}</td>
                              <td>{{$item['rate_end_time']}}</td>
                              <td>{{$item['use_expire_time']}}</td>
                              <td>{{$item['created_at']}}</td>
                              <td>{{$item['updated_at']}}</td>
                              <td>
                                  <a onclick="return confirm('确定删除？')" href="/admin/delInviteRates?id={{$item['id']}}&user_id={{$item['user_id']}}">
                                      <span class="label label-success">删除</span>
                                  </a>
                              </td>
                          </tr>
                        @endforeach
                    @else
                        <tr><td colspan="10">暂无信息</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection