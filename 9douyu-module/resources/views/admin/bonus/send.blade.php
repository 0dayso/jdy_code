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
            <a href="index.html">{{ $home }}</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">{{ $title }}</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/bonus/doSend" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div>
            @if(Session::has('message'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                    {{ Session::get('message') }}
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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>发放优惠券</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

                <div class="box-content form-horizontal">
                    <fieldset>
                        <!--<div class="control-group">
                            <label for="name" class="control-label">&nbsp;用户Id：</label>
                            <div class="col-lg-6"><input type="text" name="user_id[]" class="form-control"></div>
                        </div>-->

                        <div class="control-group">
                            <label for="name" class="control-label">&nbsp;手机号：</label>
                            <div class="col-lg-6"><input type="text" name="phone" class="form-control"></div>
                        </div>

                        <div class="control-group">
                            <label for="type" class="control-label">使用类型：</label>
                            <div class="col-lg-6">
                                <select name="bonus_id" class="form-control">
                                    @foreach( $bonus_list as $key => $item)
                                        <option value="{{ $item['id'] }}">红包ID:{{ $item['id'] }}&nbsp;{{ $item['name'] }}&nbsp;{{ $item['using_desc'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="name" class="control-label">&nbsp;发放描述：</label>
                            <div class="col-lg-6"><input type="text" name="memo" class="form-control"></div>
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