@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} " xmlns="http://www.w3.org/1999/html"></script>


    <style type="text/css">
        textarea{
            width: 800px;
        }
    </style>

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">提现管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">提现处理</a></li>
    </ul>

    {{--<h3 style="color:red;">9:30以后今天0点之前的提现订单无法取消</h3>--}}
    <!-- start: Content -->
    <form role="form" enctype="multipart/form-data" action="/admin/withdraw/doEdit" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div>
            @if(Session::has('errors'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                    {{ Session::get('errors') }}
                </div>
            @endif

            {{--@if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>

                    </ul>
                </div>
            @endif--}}
        </div>

        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>提现处理</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">

                    <fieldset>

                        <input type="hidden" name="order_id" value="{{$order_id}}">
                        <input type="hidden" name="create_time" value="{{$created_at}}">

                        <div class="control-group">
                            <label class="control-label" for="bank_name"> 汇款金额 </label>
                            <div class="controls">
                                {{$cash}}元 （已扣除手续费{{$handling_fee}}元）
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="type_name"> 订单号 </label>
                            <div class="controls">
                                {{ $order_id }}
                            </div>
                        </div>


                        <div class="control-group">
                            <label class="control-label" for="status">提现状态</label>

                            <div class="controls">
                                @if($status == \App\Http\Dbs\OrderDb::STATUS_ING)

                                    @if($isCanCancelWithDraw)
                                        <label class="radio">
                                            <input type="radio" name="status" value="{{\App\Http\Dbs\OrderDb::STATUS_CACLE}}" />取消提现
                                        </label>
                                    @else
                                        <label>待处理</label>
                                    @endif

                                @elseif($status == \App\Http\Dbs\OrderDb::STATUS_DEALING)

                                    <label class="radio">
                                        <input type="radio" name="status" value="{{\App\Http\Dbs\OrderDb::STATUS_SUCCESS}}"/>已提现
                                    </label>

                                    <label class="radio">
                                        <input type="radio" name="status" value="{{\App\Http\Dbs\OrderDb::STATUS_ERROR}}" />提现失败
                                    </label>

                                @elseif($status == \App\Http\Dbs\OrderDb::STATUS_CACLE)
                                    <label>手动取消</label>
                                @elseif($status == \App\Http\Dbs\OrderDb::STATUS_SUCCESS)
                                    <label>成功</label>
                                @else
                                    <label>失败</label>
                                @endif

                            </div>

                        </div>

                        <div class="control-group">
                            <label class="control-label" for="day_limit">备注</label>
                            <div class="controls">
                                <textarea rows="6" cols="10" name="note" style="width: 219px; height: 65px;">@if($status == \App\Http\Dbs\OrderDb::STATUS_CACLE){{$note}}@endif</textarea>
                            </div>
                        </div>

                    </fieldset>
                </div>


            </div><!--/span-->

        </div><!--/row-->

        <div class="form-actions">
            @if($isShow)
            <button type="submit" class="btn btn-primary">保存</button>
            @endif
        </div>

    </form>

@stop