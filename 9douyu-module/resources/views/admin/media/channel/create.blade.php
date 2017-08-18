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
            <a href="index.html">自媒体管理</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">添加渠道</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" enctype="multipart/form-data" action="/admin/media/channel/doCreate" method="post">

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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加渠道</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>


                <div class="box-content form-horizontal">

                    <fieldset>

                        <div class="control-group">
                            <label class="control-label" for="group_id"> 分组名称 </label>
                            <div class="controls">
                                <select id="bank_id" data-rel="chosen" name="group_id">
                                    @foreach($group_list as $val)
                                        <option value="{{$val['id']}}">{{$val['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="name">渠道名称</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="name" name="name" value="">
                                <span style="color:red;margin-left: 30px;">该名称用于区分用户注册来源,以英文名字为主</span>
                            </div>

                        </div>

                        <div class="control-group">
                            <label class="control-label" for="desc">渠道描述</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="desc" name="desc" value="">

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="url">推广落地页</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="url" name="url" value="">
                                <span style="color:red;margin-left: 30px;">请填完整的URL链接,channel为渠道名称,例如:http://wx.9douyu.com/Novice/extension?channel=jrttdownload</span>

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="package">推广包名</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="package" name="package" value="">
                                <span style="color:red;margin-left: 30px;">安卓的推广渠请填写包名,填完整的包名即可,例如:jiudouyu2.2.106_1411644jrtt_download6.apk</span>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="start_date">推广开始日期</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" name="start_date" id="start_date" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="">
                                <span style="color:red;margin-left: 30px;">包含开始日期当天</span>

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="end_date">推广结束日期</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" name="end_date" id="end_date" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd'})" value="">
                                <span style="color:red;margin-left: 30px;">包含结束日期当天</span>

                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="award_key">奖励键名</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="award_key" name="award_key" value="">
                                <span style="color:red;margin-left: 30px;">对应System_config的配置键名(可为空),例:NOVICE_ACTIVITY_S10</span>
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