@extends('wap.common.wapBase')

@section('title', '全部项目')

@section('content')
    <article>
        <section class="Js_tab_box1">
            <ul class="Js_tab t2-tab">
                <li>
                    精品推荐
                </li>
                <li>
                    优选项目
                </li>
            </ul>
            <div class="js_tab_content t2-list">
                <div class="Js_tab_main w3-mb72px">
                    <!--零钱计划-->
                    @include('wap.common.project.currentList')
                    <!--闪付息-->
                    {{--@include('wap.common.project.sdfList')--}}
                </div>
                <div class="Js_tab_main" style="display: none;">
                    <!--优选项目产品-->
                    @include('wap.common.project.list')
                    {{--<div class="t2-main-tab1">
                        <a href="/project/creditAssign" class="t2-block">
                            <h3 class="t2-main-title3"><span></span>债权转让专区 <i>{{ $creditAssignCount }}个<em class="t2-arrow"></em></i></h3>
                        </a>
                    </div>--}}
                    <section class="t2-mt50px">
                        <p class="center mt5px">
                            <a href="/project/more" class="gray-title-bj mb15px t2-blue w-fff-bj plr15px">查看已售罄产品 >></a>
                        </p>
                    </section>

                </div>
            </div>
        </section>
    </article>
@endsection
@section('footer')
    @include('wap.common.footer')
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
           $('#wap2-project-tab a').click(function () {
                    if (!$(this).hasClass('current')) {
                        //tabReset();
                        //切换样式
                        $(this).siblings().removeClass('current');
                        $(this).addClass('current');
                        //拉取数据
                        var type = $(this).attr('type');
                        GetData(type, 1, 0);

                    }
                });
        });

        function appendList($this,url,target,page)
        {
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
                var url='/project/ajax_index/type/'+type+'/p/'+p+'?{{ time() }}';
                appendList($(this),url,target,p)

            });
        });

    </script>

@endsection
