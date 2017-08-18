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
    <form role="form" action="/admin/article/category/doCreate" method="post">

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
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>{{ $title }} </h2>
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
                            <h4 class="alert-heading"><i class="halflings-icon volume-up"></i>重要提示：</h4>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date02"> 所属分类: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <select name="parent_id">
                                        <option value="0">根分类</option>
                                        @if( !empty($category) )
                                            @foreach( $category as $key => $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 分类名称: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" name="name" required="required" value="{{ Input::old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 分类别名: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" name="alias" value="{{ Input::old('alias') }}">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 排序: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="number" min="0" name="sort_num" value="{{ Input::old('sort_num') }}">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for=""> 状态 </label>
                            <div class="controls">
                                <select id="status" name="status" data-rel="chosen">
                                    <option value="100" >未发布</option>
                                    <option value="200" >已发布</option>
                                </select>
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
    <script>
        //jQuery
        (function(){
            $(document).ready(function(){

            });
        })(jQuery);
    </script>
@stop

