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
        <li><a href="javascript:void(0)">编辑耀盛保理债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doEdit/extend" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="{{ $credit_id }}" />
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
            <?php
            $keywords           = isset( $obj['keywords'] ) ? $obj['keywords'] : ['','','',''];
            ?>
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
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">债权来源</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $source[$obj['source']] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">债权类型</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $type[$obj['type']] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">债权标签</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="@if( isset( $productLine[$obj['credit_tag']] ) ) {{ $productLine[$obj['credit_tag']] }} @else 未定义标签 @endif " disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">企业名称</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['company_name'] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">借款金额</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['loan_amounts'] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">利率</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['interest_rate'] }}" disabled>
                        </div>
                    </div>                <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">还款方式</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $repaymentMethod[$obj['repayment_method']] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">到期日期</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['expiration_date'] }}" disabled>
                        </div>
                    </div>
                    <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">借款期限</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['loan_deadline'] }}{{ $obj['repayment_method'] == \App\Http\Dbs\Credit\CreditDb::REFUND_TYPE_BASE_INTEREST ? '天' : '个月' }}" disabled>
                        </div>
                    </div>                <div class="box-content form-horizontal">
                        <label class="control-label" for="textarea3">合同编号</label>
                        <div class="controls">
                            <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['contract_no'] }}" disabled>
                        </div>
                    </div>
                    @if(!empty($obj['loan_username']))
                        <div class="box-content form-horizontal">
                            <label class="control-label" for="textarea3">借款人姓名</label>
                            <div class="controls">
                                <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['loan_username'] }}" disabled>
                            </div>
                        </div>
                    @endif

                    @if(!empty($obj['loan_user_identity']))
                        <div class="box-content form-horizontal">
                            <label class="control-label" for="textarea3">借款人身份证号</label>
                            <div class="controls">
                                <input class="input-xlarge focused" type="text" name="company_name" value="{{ $obj['loan_user_identity'] }}" disabled>
                            </div>
                        </div>
                    @endif
                </fieldset>
            </div>

        </div>

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
                                        echo "<option value=\"$key\" ". (isset( $obj['riskcalc_level'] ) && ($key == $obj['riskcalc_level']) ? 'selected = "selected"' : null) .">$title</option>";
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
                                            echo "<option value=\"$key\" ". ( isset( $obj['company_level'] ) && ($key == $obj['company_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="company_level_value" value="{{ $obj['company_level_value'] or null  }}">
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
                                            echo "<option value=\"$key\" ". (isset( $obj['downstream_level'] ) && ($key == $obj['downstream_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="downstream_level_value" value="{{$obj['downstream_level_value'] or null }}">
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
                                            echo "<option value=\"$key\" ". (isset( $obj['profit_level'] ) && ($key == $obj['profit_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="profit_level_value" value="{{  $obj['profit_level_value'] or null}}">
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
                                            echo "<option value=\"$key\" ". (isset( $obj['downstream_refund_level'] ) && ($key == $obj['downstream_refund_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="downstream_refund_level_value" value="{{  $obj['downstream_refund_level_value'] or null }}">
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
                                            echo "<option value=\"$key\" ". (isset( $obj['liability_level'] ) && ($key == $obj['liability_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="liability_level_value" value="{{  $obj['liability_level_value'] or null }}" >
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
                                            echo "<option value=\"$key\" ". (isset( $obj['guarantee_level'] ) && ($key == $obj['guarantee_level']) ? 'selected = "selected"' : null) .">$title</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                                <label class="checkbox inline">
                                    <input type="text" class="input-xlarge" id="date01" name="guarantee_level_value" value="{{ $obj['guarantee_level_value'] or null}}">
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
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credit_desc"> {{  $obj['credit_desc'] or null }}</textarea>
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
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="factor_summarize"> {{  $obj['factor_summarize'] or null }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">还款来源</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="repayment_source">{{  $obj['repayment_source'] or null }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">保理公司意见</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="factoring_opinion"> {{  $obj['factoring_opinion'] or null }}</textarea>
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
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="business_background"> {{ $obj['business_background'] or null  }} </textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">原债务企业介绍</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="introduce">{{  $obj['introduce'] or null}} </textarea>
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
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="risk_control_measure">  {{  $obj['risk_control_measure'] or null }}  </textarea>
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
                                <textarea class="form-control" id="transactional_data" name="transactional_data"> {{  $obj['transactional_data'] or null }}  </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'transactional_data']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="ckeditor2">保理业务材料</label>
                            <div class="controls">
                                <textarea class="form-control" id="traffic_data" name="traffic_data"> {{  $obj['traffic_data'] or null }}   </textarea>
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
