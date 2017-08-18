<a href="javascript:;" onclick="_czc.push(['_trackEvent','Wap秒杀活动','九安心']);" >
    <div class="kill-content kill-yellow">
        <h4 class="kill-2">九安心</h4>
        <table class="kill-data">
            <tr>
                <td>
                    <p>8<span>%</span>+2<span>%</span></p>
                    <p>借款利率</p>
                </td>
                <td>
                    <div class="kill-line"></div>
                    <p>0元</p>
                    <p>剩余可投</p>
                </td>
                <td>
                    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED)
                        <a href="javascript:;" class="kill-btn disable">已结束</a>
                    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)
                        <a href="javascript:" class="kill-btn disable">敬请期待</a>
                    @else
                        <a href="javascript:" class="kill-btn disable">敬请期待</a>
                    @endif
                </td>
            </tr>
        </table>
    <!--蒙版层-->
    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
        <div class="kill-data-mask">
            <p>距离开始秒杀还有</p>
            <p class="kill-time"></p>
        </div>
    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)
        <div class="kill-data-mask">
            <p>距离下一场秒杀还有</p>
            <p class="kill-time"></p>
        </div>
    @else
        <div class="kill-data-mask">
            <p>活动已经结束</p>
            <p class="kill-time"></p>
        </div>
    @endif
    </div>
</a>