@extends('admin/layouts/default')

@section('content')

    <style type="text/css">
        textarea{
            width: 800px;
        }
    </style>

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">充值管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">添加支付银行</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" enctype="multipart/form-data" action="/admin/paylimit/doCreate" method="post">

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

        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加支付银行</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">

                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="bank_id"> 银行名称 </label>
                            <div class="controls">
                                <select id="bank_id" data-rel="chosen" name="bank_id">
                                    @foreach($bank_list as $val)
                                        <option value="{{$val['id']}}">{{$val['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="pay_type"> 支付通道 </label>
                            <div class="controls">
                                <select id="pay_type" data-rel="chosen" name="pay_type">
                                    @foreach($type_list as $key => $val)
                                        <option value="{{$key}}">{{$val['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="limit">单笔限额</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="limit" name="limit" value="{{ Input::old('limit') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="day_limit">单日限额</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="day_limit" name="day_limit" value="{{ Input::old('day_limit') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="month_limit">单月限额</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="month_limit" name="month_limit" value="{{ Input::old('month_limit') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="limit">版本号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="version" name="version" value="{{ Input::old('version') }}">
                            </div>
                        </div>

                    </fieldset>
                </div>


            </div><!--/span-->


        </div><!--/row-->

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button>
            <button type="reset" class="btn">重置</button>
        </div>

    </form>

@stop