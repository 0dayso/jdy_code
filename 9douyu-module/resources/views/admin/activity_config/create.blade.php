@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin/project/lists">{{$home}}</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">{{$title}}</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/activity_config/doCreate" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div>
            @if(Session::has('message'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示! </h4>
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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加配置 </h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

                <div class="box-content form-horizontal">
                    <fieldset>
                        <div class="alert alert-block ">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h4 class="alert-heading"><i class="halflings-icon volume-up"></i>重要提示：保存前请确认配置所属分类，不同分类作用范围不一样；</h4>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date02"> 所属分类: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <select name="config_type">
                                        <option value="activity">活动配置模块</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="selectProductLine"> 状态 </label>
                            <div class="controls">
                                <select id="status" name="status" data-rel="chosen">
                                    <option value="1">开启</option>"
                                    <option value="0">关闭</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 描述: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" name="name" required="required" value="{{ Input::old('name') }}">
                                </div>
                            </div>
                        </div>



                        <div class="control-group">
                            <label class="control-label" for="selectProductLine"> 键名 </label>

                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" name="key" required="required" value="{{ Input::old('key') }}"><span class = "add-on " id="appendConfigButton">添加二级配置</span>
                                </div>
                            </div>
                        </div>

                        <div class="control-group value_area">
                            <label class="control-label value_area" for="value">键值 </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input type="text" class="input-xlarge value_area not_null" name="value" value="{{ Input::old('value') }}">
                                </div>
                            </div>
                        </div>


                        <div id="lastDiv"></div>

                    </fieldset>
                </div>

            </div><!--/span-->
        </div><!--/row-->

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button>
            <button type="reset" class="btn">重置</button>
        </div>

    </form>
    <script>
        //jQuery
        (function(){
            $(document).ready(function(){
                //动态追加html
                var config_html =
                        '<div class="control-group append_config_div">' +
                            '<div class="controls">' +
                                '<div class="input-prepend input-append">' +
                                    '<label class="control-label" for="second_key">键名：</label>' +
                                    '<input type="text" class="input-xlarge focused not_null"  name="second_key[]" required="required" value="">' +
                                '</div>' +
                            '</div><br/>' +
                            '<div class="controls">' +
                                '<div class="input-prepend input-append">' +
                                    '<label class="control-label" for="second_value">值：</label>' +
                                    '<input type="text" class="input-xlarge focused not_null"  name="second_value[]" required="required" value="">' +
                                '</div>' +
                            '</div><br/>' +
                            '<div class="controls">' +
                                '<div class="input-prepend input-append">' +
                                    '<label class="control-label" for="second_desc">描述：</label>' +
                                    '<input type="text" class="input-xlarge focused not_null"  name="second_desc[]" value="">' +
                                    '<span class="add-on deleteConfigButton">删除二级配置</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>';


                //删除二级配置
                $('body').on('click', '.deleteConfigButton', function(){
                    $(this).parent().parent().parent().remove();
                    if($('.append_config_div').length < 1){
                        $('.value_area').addClass('not-null').show();
                    }
                });

                //添加二级配置
                $('#appendConfigButton').click(function(){
                    $('.value_area').removeClass('not-null').hide();
                    $('#lastDiv').before(config_html);
                });
            });
        })(jQuery);
    </script>
@stop

