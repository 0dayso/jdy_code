@extends('admin/layouts/default')

@section('content')

    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <style type="text/css">
        textarea{
            width: 800px;
        }
    </style>

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">编辑车贷债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doEdit/all" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="{{ $obj['id'] }}" />
        <div>
            @if(Session::has('fail'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                    {{ Session::get('fail') }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="row-fluid sortable" id="handle-record">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>债权要素</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="selectError"> 债权来源 </label>
                            <div class="controls">
                                <select name="source" id="source" data-rel="chosen" disabled>
                                  @foreach($source as $key=>$val)
                                       <option value="{{$key}}" @if(($key) == $obj['source'])selected @endif >{{$val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError"> 债权类型 </label>
                            <div class="controls">
                                <select name="type" data-rel="chosen" disabled>
                                  @foreach($type as $key=>$val)
                                       <option value="{{$key}}" @if(($key) == $obj['type'])selected @endif >{{$val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError"> 债权标签 </label>
                            <div class="controls">
                                <select name="credit_tag" data-rel="chosen" disabled>
                                  @foreach($productLine as $key=>$val)
                                       <option value="{{$key}}" @if(($key) == $obj['credit_tag'])selected @endif >{{$val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>

                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="selectError"> 企业名称 </label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput" type="text" name="company_name" value="{{ $obj['company_name'] }}" disabled>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="date02">借款金额</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_amounts" autocomplete="off" value="{{ $obj['loan_amounts'] }}" disabled><span class="add-on"> 元 </span>
                                </div>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="selectError"> 利率 </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused" id="focusedInput" type="text" name="interest_rate" value="{{ $obj['interest_rate'] }}" disabled><span class="add-on"> % </span>
                                </div>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="selectRepayment"> 还款方式 </label>
                            <div class="controls">
                                <select id="selectRepayment" name="repayment_method" data-rel="chosen" disabled>
                                    @foreach($repaymentMethod as $key=>$val)
                                        <option value="{{$key}}" @if(($key) == $obj['repayment_method'])selected @endif >{{$val}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">到期日期</label>
                            <div class="controls" >
                                    <input type="text" class="input-xlarge datepicker" id="date01" name="expiration_date" value="{{ $obj['expiration_date'] }}" disabled>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">借款期限</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_deadline" value="{{ $obj['loan_deadline'] }}" disabled>
                                    <span class="add-on" id="loan_deadline"> {{ $obj['repayment_method'] == \App\Http\Dbs\Credit\CreditDb::REFUND_TYPE_BASE_INTEREST ? '天' : '月'}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">合同编号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="contract_no" value="{{ $obj['contract_no'] }}" disabled>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="date01">借款人姓名</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_username" value="{{ $obj['loan_username'] }}" disabled>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">借款人身份证号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_user_identity" value="{{ $obj['loan_user_identity'] }}" disabled>
                            </div>
                        </div>

                        <div id="tao_shop" style='margin-top:40px;' >
                        <div class="control-group">
                            <label class="control-label" for="date01">借款用途</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_use" value="{{ $obj['loan_use'] }}" disabled>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人年龄</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="age" value="{{ $obj['age'] }}" disabled>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人户籍</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_hometown" value="{{ $obj['loan_hometown'] }}" disabled>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人性别</label>
                            <div class="controls">
                                <select name="sex" data-rel="chosen" disabled>
                                  @foreach($sex as $key=>$val)
                                       <option value="{{$key}}" @if(($key) == $obj['sex'])selected @endif >{{$val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人手机号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_phone" value="{{ $obj['loan_phone'] }}" disabled>
                            </div>
                        </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                            </div>
                        </div>
                    </fieldset>
                </div>

            </div><!--/span-->

        </div><!--/row-->
    </form>

@stop
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
          var source = $("#source").val();
          credit_extend_show( source );

          $("#source").change( function(){
                var source = $(this).val();
                credit_extend_show( source );
            });

           function credit_extend_show( source )
           {
              var tao_shop = {{\App\Http\Dbs\Credit\CreditDb::SOURCE_TAO_SHOP}};
              if( source == tao_shop ){
                $( "#tao_shop").show();
              }else{
                $( "#tao_shop").hide();
              }
           }
        });
    </script>
@endsection
