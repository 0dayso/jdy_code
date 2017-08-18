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
        <li><a href="javascript:void(0)">债权录入</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doCreate/disperse" method="post" enctype="multipart/form-data">
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
        <div class="row-fluid sortable" id="more-record">
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
                                 选择债权模版
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
        <div class="row-fluid sortable" id="handle-record" >
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>手动录入</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>
                <div class="box-content form-horizontal">
                    <fieldset>
                       <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">债权名称</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="credit_name" type="text" name="credit_name" value="{{Input::old('credit_name') }}">
                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">债权金额</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="credit_amounts" name="amounts" autocomplete="off" value="{{ Input::old('amounts') }}"><span class="add-on"> 元 </span>
                                </div>

                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">债权利率</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="interest_rate" name="interest_rate" autocomplete="off" value="{{ Input::old('interest_rate') }}"><span class="add-on"> % </span>
                                </div>

                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">债权期限</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="loan_deadline" name="loan_deadline" autocomplete="off" value="{{ Input::old('loan_deadline') }}"><span class="add-on"> 天 </span>
                                </div>

                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">开始日期</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge datepicker" id="start_time" name="start_time" value="{{ Input::old('start_time') }}">
                            </div>
                        </div>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="loan_contract">到期日期</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge datepicker" id="end_time" name="end_time" value="{{ Input::old('end_time') }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人姓名</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="loan_realname" name="loan_realname" value="{{ Input::old('loan_realname') }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人身份证号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="loan_idcard" name="loan_idcard" value="{{ Input::old('loan_idcard') }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">合同编号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="contract_no" value="{{ Input::old('contract_no') }}">
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


    </form>
@stop
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
           var record_type = $("#record_type").val();
           type_show(record_type);
           function type_show(record_type){
               if(record_type == 1){
                    $("#more-record").show();
                    $("#handle-record").hide();

                }else{
                    $("#more-record").hide();
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
