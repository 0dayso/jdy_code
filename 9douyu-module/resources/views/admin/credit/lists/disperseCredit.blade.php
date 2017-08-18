@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">债权列表</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>新分散债权列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="box-content buttons">
                    {{--项目类型:
                    <a href="/admin/project/lists?product_line=JSX" class="btn btn-small btn-success">九省心</a>
                    <a href="/admin/project/lists?product_line=JAX" class="btn btn-small btn-success">九安心</a>
                    <a href="/admin/project/lists?product_line=SDF" class="btn btn-small btn-success">闪电付息</a>
                    <br>
                    <br>--}}
                    债权状态:
                    <a class="btn btn-small btn-info" href="/admin/credit/lists/disperse?status=100">待发布</a>
                    <a class="btn btn-small btn-info" href="/admin/credit/lists/disperse?status=200">已发布</a>
                    <a class="btn btn-small btn-info" href="/admin/credit/lists/disperse?status=300">已到期</a>
                    @if( $status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_UNUSED)
                        <a class="btn btn-small  btn-success batch-publish" >批量发布</a>
                    @endif

                </div>
                <form role="form"  method="post" id="credit_batch_publish" >
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        @if( $status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_UNUSED)
                        <th><input type="checkbox" id="check_all" /></th>
                        @endif
                        <th>债权ID</th>
                        <th>债权名称</th>
                        <th>借款金额</th>
                        <th>可用金额</th>
                        <th>姓名</th>
                        <th>身份证号</th>
                        <th>债权利率</th>
                        <th>债权状态</th>
                        <th>开始日期</th>
                        <th>到期日期</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse( $data as $val )
                            <tr>
                                @if( $val->status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_UNUSED)
                                <td><input type="checkbox"  name="id[]" value="{{$val->id}}" /></td>
                                @endif
                                <td class="center">{{$val->id}}</td>
                                <td class="center">{{$val->credit_name}}</td>
                                <td class="center">{{$val->amounts}} 元</td>
                                <td class="center">{{$val->usable_amount}} 元</td>
                                <td class="center">{{$val->loan_realname}}</td>
                                <td class="center">{{$val->loan_idcard}}</td>
                                <td class="center">{{$val->interest_rate}} %</td>
                                <td class="center">
                                    @if($val->status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_UNUSED)未发布
                                    @elseif($val->status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_ACTIVE)已发布
                                    @elseif($val->status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_EXPIRE)已过期
                                    @else 未知
                                    @endif
                                </td>
                                <td class="center">{{$val->start_time}}</td>
                                <td class="center">{{$val->end_time}}</td>
                                <td class="center">{{$val->loan_deadline}} 天</td>
                                <td class="center">{{$val->contract_no}}</td>
                                <td class="center">
                                    @if($val->status == \App\Http\Dbs\Credit\CreditDisperseDb::STATUS_CODE_UNUSED)
                                    <a class="btn btn-small btn-info doPublish" data-value = "{{ $val->id }}" >
                                        发布
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="13">暂无信息</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </form>

                {!! $data->render() !!}
            </div>
        </div><!--/span-->

    </div><!--/row-->
@endsection
@section('jsScript')
    <script>
        (function($){
            $(document).ready(function(){
                /**
                 * 项目发布
                 */
                $(".doPublish").click(function(){
                    if(!confirm('确定发布此债权吗？')) return false;
                    var creditId = $(this).attr('data-value');
                    $.ajax({
                        url:'/admin/credit/doOnline/disperse',
                        type:'POST',
                        data:{id:creditId},
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

               $("#check_all").click(function(){
                    if( $(this).attr("checked") )
                    {
                    $(".checker span ").addClass("checked");
                    $("input[name='id[]']").attr("checked", true);
                    }else{
                    $(".checker span ").removeClass("checked");
                    $("input[name='id[]']").attr("checked", false);
                    }
                });
                //批量发布
                $(".batch-publish").click(function(){
                    if(!confirm('确定要批量发布债权吗？')) return false;
                    var credit_arr = [];
                     $("input[name='id[]']:checked").each( function() {
                        credit_arr.push( $(this).val() );
                     });
                    $.ajax({
                        url:'/admin/credit/doOnline/disperse',
                        type:'POST',
                        data:{id: credit_arr },
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

            });
        })(jQuery);
    </script>
@endsection
