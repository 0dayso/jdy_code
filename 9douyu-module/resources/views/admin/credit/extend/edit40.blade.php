@extends('admin/layouts/default')

@section('content')

    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <style type="text/css">
        textarea{
            width: 800px;
        }
    </style>
    <style type="text/css">
        table.gridtable {
            font-family: verdana,arial,sans-serif;
            font-size:11px;
            color:#333333;
            border-width: 1px;
            border-color: #666666;
            border-collapse: collapse;
        }
        table.gridtable th {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
        }
        table.gridtable td {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
        }
    </style>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">编辑第三方债权</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/credit/doEdit/extend" method="post" enctype="multipart/form-data">

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
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>债权要素</h2>
                    <div class="box-icon">
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
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
                                <input class="input-xlarge focused" type="text" name="company_name" value="@if( isset( $productLine[$obj['credit_tag']] ) ) {{ $productLine[$obj['credit_tag']] }} @else 未定义标签 @endif" disabled>
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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>债权资料</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

                <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">项目描述</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="project_desc"rows="5" cols="80" name="project_desc"> {{ $obj['project_desc'] or null }}</textarea>
                                @include('scripts.endCKEditor',['id'=>'project_desc']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>

                        <div class="control-group hidden-phone">
                            <label class="control-label" for="textarea3">风险控制</label>
                            <div class="controls">
                                <textarea class="cleditor1" id="risk_control"rows="5" cols="80" name="risk_control"> {{ $obj['risk_control'] or null}}</textarea>
                                @include('scripts.endCKEditor',['id'=>'risk_control']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                            </div>
                        </div>
                    </fieldset>
                </div>

            </div>

        </div><!--/row-->
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
                    <label class="control-label" for="loan_contract">上传债权列表</label>
                    <div class="controls">
                        <input type="file" name="credit_list" value=""/>
                        <br />
                        <a href="/admin/upload/demo/kuaijin-demo-v1.xlsx"> 上传文件模板 </a>
                        <br />
                    </div>
                </div>

            </fieldset>
        </div>

        <div class="box-content form-horizontal">
            <fieldset>
                <label class="control-label" for="loan_contract">债权列表</label>
                <div class="controls">
                    <table class="gridtable">
                    <input type='hidden' name ='credit_list' value='{{ $obj['credit_list'] or null }}' />
                        <?php
                        if(!empty($obj['credit_list'])){
                            $creditInfo = \GuzzleHttp\json_decode($obj['credit_list'], true);
                            foreach($creditInfo as $k => $record){
                                if(isset($record['realname']) && isset($record['identity_card']) && isset($record['amount']) && isset($record['time']) && isset($record['refund_time']) && isset($record['address'])){
                                    if(!is_array($record['realname']) && !is_array($record['identity_card']) && !is_array($record['amount']) && !is_array($record['time']) && !is_array($record['refund_time']) && !is_array($record['address'])){
                                        echo "<tr><td>". $record['realname'] . "</td> <td>". $record['identity_card'] ."</td><td>". $record['amount'] ."</td><td>". $record['time'] ."</td><td>". $record['refund_time'] ."</td><td>". $record['address'] ."</td></tr>";
                                    }
                                }
                            }
                        }
                        ?>

                    </table>
                </div>
            </fieldset>
        </div>




        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button>
            <button type="reset" class="btn">重置</button>
        </div>

    </form>

@stop
