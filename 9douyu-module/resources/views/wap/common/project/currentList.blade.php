{{--  这个模板要求用CurrentEntity  --}}
<div onclick="_czc.push(['_trackEvent','WAP理财页','零钱计划']);" class="t2-main-tab">
    <h3 class="t2-main-title"><i></i>零钱计划 <span>1元起投 灵活存取</span></h3>
    <a href="/project/current/detail" class="t2-block">
        <table class="t2-main-tab-1">
            <tr>
                <td>
                    <p class="t2-project-1">借款利率</p>
                    <p class="t2-project-2"><span>{{ (float)$current['rateInfo']['rate'] }}</span>%</p>
                </td>
                <td>
                    <p class="t2-project-1">剩余可投</p>
                    <p class="t2-project-3"><span>{{ $current['formatFreeAmount'] }} </span>万</p>
                </td>
                <td>
                    <a href="/project/current/detail" class="t2-pro-btn">立即出借</a>
                </td>
            </tr>
        </table>
    </a>
</div>