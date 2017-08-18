<div class="footer">
    <div class="wrap hidden mb-51px">
        <div class="footer-left">
            <div class="hidden">
                <p class="new-footer-nav fl">联系我们</p>
                <p class="new-footer-nav fr foote-t">关注我们</p>

            </div>
            <div class="footer-info-wrap hidden">
                <div class="footer-info">
                    <p class="footer-tel">400-6686-568</p>
                    <div class="footer-time"><i class="t1-icon15 iconfont">&#xe60d;</i>工作时间：9:00 — 18:00</div>
                    <p class="footer-address">商务合作：business@9douyu.com<br/>公司地址：北京市朝阳区郎家园6号郎园vintage 2号楼A座2层</p>
                </div>
                <div class="footer-qrcode">
                    <dl>
                        <dt class="icon-server"></dt>
                        <dd>关注微信服务号</dd>
                    </dl>
                    <dl>
                        <dt class="icon-app"></dt>
                        <dd>手机客户端下载</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="footer-right">

            <ul class="footer-tab js-footer-tab">
                <div class="footer-line">
                    <div class="footer-blue"></div>
                </div>

                <li  class="selected"><a href="/about/notice" target="_blank">平台公告</a></li>
                <li><a  href="/about/notice?q=records" target="_blank">还款公告</a>
                    <div></div> </li>
            </ul>

            <div class="footer-tabbox js-footer-tabbox">

                @if( !empty($article['notice']) )
                    <div >
                        <ul>
                            @foreach($article['notice'] as $notice)
                                <li><a  href="/article/{{ $notice['id'] }}" target="_blank" rel="{{ $notice['title'] }}">{{ str_limit($notice['title'], $limit = 26, $end = '...') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div>
                        <ul>
                            <li>暂无平台公告信息</li>
                        </ul>
                    </div>
                @endif

                @if( !empty($article['refund']) )
                    <div class="none">
                        <ul>
                            @foreach($article['refund'] as $refund)
                                <li><a  href="/article/{{ $refund['id'] }}" target="_blank" rel="{{ $refund['title'] }}">{{ str_limit($refund['title'], $limit = 26, $end = '...')  }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="none">
                        <ul>
                            <li>暂无还款公告信息</li>
                        </ul>
                    </div>
                @endif
            </div>

        </div>

        <div class="clear"></div>
        <div class="footer-logo">
            <span>耀盛中国旗下平台&nbsp; |</span>
            <a href="http://www.sunholding.com.cn/" target="_blank">耀盛中国</a>
            <a href="http://www.riskcalc.cn/" target="_blank">瑞思科雷</a>
            <a href="http://www.sunfactoring.com.cn/" target="_blank" class="footer-logo2"> 耀盛保理</a>
            <a href="http://www.sunlends.com/" target="_blank"> 耀盛信贷</a>
            <!-- <a href="http://www.sunfund.com/" target="_blank">耀盛财富</a> -->
            <a href="http://www.sunleasing.com.cn/" target="_blank">耀江租赁</a>
            <a href="http://www.timecash.cn/" target="_blank"> 快金</a>
            <a href="http://www.pufubao.net/" target="_blank">普付宝</a>
            <a href="http://www.sunfundsecurities.com.hk" target="_blank">耀盛证券</a>
            <span class="new-footer-line">｜</span>
            <a href="/about/index"  target="_blank">关于我们 </a>
            <a href="/about/sunholding" target="_blank">集团介绍</a>
            <a href="/about/branch" target="_blank">分支机构 </a>
            <a href="/help" target="_blank">帮助中心</a>
        </div>

    </div>
</div>

<div class="clearfix"></div>

