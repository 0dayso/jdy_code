<div class="crs-img"></div>
<div class="new-data">
    <div class="new-web-data">
        <table>
            <tr>
                <td onclick="window.open('/zt/statistics');">
                    <dl>
                        <dt class="web-data-icon web-data-icon1"></dt>
                        <dd>
                            <h4>{{ number_format(floor($stat['total_invest_amount'])) }}</h4>
                            <p>总交易额（元）</p>
                        </dd>
                    </dl>
                </td>
                <td onclick="window.open('/zt/statistics');">
                    <dl>
                        <dt class="web-data-icon web-data-icon2"></dt>
                        <dd>
                            <h4>{{ number_format($stat['user_total']) }}</h4>
                            <p>注册用户数（人）</p>
                        </dd>
                    </dl>
                </td>
                <td class="br0px" onclick="window.open('/content/article/reservefund?id=815');">
                    <dl>
                        <dt class="web-data-icon web-data-icon3"></dt>
                        <dd>
                            <h4>{{ number_format($stat['risk_money']) }}<i class="t1-icon9 iconfont" title="由东亚银行监管，月底更新">&#xe609;</i></h4>
                            <p>风险准备金（元）</p>

                        </dd>
                    </dl>
                </td>
            </tr>
        </table>
    </div>
</div>