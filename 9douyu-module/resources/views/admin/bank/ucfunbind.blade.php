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
            <a href="index.html">银行卡管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">先锋支付解绑</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/bankcard/doUnbind" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div>
            @if(Session::has('message'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                    {{ Session::get('message') }}
                </div>
            @endif

        </div>

        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>解绑银行卡</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">

                    <fieldset>



                        <div class="control-group">
                            <label class="control-label" for="limit">手机号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="phone" name="phone" value="{{ Input::old('phone') }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="day_limit">银行卡号</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="card_no" name="card_no" value="{{ Input::old('card_no') }}">
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