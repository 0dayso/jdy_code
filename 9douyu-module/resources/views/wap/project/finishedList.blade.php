@extends('wap.common.wapBase')

@section('title', '全部项目')

@section('cssStyle')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/progress.css')}}">
@endsection

@section('content')

    <article>
        <div class="credit-list" id="list">
            <!--售罄列表-->
            @include('wap.common.project.finishedList')
        </div>
    </article>
    <section class="t2-mt50px">
        <p class="center mt5px">
            {{--<a id="next-page" data-page="{{$page}}"  data-target="#list"--}}
               {{--class="gray-title-bj mb15px t2-blue w-fff-bj plr15px"  data-type="{$type}">查看更多产品 >></a>--}}
        </p>
    </section>
@endsection
@section('jspage')
    <script type="text/javascript">

        $(document).ready(function(){
            //progressAnimate();
            function GetData(type,page,add){
                $.ajax({
                    url: "/project/ajax_index",
                    dataType: 'json',
                    type: 'post',
                    data: {'type': type, 'p': page},
                    success: function (result) {
                        if (add == 0) {
                            $('#list').html(result.content);
                            $("#next-page").attr("data-type",result.type)
                        } else {
                            $('#list').append(result.content);
                        }
                        $('#next-page').attr('data-page',1);
                    },
                    error: function (msg) {
                        alert('获取失败，请稍候再试');
                    }
                });
            }
        });

        function appendList($this,url,target,page){
            var p = parseInt($this.attr('data-page'))+1;
            if(p === page) {
                $.ajax({
                    url:url+' '+target,
                    success: function(res){
                        $('#list').append(res.content);
                        //$(res.content).find(target).find('section').appendTo(target);
                        $this.attr('data-page',p);
                    }
                });

            } else{
                return false;
            }
        }

        $(function(){
            $("#next-page").click(function(){
                var page=$(this).attr('data-page');
                var type=$(this).attr('data-type');
                var target=$(this).attr('data-target');
                var p = parseInt(page)+1;
                //var url='/project/lists/type/{$type}/p/'+p+'?{:time()}';
                var url='/project/ajax_index/type/'+type+'/p/'+p+'?{:time()}';
                appendList($(this),url,target,p)

            });
        });
    </script>
@endsection