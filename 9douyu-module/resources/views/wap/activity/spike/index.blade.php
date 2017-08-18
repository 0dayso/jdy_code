@extends('wap.common.activity')

@section('title', '花漾初夏  盛惠难却')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/interest4/css/index.css') }}">

@endsection

@section('content')

    <article>
        <div class="kill-banner">
            <p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}</p>
        </div>
        <section class="kill-1">
            <div>
                @if( !empty($spikeProject['high']))
                    @foreach($spikeProject['high'] as $highKey=>$highProject)
                        @if($userStatus ==true)
                        <a href="javascript:;"  class="userInvestProject" attr-data-id="{{$highProject['id']}}">
                        @else
                        <a href="javascript:;"  class="userLogin" attr-data-id="{{$highProject['id']}}">
                        @endif
                            <div class="kill-content kill-yellow">
                                <h4 class="kill-2">{{$highProject['product_line_note']}}{{$highProject['format_invest_time']}}{{$highProject['invest_time_unit']}}</h4>
                                <table class="kill-data">
                                    <tr>
                                        <td>
                                            <p>{{ (float)$highProject['base_rate'] }}<span>%</span>+{{ (float)$highProject['after_rate']}}<span>%</span></p>
                                            <p>借款利率</p>
                                        </td>
                                        <td>
                                            <div class="kill-line"></div>
                                            <p class="kh">{{number_format($highProject['left_amount'])}}元</p>
                                            <p>剩余可投</p>
                                        </td>
                                        <td>
                                        @if($highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($highProject['publish_at'],'default') >= time())
                                            <a href="javascript:" class="kill-btn disable">敬请期待</a>
                                        @elseif($highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                            <a href="javascript:;" class="kill-btn">立即秒杀</a>
                                        @else
                                            <a href="javascript:" class="kill-btn disable">{{ $highProject['status_note'] }}</a>
                                        @endif
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </a>
                    @endforeach
                @endif
                @if( !empty($spikeProject['low']))
                    @foreach($spikeProject['low'] as $key=>$lowProject)
                            @if($userStatus ==true)
                            <a href="javascript:;"  class="userInvestProject" attr-data-id="{{$lowProject['id']}}">
                            @else
                            <a href="javascript:;"  class="userLogin" attr-data-id="{{$lowProject['id']}}">
                            @endif
                            <div class="kill-content">
                                <h4 class="kill-2">{{$lowProject['product_line_note']}}{{$lowProject['format_invest_time']}}{{$lowProject['invest_time_unit']}}</h4>
                                <table class="kill-data">
                                    <tr>
                                        <td>
                                            <p>{{ (float)$lowProject['base_rate'] }}<span>%</span>+{{ (float)$lowProject['after_rate']}}<span>%</span></p>
                                            <p>借款利率</p>
                                        </td>
                                        <td>
                                            <div class="kill-line"></div>
                                            <p class="kh">{{number_format($lowProject['left_amount'])}}元</p>
                                            <p>剩余可投</p>
                                        </td>
                                        <td>

                                            @if($lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($lowProject['publish_at'],'default') >= time())
                                                <a href="javascript:" class="kill-btn disable">敬请期待</a>
                                            @elseif($lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                                <a href="javascript:;" class="kill-btn ">立即秒杀</a>
                                            @else
                                                <a href="javascript:" class="kill-btn disable">{{ $lowProject['status_note'] }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

            <div class="page-rule">
                <h3>活动规则</h3>
                <div class="page-box">
                    <p>1.活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
                    <p>2.加息抢购项目为项目直接加息，出借时不可再使用加息券和现金券；</p>
                    <p>3.活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
                </div>
            </div>
            <input type="hidden" name="_token"  value="{{csrf_token()}}">
            <!-- <div class="page-ad">
            <a href="/activity/invitation"><img src="{{ assetUrlByCdn('/static/weixin/activity/interest2/images/page-ad.jpg') }}" class="img"></a></div> -->
        </section>
    </article>
@endsection

@section('footer')

@endsection

@section('jsScript')
    <script>
        document.body.addEventListener('touchstart', function () { });


        $(document).ready(function(){

            var client = getCookie('JDY_CLIENT_COOKIES');
            if( client == '' || !client ){
                var client  =   '{{$client}}';
            }
            @if($userStatus ==false)
            $(".userLogin").click(function () {
                if( client =='ios'){
                    window.location.href = "objc:gotoLogin";;
                    return false;
                }
                if (client =='android'){
                    window.jiudouyu.login()
                    return false;
                }
                window.location.href='/login';
            })
            @else

            $('.userInvestProject').click(function () {
                var  projectId  =   $(this).attr("attr-data-id");

                if( !projectId ||projectId==0){
                    return false;
                }

               var act_token = "{{time()}}" + '_100_' + projectId
                if( client =='ios'){
                    if( version && version <'4.1.0'){
                        window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                        return false;
                    }
                    if(!version || version >='4.1.0') {
                        window.location.href="objc:toProjectDetail("+projectId+",1,"+act_token+")";
                        return false;
                    }
                }
                if (client =='android'){
                    if( version <'4.1.0' ) {
                        window.jiudouyu.fromNoviceActivity(projectId,1);
                        return false;
                    }
                    if( version >='4.1.0' ) {
                        window.jiudouyu.fromNoviceActivity(projectId,1,act_token);
                        return false;
                    }
                }
                var _token = $("input[name='_token']").val();
                $.ajax({
                    url      :"/activity/setActToken",
                    data     :{act_token:act_token,_token:_token},
                    dataType :'json',
                    type     :'post',
                    success : function() {
                        window.location.href='/project/detail/' + projectId;
                        }, error : function() {
                            window.location.href='/project/detail/' + projectId;
                    }
                });
                window.location.href='/project/detail/' + projectId;
                return false
            })
        @endif
        });
    </script>
@endsection
