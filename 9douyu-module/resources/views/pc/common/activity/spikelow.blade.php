<div class="seckill-2">
    <a href="javascript:;" onclick="_czc.push(['_trackEvent','PC秒杀活动','九安心']);window.location.href='/project/index'">
        <div class="seckill-title seckill-title1">九省心</div>
        <div class="seckill-info">活动期间，每日 {{ $spikeTime }}准时开启加息秒杀专场！</div>
        <div class="seckill-4-data">
            <table>
                <tr>
                    <td width="200">
                        <p>
                            <big>8</big><span class="per">%</span><span class="add">+</span><big>2</big><span class="per">%</span>
                        </p>
                        <p class="nhs"><small>借款利率</small></p>
                        <i></i>
                    </td>
                    <td width="160">
                        <p> 0天</p>
                        <p><small>项目期限</small></p>
                        <i></i>
                    </td>
                    <td width="170">
                        <p>0元</p>
                        <p><small>剩余可投</small></p>
                        <i></i>
                    </td>
                    <td>
                        @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ENDED)
                            <span  class="seckill-btn btn1 disable">已结束</span>
                        @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)
                            <span  class="seckill-btn btn1 disable">敬请期待</span>
                        @else
                            <span  class="seckill-btn btn1 disable">敬请期待</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </a>
    <!-- 倒计时遮罩 -->
    @if($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_NOSTART)
        <div class="seckill-4-mask">
            <i></i>
            <p>距离开始秒杀还有<br><big class="timelast"></big></p>
        </div>
    @elseif($spikeStatus == \App\Http\Logics\Activity\SpikeLogic::ACTIVITY_ONGOING)
        <div class="seckill-4-mask">
            <i></i>
            <p>距离开始秒杀还有<br><big class="timelast"></big></p>
        </div>
    @else
        <div class="seckill-4-mask">
            <i></i>
            <p>活动已经结束</p>
        </div>
    @endif
</div>