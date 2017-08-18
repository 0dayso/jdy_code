@if( !empty(\App\Http\Logics\Ad\AdLogic::getUseAbleListByPositionId(13)) && empty($tag))
    <div class="index-activity-mask"></div>
    <div class="index-activity-pop">
        <a href="javascript:;" class="index-activity-pop-close" data-toggle="mask" data-target="index-activity-layer"></a>
        @if( isset(\App\Http\Logics\Ad\AdLogic::getUseAbleListByPositionId(13)[0]['param']['file'] ) && !empty(\App\Http\Logics\Ad\AdLogic::getUseAbleListByPositionId(13)[0]['param']['file']) )
            <a target="_blank" href="{{ \App\Http\Logics\Ad\AdLogic::getUseAbleListByPositionId(13)[0]['param']['url'] }}">
                <img src="{{ \App\Http\Logics\Ad\AdLogic::getUseAbleListByPositionId(13)[0]['param']['file'] }}">
            </a>
        @endif
    </div>
@endif

{{--<div class="index-activity-mask"></div>--}}
{{--<div class="index-activity-pop">--}}
    {{--<a href="javascript:;" class="index-activity-pop-close" data-toggle="mask" data-target="index-activity-layer"></a>--}}
        {{--<a target="_blank" href="#">--}}
            {{--<img src="{{assetUrlByCdn('/static/theme/spring/images/theme-pop.png')}}">--}}
        {{--</a>--}}
{{--</div>--}}