@extends('pc.common.layout')

@section('title', '合同下载')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="m-myuser">
        <!-- account begins -->
        @include('pc.common.leftMenu')

        <div class="m-content mb30">
            <!--选项卡1导航-->
            <ul class="m-tabnav1">
                <li class="m-addstyle"><a href="javascript:;">合同下载</a></li>
            </ul>
            <div class="m-showbox pt40">
                <p class="h30px text-warning">提示：合同生成期间，如同一时间人数较多，会造成生成延时，一般时间不会超过10分钟；合同下载后将直接存于电脑中</p>
                <!--选项卡1内容1-->
                <div class="m-tabtitle">

                    <div class="m-tabbox">
                        <div>
                            <table class="table table-theadbg table-textcenter mb26px">
                                <thead>
                                <tr>
                                    <td>项目名称</td>
                                    <td>出借金额</td>
                                    <td>时间</td>
                                    <td>合同下载</td>

                                </tr>
                                </thead>
                                <tbody>
                                @if( !empty($list) )
                                    @foreach( $list as $item )
                                        <tr>
                                            <td>{{ $item['name'].' '.$item['project_id'] }}</td>
                                            <td>{{ number_format($item['cash'],2) }}</td>
                                            <td>{{ $item['created_at'] }}</td>
                                            @if(isset($contract[$item['id']]))
                                            <td><a href="javascript:;" data-value="{{ $item['id'] }}" class="link-active " contract_status='success' id="create_contract{{ $item['id'] }}">下载</a></td>
                                            @else
                                            <td><a href="javascript:;" data-value="{{ $item['id'] }}" class="link-active " contract_status='doing' id="create_contract{{ $item['id'] }}">生成</a></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td>暂无信息</td></tr>
                                @endif
                                </tbody>
                            </table>

                            <div class="web-page">
                                @if( !empty($list) )
                                    @include('scripts/paginate', ['paginate'=>$paginate])
                                @endif
                            </div>
                            <form method="post" action="/contract/doCreateDownLoad" id="contractDown">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="invest_id" id="investId" value="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection
@section('jspage')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function($){

            $(document).delegate(".link-active",'click',function(){

                    var lock    =   $(this).attr('contract_status')
                    if( lock =='lock') return false
                    if( lock =='fail') {

                        alert('合同下载失败，请联系客服！')
                        return false;
                    }

                    if( lock =='success'){

                        var investId = $(this).attr('data-value');

                        $('#investId').val(investId);

                        $('#contractDown').submit();

                        return false;
                    }

                    var investId = $(this).attr('data-value');

                    var _token   =   $("input[name='_token']").val();

                    $(this).attr('contract_status','lock')

                    $.ajax({
                        url      :"/contract/doCreateDownLoad",
                        data     :{invest_id:investId,_token:_token,dataType:'json'},
                        dataType :'json',
                        type     :'post',
                        success : function(json) {

                            if( json.status == true ){
                                $("#create_contract" + investId).html('生成中').removeClass('create_contract');
                                checkContract(investId);
                                alert('您的合同正在生成中，请耐心等待');
                           }else {
                                alert(json.msg)
                            }
                        }, error : function() {
                            alert('网络异常，清稍后再试')
                        }
                    });
                })
            var checkContract   =   function (investId) {

                status      =   $("#create_contract" + investId).attr('contract_status');

                if( status == 'success' ) return false;

                var obj     =   $("#create_contract" + investId);

                var time2   =   20;

                var time_s  =   setInterval(function () {
                    time2--;
                    var _token      =   $("input[name='_token']").val();
                    $.ajax({
                        url      :"/contract/checkContractStatus",
                        data     :{invest_id:investId,_token:_token},
                        dataType :'json',
                        type     :'post',
                        success : function(json) {
                            if( json.status == true){
                                clearInterval(time_s);
                                obj.html('下载')
                                obj.attr('contract_status','success');
                            }
                        }
                    });
                    if( time2 <0 ){
                        obj.html('生成失败');
                        obj.attr('contract_status','fail');
                        clearInterval(time_s);
                    }
                },3000);
            }
        })(jQuery);
    </script>
@endsection
