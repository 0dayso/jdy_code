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
        <li><a href="javascript:void(0)">编辑项目集债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doEdit/group" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="<?php echo $obj->id ?>" />
        <input type="hidden" name="source"    value="{{ $currentSource }}" />
        <input type="hidden" name="type" value="{{ $currentType }}" />
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
                                <select id="selectSource" data-rel="chosen"  name="source">
                                    <?php
                                    foreach($source as $key => $title){
                                        echo "<option value=\"$key\"  ". (($key == $obj->source) ? 'selected = "selected"' : null) .">$title</option>";
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
                                        echo "<option value=\"$key\"  ". (($key == $currentType) ? 'selected = "selected"' : null) ." >$title</option>";
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
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">融资企业</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="financing_company"  value="{{ Input::old('financing_company', $obj->financing_company) }}">
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">项目区域位置</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="program_area_location"  value="{{ Input::old('program_area_location', $obj->program_area_location) }}">
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">借款用途</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="loan_use"  value="{{ Input::old('loan_use', $obj->loan_use) }}">
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">还款来源</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="repayment_source"  value=" {{ Input::old('repayment_source', $obj->repayment_source) }} ">
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
                            <label class="control-label" for="loan_contract">借款合同</label>
                            <div class="controls">
                                <textarea class="form-control" id="loan_contract" name="loan_contract"> {{ Input::old('loan_contract', $obj->loan_contract) }}   </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'loan_contract']){{-- 引入CKEditor编辑器相关JS依赖 --}}
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