@extends('admin/layouts/default')

@section('content')
    <script src="{{assetUrlByCdn('/theme/metro/My97DatePicker/WdatePicker.js')}} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="#">{{$home}}</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">{{$title}}</a></li>
    </ul>
    <!-- start: Content -->
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header" data-original-title="">
                <h2><i class="halflings-icon edit"></i><span class="break"></span>预约导出(目前支持财务部需求数据导出)</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>

            <div class="box-content" style="display:none;">

                <form class="form-horizontal" method="get" action="/admin/project/orderExport">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">统计类型</label>
                            <div class="controls">
                                <input type="radio" class="typeahead span4" name="export_type" value="1" checked/> 项目满标时间&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="typeahead span4" name="export_type" value="2"/>  项目完结时间
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">时间</label>
                            <div class="controls">
                                <input type="text" class="span2" name="start_time" id="start_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="开始时间" />
                                ——
                                <input type="text" class="span2" name="end_time" id="end_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="结束时间" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">邮箱</label>
                            <div class="controls">
                                <input type="text" class="typeahead span4" name="email" placeholder="请填写您的邮箱地址"/>
                            </div>
                        </div>
                        <div class="control-group" id="is_before_type" style="display: none;">
                            <label class="control-label" for="typeahead">提前回款</label>
                            <div class="controls">
                                <input type="radio" class="typeahead span4" name="is_before" value="0" checked/>  完结项目 &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="typeahead span4" name="is_before" value="1" /> 提前回款

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">姓名</label>
                            <div class="controls">
                                <input type="text" class="typeahead span4" name="name" placeholder="请填写您的姓名" />
                            </div>
                        </div>


                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">提交预约</button>
                            <button type="reset" class="btn">取消</button></div>
                    </fieldset>
                </form>

            </div>

        </div><!--/span-->

    </div>
    <div class="row-fluid sortable ui-sortable">
        <div class="box span12">
            <div class="box-header" data-original-title="">
                <h2><i class="halflings-icon edit"></i><span class="break"></span>搜索表单</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content" style="display:none;">
                <form class="form-horizontal" method="get" action="/admin/project/lists">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">请填写项目ID</label>
                            <div class="controls">
                                <input type="text" class="typeahead span4" name="id" placeholder="可以填写多个id,以英文逗号隔开" @if(isset($_GET['id']))value="{{$_GET['id']}}"@endif />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">项目满标时间</label>
                            <div class="controls">
                                <input type="text" class="span2" name="start_time" id="start_time" @if(isset($_GET['start_time']))value="{{$_GET['start_time']}}"@endif onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="开始时间" />
                                ——
                                <input type="text" class="span2" name="end_time" id="end_time" @if(isset($_GET['end_time']))value="{{$_GET['end_time']}}"@endif onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" placeholder="结束时间" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="typeahead">债权人姓名</label>
                            <div class="controls">
                                <input type="text" class="typeahead span4" name="loan_username"  value="{{ $pageParam['loan_username'] or null }}"/>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="typeahead">债权企业名称</label>
                            <div class="controls">
                                <input type="text" class="typeahead span4" name="credit_name" value="{{ $pageParam['credit_name'] or null }}"/>
                            </div>
                        </div>


                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">提交搜索</button>
                            <button type="reset" class="btn">取消</button></div>
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->

    </div>

    <div class="box-content buttons">
        项目类型:
        @foreach($projectLineArr as $key => $val)
            <a href="{{$val}}" class="btn btn-small btn-success">{{$key}}</a>
        @endforeach
        <br />
        <br />
        项目状态:
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=100">待审核</a>
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=110">未通过</a>
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=120">未发布</a>
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=130">投资中</a>
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=150">还款中</a>
            <a class="btn btn-small btn-info" href="/admin/project/lists?status=160">已完结</a>

    </div>
    <div>

        @if(Session::has('message'))
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4>  <i class="icon icon fa fa-warning"></i> 提示! </h4>
                {{ Session::get('message') }}
            </div>
        @endif

    </div>


    <div class="row-fluid sortable ui-sortable">

        <div class="box span12">
            <div class="box-header">
                <h2>
                    <i class="halflings-icon align-justify"></i>
                    <span class="break"></span>
                    {{$productLineNote}}
                </h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th><input type="checkbox" /></th>
                        <th>ID</th>
                        <th>名称</th>
                        <th>类型</th>
                        <th>融资额</th>
                        <th width="80">项目期限</th>
                        <th>还款方式</th>
                        <th>利率</th>
                        <th width="80">基础利率</th>
                        <th width="80">附加利率</th>
                        <th>状态</th>
                        <th>发布时间</th>
                        <th>到期日</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if( !empty($projectList) )
                        <?php
                        $credit = [];
                        ?>
                        @foreach($projectList as $key => $val)
                            <tr>
                                <td><input type="checkbox" name="id" value="{{$val['id']}}" /></td>
                                <td class="center">{{$val['id']}}</td>
                                <td class="center">{{$val['name']}}</td>
                                <td class="center">{{$val['product_line_note']}}</td>
                                <td class="center">{{$val['total_amount']}}</td>
                                <td class="center">{{$val['invest_time_note']}}</td>
                                <td class="center">{{$val['refund_type_note']}}</td>
                                <td class="center">{{$val['profit_percentage']}}%</td>
                                <td class="center">{{$val['base_rate']}}%</td>
                                <td class="center">{{$val['after_rate']}}%</td>
                                <td class="center">{{$val['status_note']}}</td>
                                <td class="center">{{$val['publish_at']}}</td>
                                <td class="center">{{$val['end_at']}}</td>
                                <td class="center">{{$val['created_at']}}</td>
                                <td class="center">{{$val['updated_at']}}</td>
                                <td class="center">
                                    <a href="/project/detail/{{ $val['id'] }}" target="_blank"><span class="label label-success">详情</span></a>
                                    @if($val['status'] == 100)
                                        <a href="javascript:void(0)" data-value="{{ $val['id'] }}" class="doPass" ><span class="label label-warning">审核通过</span></a>
                                    @elseif($val['status'] == 120)
                                        <a href="javascript:void(0)" data-value="{{ $val['id'] }}" class="doPublish" ><span class="label label-warning">发布</span></a>
                                    @elseif($val['status'] == 150 && $val['product_line'] != 300)
                                        <a href="javascript:void(0)" data-value="{{ $val['id'] }}" class="doBeforeRefundRecord" ><span class="label label-fail">提前还款</span></a>
                                    @endif
                                    @if($val['new'])
                                        <a href="/admin/project/updateNew/{{ $val['id'] }}"><span class="label label-warning">编辑</span></a>
                                    @else
                                        <a href="/admin/project/update/{{ $val['id'] }}"><span class="label label-warning">编辑</span></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="20">暂无信息</td></tr>
                    @endif
                    </tbody>
                </table>
                <div class="pagination pagination-centered" id="pagination-ajax">
                    @include('scripts/paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div><!--/span-->
    </div>

@endsection
@section('jsScript')
    <script>
        (function($){
            $(document).ready(function(){

                /**
                 * 审核通过
                 */
                $(".doPass").click(function(){
                    var projectId = $(this).attr('data-value');
                    $.ajax({
                        url:'/admin/project/doPass',
                        type:'POST',
                        data:{id:projectId},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            console.log(result);
                            if(result.status == false) {
                                alert(result.msg);
                                return false;
                            } else {
                                alert(result.msg);
                                location.reload();
                            }
                        }
                    });
                });

                /**
                 * 项目发布
                 */
                $(".doPublish").click(function(){
                    if(!confirm('确定发布此项目吗？')) return false;
                    var projectId = $(this).attr('data-value');
                    $.ajax({
                        url:'/admin/project/doPublish',
                        type:'POST',
                        data:{id:projectId},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            console.log(result);
                            if(result.status == false) {
                                alert(result.msg);
                                return false;
                            } else {
                                alert(result.msg);
                                location.reload();
                            }
                        }
                    });
                });

                /**
                 * 项目删除
                 */
                $(".doDelete").click(function(){
                    if(!confirm('确定删除此项目吗？')) return false;

                    var projectId = $(this).attr('data-value');
                    $.ajax({
                        url:'/admin/project/doDelete',
                        type:'POST',
                        data:{id:projectId},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            console.log(result);
                            if(result.status == false) {
                                alert(result.msg);
                                return false;
                            } else {
                                alert(result.msg);
                                location.reload();
                            }
                        }
                    });
                });

                /**
                 * 提前还款
                 */
                $(".doBeforeRefundRecord").click(function(){
                    if(!confirm('此项目确定提前还款吗？')) return false;

                    var projectId = $(this).attr('data-value');
                    $.ajax({
                        url:'/admin/project/beforeRefundRecord',
                        type:'POST',
                        data:{project_id:projectId},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            console.log(result);
                            if(result.status == false) {
                                alert(result.msg);
                                return false;
                            } else {
                                alert(result.msg);
                                location.reload();
                            }
                        }
                    });
                });

                $("input[name='export_type']").click(function () {

                    var val =   $(this).val();
                    if( val == 1){
                        $("#is_before_type").hide();
                    }
                    if( val == 2){
                        $("#is_before_type").show();
                    }
                })

            });
        })(jQuery);
    </script>
@endsection