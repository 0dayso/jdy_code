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
        <li><a href="javascript:void(0)">编辑耀盛信贷债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doEdit/loan" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="<?php echo $obj->id ?>" />
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
                                        echo "<option value=\"$key\"  ". (($key == $currentSource) ? 'selected = "selected"' : null) ." >$title</option>";
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
                                        echo "<option value=\"$key\"  ". (($key == $obj->credit_tag) ? 'selected = "selected"' : null) .">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectCreditor"> 乙方债权人信息 </label>
                            <div class="controls">
                                <select id="selectCreditor" name="creditor_info" data-rel="chosen">
                                    <?php
                                    foreach($creditor as $key => $title){
                                        echo "<option value=\"$title\"  ". (($title == $obj->creditor_info) ? 'selected = "selected"' : null) .">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectError"> 企业名称 </label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="focusedInput" type="text" name="company_name" value="{{ Input::old('company_name', $obj->company_name) }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">借款金额</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_amounts" autocomplete="off" value="{{ Input::old('loan_amounts', $obj->loan_amounts/10000) }}"><span class="add-on"> 万元 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02">可用金额</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="can_use_amounts" autocomplete="off" value="{{ Input::old('can_use_amounts', $obj->can_use_amounts/10000) }}"><span class="add-on"> 万元 </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectError"> 利率 </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused" id="focusedInput" type="text" name="interest_rate" value="{{ Input::old('interest_rate', $obj->interest_rate) }}"><span class="add-on"> % </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectRepayment"> 还款方式 </label>
                            <div class="controls">
                                <select id="selectRepayment" name="repayment_method" data-rel="chosen">
                                    <?php
                                    foreach($repaymentMethod as $key => $title){
                                        echo "<option value=\"$key\" ". (($key == $obj->repayment_method) ? 'selected = "selected"' : null) .">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">到期日期</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge datepicker" id="date01" name="expiration_date" value="{{ Input::old('expiration_date', $obj->expiration_date) }}">
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="date02">借款期限</label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge" id="date02" name="loan_deadline" value="{{ Input::old('loan_deadline', $obj->loan_deadline) }}"><span class="add-on" id="loan_deadline">
                                        <?php echo ($obj->repayment_method== 10) ? '天' : '月';?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">合同编号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="contract_no" value="{{ Input::old('contract_no', $obj->contract_no) }}">
                            </div>
                        </div>

                        <?php
                        $loan_username      = json_decode($obj->loan_username, true);
                        $loan_user_identity = json_decode($obj->loan_user_identity, true);
                        $keywords           = json_decode($obj->keywords, true);
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="date01">借款人信息</label>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_username[]" value="<?php echo $loan_username[0];?>"> 借款人姓名
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value="<?php echo $loan_user_identity[0];?>"> 证件号
                                </label>
                            </div>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value="<?php echo $loan_username[1];?>"> 借款人姓名
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value="<?php echo $loan_user_identity[1];?>"> 证件号
                                </label>
                            </div>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value="<?php echo $loan_username[2];?>"> 借款人姓名
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value="<?php echo $loan_user_identity[2];?>"> 证件号
                                </label>
                            </div>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01"  name="loan_username[]" value="<?php echo $loan_username[3];?>"> 借款人姓名
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]" value="<?php echo $loan_user_identity[3];?>"> 证件号
                                </label>
                            </div>
                            <div class="controls">
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_username[]" value="<?php echo $loan_username[4];?>"> 借款人姓名
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="loan_user_identity[]"  value="<?php echo $loan_user_identity[4];?>"> 证件号
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
                                        echo "<option value=\"$key\" ". (($key == $obj->riskcalc_level) ? 'selected = "selected"' : null) .">$title</option>";
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
                                            echo "<option value=\"$key\" ". (($key == $obj->company_level) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="company_level_value" value="{{ Input::old('company_level_value', $obj->company_level_value) }}">
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
                                            echo "<option value=\"$key\" ". (($key == $obj->profit_level) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="profit_level_value" value="{{ Input::old('profit_level_value', $obj->profit_level_value) }}">
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
                                            echo "<option value=\"$key\" ". (($key == $obj->liability_level) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="liability_level_value" value="{{ Input::old('liability_level_value', $obj->liability_level_value) }}" >
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
                                            echo "<option value=\"$key\" ". (($key == $obj->guarantee_level) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="guarantee_level_value" value="{{ Input::old('guarantee_level_value', $obj->guarantee_level_value) }}">
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
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="<?php echo $keywords[0];?>">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="<?php echo $keywords[1];?>">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="<?php echo $keywords[2];?>">
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="keywords[]" value="<?php echo isset($keywords[3]) ? $keywords[3] : null;?>">
                                </label>
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">债权综述</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credit_desc"> {{ Input::old('credit_desc', $obj->credit_desc) }}</textarea>
                            </div>
                        </div>

                    </fieldset>

                </div>

            </div>

        </div><!--/row-->



        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>信贷项目描述</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="date01">融资企业</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="financing_company" value="{{ Input::old('financing_company', $obj->financing_company) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">成立时间</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="founded_time" value="{{ Input::old('founded_time', $obj->founded_time) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">项目区域位置</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="program_area_location" value="{{ Input::old('program_area_location', $obj->program_area_location) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">注册资金</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="registered_capital" value="{{ Input::old('registered_capital', $obj->registered_capital) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">年收入</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="annual_income" value="{{ Input::old('annual_income', $obj->annual_income) }}">
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">借款用途</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="loan_use"> {{ Input::old('loan_use', $obj->loan_use) }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">还款来源</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="repayment_source">{{ Input::old('repayment_source', $obj->repayment_source) }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">企业背景</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="background"> {{ Input::old('background', $obj->background) }}</textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">企业财务状况</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="financial"> {{ Input::old('financial', $obj->financial) }}</textarea>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </div>

        </div><!--/row-->

        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>实际控制人信息</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="selectSex">性别</label>
                            <div class="controls">
                                <select id="selectSex" data-rel="chosen" name="sex">
                                    <?php
                                    foreach($sex as $key => $title){
                                        echo "<option value=\"$key\" ". (($key == $obj->sex) ? 'selected = "selected"' : null) .">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">年龄</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="age" value="{{ Input::old('age', $obj->age) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">户籍所在地</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="family_register" value="{{ Input::old('family_register', $obj->family_register) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">居住地</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="residence" value="{{ Input::old('residence', $obj->residence) }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">家庭稳定性</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="home_stability" value="{{ Input::old('home_stability', $obj->home_stability) }}">
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">财产状况</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="esteemn"> {{ Input::old('esteemn', $obj->esteemn) }}</textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">征信记录</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credibility"> {{ Input::old('credibility', $obj->credibility) }}</textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">涉诉情况</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="involved_appeal"> {{ Input::old('involved_appeal', $obj->involved_appeal) }}</textarea>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </div>

        </div><!--/row-->


        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>企业提交资料</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">
                    <fieldset>

                        <?php

                        $submit_data = json_decode($obj->submit_data, true);



                        ?>
                        <div class="control-group hidden-phone">
                            <div class="controls">
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['license']) ? 'checked=""' : null; ?> id="submit_data[license]" name="submit_data[license]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[license]">营业执照认证 </label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['tax_submit_dataority']) ? 'checked=""' : null; ?>  id="submit_data[tax_submit_dataority]" name="submit_data[tax_submit_dataority]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[tax_submit_dataority]">税务登记证认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['organization_code']) ? 'checked=""' : null; ?>  id="submit_data[organization_code]" name="submit_data[organization_code]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[organization_code]">组织机构代码认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['bank_account_cert']) ? 'checked=""' : null; ?>  id="submit_data[bank_account_cert]" name="submit_data[bank_account_cert]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[bank_account_cert]">开户许可证认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['company_rule']) ? 'checked=""' : null; ?>  id="submit_data[company_rule]" name="submit_data[company_rule]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[company_rule]">公司章程认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['capital_verified_report']) ? 'checked=""' : null; ?>  id="submit_data[capital_verified_report]" name="submit_data[capital_verified_report]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[capital_verified_report]">验资报告认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['qualification']) ? 'checked=""' : null; ?>  id="submit_data[qualification]" name="submit_data[qualification]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[qualification]">公司资质认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['representative']) ? 'checked=""' : null; ?>  id="submit_data[representative]" name="submit_data[representative]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[representative]">法定代表人身份证、户口本、结婚证、征信报告认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['controller']) ? 'checked=""' : null; ?>  id="submit_data[controller]" name="submit_data[controller]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[controller]">实际控制人及其配偶身份证、户口本、结婚证、征信报告认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['premise']) ? 'checked=""' : null; ?>  id="submit_data[premise]" name="submit_data[premise]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[premise]">经营场所证明文件认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['residence']) ? 'checked=""' : null; ?>  id="submit_data[residence]" name="submit_data[residence]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[residence]">实际控制人住所证明文件认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['business_info']) ? 'checked=""' : null; ?>  id="submit_data[business_info]" name="submit_data[business_info]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[business_info]">经营资料证明文件认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['house_property']) ? 'checked=""' : null; ?>  id="submit_data[house_property]" name="submit_data[house_property]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[house_property]">房产认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['car']) ? 'checked=""' : null; ?>  id="submit_data[car]" name="submit_data[car]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[car]">车辆认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['onsite']) ? 'checked=""' : null; ?>  id="submit_data[onsite]" name="submit_data[onsite]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[onsite]">实地认证</label>
                                </div>
                                <div class="row"><div class="col-lg-2 textr"><input <?php echo isset($submit_data['other']) ? 'checked=""' : null; ?>  id="submit_data[other]" name="submit_data[other]" type="checkbox"></div>
                                    <label class="control-label col-lg-5"  style="width:600px; text-align:left;"for="submit_data[other]">其他材料认证</label>
                                </div>
                            </div>
                        </div>


                    </fieldset>
                </div>

            </div>

        </div><!--/row-->



        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>风控控制</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">风控信息</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="risk_control_message"> {{ Input::old('risk_control_message', $obj->risk_control_message) }}</textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">风险保障</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="risk_control_security"> {{ Input::old('risk_control_security', $obj->risk_control_security) }}</textarea>
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