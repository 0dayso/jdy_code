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
        <li><a href="javascript:void(0)">创建九斗鱼借款体系账户债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/create/doLoanUser" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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

      <div class="control-group">
            <div class="controls">
             录入方式： <select id="record_type" data-rel="chosen"  name="record_type" >
                  @foreach($record_type as $key=>$val)
                       <option value="{{$key+1}}" @if(($key+1) == Input::old('record_type'))selected @endif >{{$val}}</option>
                  @endforeach
               </select>
                </div>
      </div>
       <!--批量录入-->
        <div class="row-fluid sortable" id="mutil-record">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>批量录入</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>
                <div class="box-content form-horizontal">
                    <fieldset>
                       <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">选择债权</label>
                            <div class="controls">
                                <input type="file" name="credit_list" value=""/>
                                <br/>
                                <!--<a href="/admin/upload/demo/kuaijin-demo-v1.xlsx"> 选择债权模版 </a>-->
                                 选择定期债权模版
                                <br />
                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <div class="controls">
                             <button type="submit" class="btn btn-primary">保存</button>
                             <button type="reset" class="btn">重置</button>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div><!--/row-->

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

                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="selectError"> 债权名称 </label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput" type="text" name="credit_name" value="{{ Input::old('credit_name') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectError"> 借款人类型 </label>
                            <div class="controls">
                                <select id="selectRepayment" name="loan_type" data-rel="chosen">
                                  @foreach($loanType as $key=>$val)
                                       <option value="{{$key}}" @if(($key) == Input::old('loan_type'))selected @endif >{{$val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">借款金额</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_amounts" autocomplete="off" value="{{ Input::old('loan_amounts') }}"><span class="add-on"> 元 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">平台服务管理费</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="manage_fee" autocomplete="off" value="{{ Input::old('manage_fee') }}"><span class="add-on"> 元 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectError"> 利率 </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused" id="focusedInput" type="text" name="interest_rate" value="{{ Input::old('interest_rate') }}"><span class="add-on"> % </span>
                                </div>
                            </div>
                        </div>

<!--                        <div class="control-group">
                            <label class="control-label" for="selectError"> 项目发布利率 </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused" id="focusedInput" type="text" name="project_publish_rate" value="{{ Input::old('project_publish_rate') }}"><span class="add-on"> % </span>
                                </div>
                            </div>
                        </div>-->

                        <div class="control-group">
                            <label class="control-label" for="selectRepayment"> 还款方式 </label>
                            <div class="controls">
                                <select id="selectRepayment" name="repayment_method" data-rel="chosen">
                                    <?php
                                    foreach($repaymentMethod as $key => $title){
                                        echo "<option value=\"$key\">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">借款期限</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_deadline" value="{{ Input::old('loan_deadline') }}"><span class="add-on" id="loan_deadline"> 天 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">融资时间</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_days" value="{{ Input::old('loan_days') }}"><span class="add-on" id="loan_days"> 天 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">合同编号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="contract_no" value="{{ Input::old('contract_no') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">借款人手机号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_phone" value="{{ Input::old('loan_phone') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">身份证号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_user_identity" value="{{ Input::old('loan_user_identity') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">姓名</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_username" value="{{ Input::old('loan_username') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">银行名称</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="bank_name" value="{{ Input::old('bank_name') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">银行卡号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="bank_card" value="{{ Input::old('bank_card') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">保存</button>
                                <button type="reset" class="btn">重置</button>
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
           var record_type = $("#record_type").val();
           type_show(record_type);
           function type_show(record_type){
               if(record_type == 1){
                    $("#mutil-record").show();
                    $("#handle-record").hide();

                }else{
                    $("#mutil-record").hide();
                    $("#handle-record").show();
                }
            }

          $("#record_type").change(function(){

                var record_type = $(this).val();

                type_show(record_type);
          });
        });
    </script>
@endsection
