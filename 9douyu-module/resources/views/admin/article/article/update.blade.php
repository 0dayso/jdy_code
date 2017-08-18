@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <style type="text/css">
        textarea{
            width: 200px;
        }
    </style>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin/project/lists">{{$home}}</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">{{$title}}</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/article/doUpdate" method="post" enctype="multipart/form-data">

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
                        <input type="hidden" name="id" value="{{ $info['id'] }} ">
                        <div class="control-group">
                            <label class="control-label" for="date02"> 所属分类: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <select name="category_id">
                                        <option value="0">根分类</option>
                                        @if( !empty($category) )
                                            @foreach( $category as $key => $item)
                                                <option value="{{ $item['id'] }}" @if($item['id'] == $info['category_id']) selected @endif>{{ $item['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">是否推送</label>
                            <div class="controls">
                                <label class="radio">
                                    <span class=""><input name="is_push" value="0" @if( $info['is_push'] == 0  ) checked @endif type="radio"></span>
                                    不推送
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <span class=""><input name="is_push" value="1" @if( $info['is_push'] == 1 ) checked @endif type="radio"></span>
                                    推送
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">文章类型</label>
                            <div class="controls">
                                <label class="radio">
                                    <span class=""><input name="type_id" value="1" @if( !empty($info['type_id']) && $info['type_id']==1 ) checked @endif type="radio"></span>
                                    文章资讯
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <span class=""><input name="type_id" value="2"  @if( !empty($info['type_id']) && $info['type_id']==2 ) checked @endif type="radio"></span>
                                    媒体合作
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 标题: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" name="title" value="{{ Input::old('title', $info['title'] ) }}">
                                </div>
                            </div>
                        </div>



                        <div class="control-group">
                            <label class="control-label" for="date02"> 图片上传: </label>
                            <div class="controls">
                                <input type="hidden" name="picture_id" value="{{ $info['picture_id'] }}">
                                <input id="img" type="file" size="45" name="img" class="input" value="{{ Input::old('img') }}" >
                                <a class="button" id="buttonUpload" >上传</a>
                                @if(!empty($pic['path']))
                                    <img src="{{ assetUrlByCdn('resources/'.$pic['path']) }}" width="40" height="40">
                                @endif
                            </div>

                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 布局: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <select name="layout">
                                        @if( !empty($layout) )
                                            @foreach( $layout as $key => $item)
                                                <option value="{{ $item['value'] }}" @if($item['value'] == $info['layout']) selected @endif >{{ $item['text'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-content form-horizontal">
                            <fieldset>

                                <div class="control-group hidden-phone">
                                    <label class="control-label" for="ckeditor2">摘要</label>
                                    <div class="controls">
                                        <textarea class="form-control" id="description" name="intro" style="height: 5px;"> {{ Input::old('intro', $info['intro']) }}  </textarea>
                                        @include('scripts.endCKEditor',['id'=>'description']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                                    </div>
                                </div>

                                <div class="control-group hidden-phone">
                                    <label class="control-label" for="ckeditor2">文章内容</label>
                                    <div class="controls">
                                        <textarea class="form-control" id="content_editor" name="content"> {{ Input::old('content', $info['content']) }}  </textarea>
                                        @include('scripts.endCKEditor',['id'=>'content_editor']){{-- 引入CKEditor编辑器相关JS依赖 --}}
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> SEO关键字: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="text" min="0" name="keywords" value="{{ Input::old('keywords', $info['keywords']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> SEO描述: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <textarea name="description" rows="2" cols="120" style="width: 40rem; height: 3rem;" >{{ Input::old('description', $info['description']) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{--发布时间--}}
                        <div class="control-group">
                            <label class="control-label" for="publish_time">发布时间</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" name="publish_time" id="publish_time" onclick="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'})" value="{{ Input::old('publish_time', $info['publish_time']) }}">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="date02"> 排序: </label>
                            <div class="controls">
                                <div class="input-prepend input-append">
                                    <input class="input-xlarge focused not_null" type="number" min="0" name="sort_num"  placeholder="序号越小越靠前" value="{{ Input::old('sort_num', $info['sort_num']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">是否置顶</label>
                            <div class="controls">
                                <label class="radio">
                                    <span class="checked"><input name="is_top" id="optionsRadios1" value="0" type="radio"></span>
                                    不置顶
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <span class=""><input name="is_top" id="optionsRadios2" value="1" @if ($info['is_top'] == 1) checked @endif type="radio"></span>
                                    置顶
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for=""> 状态 </label>
                            <div class="controls">
                                <select id="status" name="status" data-rel="chosen">
                                    <option value="100" @if ($info['status'] == 100) selected @endif >未发布</option>
                                    <option value="200" @if ($info['status'] == 200) selected @endif >已发布</option>
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
    <script src="{{ assetUrlByCdn('theme/metro/js/upload.js') }} "></script>

    <script>
        //jQuery
        (function(){
            $(document).ready(function(){

                $("#buttonUpload").on('click' ,   function(){
                    $.ajaxFileUpload(
                            {
                                headers: {'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN')},
                                url: '/upload/img', //用于文件上传的服务器端请求地址
                                secureuri: false, //是否需要安全协议，一般设置为false
                                fileElementId: 'img', //文件上传域的ID
                                dataType: 'content', //返回值类型 一般设置为json
                                success: function (data)  //服务器成功响应处理函数
                                {

                                    console.log(data);
                                    alert(data.file_infor);

                                    $("#img1").attr("src", data.imgurl);
                                    if (typeof (data.error) != 'undefined') {
                                        if (data.error != '') {
                                            alert(data.error);
                                        } else {
                                            alert(data.msg);
                                        }
                                    }
                                },
                                error: function (data, status, e)//服务器响应失败处理函数
                                {
                                    console.log(e);
                                    alert(e);
                                }
                            })
                    return false;
                });

            });
        })(jQuery);
    </script>
@stop

