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
        <li><a href="javascript:void(0)">分组编辑</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" enctype="multipart/form-data" action="/admin/media/group/doEdit" method="post">

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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>分组编辑</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

                <div class="box-content form-horizontal">
                    <fieldset>
                        <input type="hidden" name="id" value="{{$id}}">

                        <div class="control-group">
                            <label class="control-label" for="name">分组名称</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="name" name="name" value="{{ Input::old('name',$name) }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="desc">分组描述</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" id="desc" name="desc" value="{{ Input::old('desc',$desc) }}">
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