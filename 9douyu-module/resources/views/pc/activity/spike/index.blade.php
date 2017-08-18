@extends('pc.common.activity')

@section('title', '花漾初夏  盛惠难却')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/interest4/css/index.css') }}">
    <!-- banner -->
    <div class="seckill-banner">
        <p>{{ date("Y年m月d日",$activityTime['start']) }}－{{ date("Y年m月d日",$activityTime['end']) }}</p>
    </div>
    <div class="wrap">
        @if( !empty($spikeProject['high']))
            @foreach($spikeProject['high'] as $highKey=>$highProject)
                <div class="seckill-4">
                    <a href="javascript:;" onclick="window.location.href='/project/detail/{{$highProject['id']}}'">
                        <div class="seckill-title seckill-title1">{{$highProject['product_line_note']}}{{$highProject['format_invest_time']}}{{$highProject['invest_time_unit']}}</div>
                        <div class="seckill-info">&nbsp;</div>
                        <div class="seckill-4-data">
                            <table>
                                <tr>
                                    <td width="250">
                                        <p>
                                            <big>{{ (float)$highProject['base_rate'] }}</big><span class="per">%</span><span class="add">+</span><big>{{ (float)$highProject['after_rate'] }}</big><span class="per">%</span>
                                        </p>
                                        <p class="nhs"><small>借款利率</small></p>
                                        <i></i>
                                    </td>
                                    <td width="160">
                                        <p>{{$highProject['format_invest_time']}} {{$highProject['invest_time_unit']}}</p>
                                        <p><small>项目期限</small></p>
                                        <i></i>
                                    </td>
                                    <td width="170">
                                        <p>{{number_format($highProject['left_amount'])}}元</p>
                                        <p><small>剩余可投</small></p>
                                        <i></i>
                                    </td>
                                    <td>@if($highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($highProject['publish_at'],'default') >= time())
                                            <span href="javascript:" class="seckill-btn btn1 disable">敬请期待</span>
                                        @elseif($highProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                            <span href="javascript:;" class="seckill-btn btn1">立即秒杀<ins></ins></span>
                                        @else
                                            <span href="javascript:" class="seckill-btn btn1 disable">{{ $highProject['status_note'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>

                </div>
            @endforeach
        @endif
        @if( !empty($spikeProject['low']))
            @foreach($spikeProject['low'] as $key=>$lowProject)
                <div class="seckill-2">
                    <a href="javascript:;" onclick="window.location.href='/project/detail/{{$lowProject['id']}}'">
                        <div class="seckill-title seckill-title1">{{$lowProject['product_line_note']}}{{$lowProject['format_invest_time']}}{{$lowProject['invest_time_unit']}}</div>
                        <div class="seckill-info">&nbsp;</div>
                        <div class="seckill-4-data">
                            <table>
                                <tr>
                                    <td width="250">
                                        <p>
                                            <big>{{ (float)$lowProject['base_rate'] }}</big><span class="per">%</span><span class="add">+</span><big>{{ (float)$lowProject['after_rate'] }}</big><span class="per">%</span>
                                        </p>
                                        <p class="nhs"><small>借款利率</small></p>
                                        <i></i>
                                    </td>
                                    <td width="160">
                                        <p>{{$lowProject['format_invest_time']}} {{$lowProject['invest_time_unit']}}</p>
                                        <p><small>项目期限</small></p>
                                        <i></i>
                                    </td>
                                    <td width="170">
                                        <p>{{number_format($lowProject['left_amount'])}}元</p>
                                        <p><small>剩余可投</small></p>
                                        <i></i>
                                    </td>
                                    <td>@if($lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($lowProject['publish_at'],'default') >= time())
                                            <span href="javascript:" class="seckill-btn btn1 disable">敬请期待</span>
                                        @elseif($lowProject['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                            <span href="javascript:;" class="seckill-btn btn1">立即秒杀<ins></ins></span>
                                        @else
                                            <span href="javascript:" class="seckill-btn btn1 disable">{{ $lowProject['status_note'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                </div>

            @endforeach
        @endif
        <div class="clear"></div>
        <div class="seckill-rule">
            <div class="seckill-title seckill-title1">活动规则</div>
            <p><span>1、</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}；</p>
            <p><span>2、</span>加息抢购项目为项目直接加息，出借时不可再使用加息券和现金券；</p>
            <p><span>3、</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
        </div>
        


    </div>
@endsection


@section('jspage')

@endsection



