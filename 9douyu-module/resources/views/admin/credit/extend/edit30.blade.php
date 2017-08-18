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
        <li><a href="javascript:void(0)">编辑房产抵押债权</a></li>
    </ul>
    <!-- start: Content -->
    <form  action="/admin/credit/doEdit/extend" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="{{$credit_id}}" />
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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>债权信息</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

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


                <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">债权综述</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credit_desc"> {{  $obj['credit_desc'] or null}}</textarea>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">房产位置</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="housing_location" value="{{ $obj['housing_location'] or null }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">房产面积</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="housing_area" value="{{ $obj['housing_area'] or null }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date01">房产估值</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="housing_valuation" value="{{ $obj['housing_valuation'] or null }}">
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
                                        echo "<option value=\"$key\"  ". (isset( $obj['sex'] ) && ($key == $obj['sex']) ? 'selected = "selected"' : null) .">$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">年龄</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="age" value="{{ $obj['age'] or null }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">户籍所在地</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="family_register" value="{{ $obj['family_register'] or null }}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date01">居住地</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="date01" name="residence" value="{{ $obj['residence'] or null }}">
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">征信记录</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="credibility"> {{ $obj['credibility'] or null }}</textarea>
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">涉诉情况</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="involved_appeal"> {{ $obj['involved_appeal'] or null }}</textarea>
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
                                <textarea class="cleditor1" id="textarea3"rows="5" cols="80" name="risk_control_message"> {{ $obj['risk_control_message'] or null }}</textarea>
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
                            <label class="control-label" for="ckeditor1">借款人证件</label>
                            <div class="controls">
                                <textarea class="form-control" id="certificates" name="certificates"> {{ $obj['certificates'] or null }}  </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'certificates']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>


                        <div class="control-group hidden-phone">
                            <label class="control-label" for="ckeditor2">房产抵押资料</label>
                            <div class="controls">
                                    <textarea class="form-control" id="mortgage" name="mortgage"> {{ $obj['mortgage'] or null }}   </textarea>
                                @include('scripts.endImgCKEditor',['id'=>'mortgage']){{-- 引入CKEditor编辑器相关JS依赖 --}}
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
