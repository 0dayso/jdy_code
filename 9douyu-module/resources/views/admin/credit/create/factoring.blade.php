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
        <li><a href="javascript:void(0)">创建耀盛保理债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doCreate/factoring" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="source"    value="{{ $currentSource }}" />
        <input type="hidden"  name="type"     value="{{ $currentType }}" />
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

        <div class="row-fluid sortable">
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
                                <label class="control-label" for="selectSource"> 债权来源 </label>
                                <div class="controls">
                                    <select id="selectSource" data-rel="chosen" disabled="disabled">
                                            <?php
                                                foreach($source as $key => $title){
                                                    echo "<option value=\"$key\">$title</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="selectType"> 债权样式 </label>
                                <div class="controls">
                                    <select id="selectType"data-rel="chosen" disabled="disabled">
                                        <?php
                                        foreach($type as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="selectTag"> 债权标签 </label>
                                <div class="controls">
                                    <select id="selectTag" name="credit_tag" data-rel="chosen">
                                        <?php
                                        foreach($productLine as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="selectError"> 企业名称 </label>
                                <div class="controls">
                                    <input class="input-xlarge focused" id="focusedInput" type="text" name="company_name" value="{{ Input::old('company_name') }}">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="date02">借款金额</label>
                                <div class="controls">
                                    <div class="input-prepend input-append">
                                        <input type="text" class="input-xlarge" id="date02" name="loan_amounts" autocomplete="off" value="{{ Input::old('loan_amounts') }}"><span class="add-on"> 万元 </span>
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
                                <label class="control-label" for="date01">到期日期</label>
                                <div class="controls">
                                        <input type="text" class="input-xlarge datepicker" id="date01" name="expiration_date" value="{{ Input::old('expiration_date') }}">
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
                                <label class="control-label" for="date01">合同编号</label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" id="date01" name="contract_no" value="{{ Input::old('contract_no') }}">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="date01">借款人信息</label>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_username[]" value=""> 借款人姓名
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value=""> 证件号
                                    </label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value=""> 借款人姓名
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value=""> 证件号
                                    </label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value=""> 借款人姓名
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value=""> 证件号
                                    </label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value=""> 借款人姓名
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value=""> 证件号
                                    </label>
                                </div>
                                <div class="controls">
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_username[]" value=""> 借款人姓名
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]"  value=""> 证件号
                                    </label>
                                </div>
                            </div>

                        </fieldset>
                </div>


            </div><!--/span-->


        </div><!--/row-->



        <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>债权描述</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="selectRiskcale"> riskcalc信用评级 </label>
                            <div class="controls">
                                <select id="selectRiskcale" name="riskcalc_level" data-rel="chosen">
                                    <?php
                                    foreach($risk as $key => $title){
                                        echo "<option value=\"$key\">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleA"> 企业经营规模 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleA" name="company_level" data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="company_level_value" value="{{ Input::old('company_level_value') }}">
                                </label>
                            </div>

                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleB"> 下游企业实力 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleB" name="downstream_level"  data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="downstream_level_value" value="{{ Input::old('downstream_level_value') }}">
                                </label>
                            </div>


                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleC"> 企业盈利能力 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleC" name="profit_level" data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="profit_level_value" value="{{ Input::old('profit_level_value') }}">
                                </label>
                            </div>



                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleD"> 下游回款能力 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleD" name="downstream_refund_level" data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="downstream_refund_level_value" value="{{ Input::old('downstream_refund_level_value') }}">
                                </label>
                            </div>


                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleE"> 资产负债水平 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleE" name="liability_level"  data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="liability_level_value" value="{{ Input::old('liability_level_value') }}" >
                                </label>
                            </div>



                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleF"> 担保方实例 </label>
                                </label>
                                <label class="checkbox inline">
                                    <select id="selectRiskcaleF" name="guarantee_level" data-rel="chosen">
                                        <?php
                                        foreach($star as $key => $title){
                                            echo "<option value=\"$key\">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="guarantee_level_value" value="{{ Input::old('guarantee_level_value') }}">
                                </label>
                            </div>

                            <div class="controls">
                                <label class="checkbox inline">
                                    <label class="control-label" for="selectRiskcaleF">计分规则</label>
                                </label>
                                <label class="checkbox inline">
                                    5星 （90 - 95，包含90和95） <br>
                                    4星 （85 - 89，包含85和89） <br>
                                    3星 （80 - 84，包含80和84） <br>
                                    2星 （75 - 79，包含75和79） <br>
                                    1星（ 70 - 74，包含70和74） <br>
                                </label>
                            </div>

                        </div>
                    </fieldset>
            </div>

        </div>

    </div><!--/row-->



    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>债权信息</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="selectError">关键字</label>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="">
                                </label>
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">债权综述</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credit_desc"> {{ Input::old('credit_desc') }}</textarea>
                            </div>
                        </div>

                    </fieldset>

            </div>

        </div>

    </div><!--/row-->



    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>保理项目描述</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">项目综述</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="factor_summarize"> {{ Input::old('factor_summarize') }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">还款来源</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="repayment_source">{{ Input::old('repayment_source') }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">保理公司意见</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="factoring_opinion"> {{ Input::old('factoring_opinion') }}</textarea>
                            </div>
                        </div>
                    </fieldset>
            </div>

        </div>

    </div><!--/row-->


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>企业背景</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">原债权企业介绍</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="business_background"> {{ Input::old('business_background') }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">原债务企业介绍</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="introduce">{{ Input::old('introduce') }} </textarea>
                            </div>
                        </div>
                    </fieldset>
            </div>

        </div>

    </div><!--/row-->


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>风控措施</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">风控措施</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="risk_control_measure">  {{ Input::old('risk_control_measure') }}  </textarea>
                            </div>
                        </div>


                    </fieldset>
            </div>

        </div>

    </div><!--/row-->


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon edit"></i><span class="break"></span>上传资料</h2>
                <div class="box-icon">
                    {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                </div>
            </div>


            <div class="box-content form-horizontal">
                    <fieldset>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="ckeditor1">基础交易材料</label>
                            <div class="controls">
                                <textarea class="form-control" id="transactional_data" name="transactional_data"> {{ Input::old('transactional_data') }}  </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'transactional_data']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="ckeditor2">保理业务材料</label>
                            <div class="controls">
                                <textarea class="form-control" id="traffic_data" name="traffic_data"> {{ Input::old('traffic_data') }}   </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'traffic_data']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>


                    </fieldset>
            </div>

        </div>

    </div><!--/row-->

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">保存</button>
        <button type="reset" class="btn">重置</button>
    </div>

</form>

@stop