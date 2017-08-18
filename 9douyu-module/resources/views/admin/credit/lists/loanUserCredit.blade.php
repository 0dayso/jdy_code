@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">借款人体系债权列表</a></li>
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
                <h2><i class="halflings-icon user"></i><span class="break"></span>借款人体系债权列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="box-content buttons">
                    债权状态:
                    <a class="btn btn-small btn-info" href="/admin/credit/lists/loanUser?status=100">未使用</a>
                    <a class="btn btn-small btn-info" href="/admin/credit/lists/loanUser?status=200">已使用</a>

                </div>
                <form role="form"  method="post" id="credit_batch_publish" >
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>债权ID</th>
                        <th>债权名称</th>
                        <th>借款人类型</th>
                        <th>借款金额</th>
                        <th>姓名</th>
                        <th>债权利率</th>
                        <th>债权状态</th>
                        <th>还款方式</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse( $data as $val )
                            <tr>
                                <td class="center">{{$val->id}}</td>
                                <td class="center">{{$val->credit_name}}</td>
                                <td class="center">{{ $loanType[$val->loan_type] }}</td>
                                <td class="center">{{$val->loan_amounts}} 元</td>
                                <td class="center">{{$val->loan_username}}</td>
                                <td class="center">{{$val->interest_rate}} %</td>
                                <td class="center">
                                    @if($val->status_code == \App\Http\Dbs\Credit\CreditUserLoanDb::STATUS_UNUSED)未发布
                                    @elseif($val->status_code == \App\Http\Dbs\Credit\CreditUserLoanDb::STATUS_ACTIVE)已发布
                                    @else 未知
                                    @endif
                                </td>
                                <td class="center">

                                    {{ $repaymentMethod[ $val->repayment_method ] }}
                                </td>
                                <td class="center">{{$val->loan_deadline}} @if( $val->repayment_method == \App\Http\Dbs\Credit\CreditDb::REFUND_TYPE_BASE_INTEREST ) 天 @else 个月 @endif</td>
                                <td class="center">{{$val->contract_no}}</td>
                                <td class="center">
                                    @if($val->status == \App\Http\Dbs\Credit\CreditUserLoanDb::STATUS_UNUSED)
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
