<div class="m-myuser-nav">
    
    {{--<ul>
        <li class="m-title">我的账户</li>

        <li class="m-first   @if($class=='') checked @endif">
            <a href="{:U('/user')}"  @if($class=='') class="checkeda" @endif><i class="t1-icon22 iconfont">&#xe615;</i>账户总览</a>
        </li>

        <li class="m-second  @if($class=='invest') checked @endif">
            <a href="{:U('/user/invest/records/type/refunding')}"  @if($class=='invest') class="checkeda" @endif><i class="t1-icon17 iconfont">&#xe610;</i>定期资产</a>
        </li>

        <li class="m-third   @if($class=='current') checked @endif">
            <a href="{:U('/user/current')}" @if($class=='current') class="checkeda" @endif><i class="t1-icon19 iconfont">&#xe612;</i>零钱计划</a>
        </li>

        <li class="m-fourth  @if($class=='credit_assign') checked @endif">
            <a href="{:U('/user/credit_assign/assignable')}"  @if($class=='credit_assign') class="checkeda" @endif><i class="t1-icon21 iconfont">&#xe614;</i>债权转让</a>
        </li>

        <li class="m-fifth   @if($class=='fund_history' || $class=='order' || $class=='BankCard') checked @endif">
            <a href="{:U('/user/fund_history')}" @if($class=='fund_history' || $class=='order' || $class=='BankCard') class="checkeda" @endif><i class="t1-icon18 iconfont">&#xe611;</i>资金记录</a>
        </li>

        <li class="m-sixth   @if($class=='bonus' || $class=='t') checked @endif">
            <a href="{:U('/user/bonus')}" @if($class=='bonus' || $class=='t') class="checkeda"  @endif><em class="t1-icon24 iconfont">&#xe616;</em>优惠券
            @if(isset($availableBonusCount)) <i>{{ $availableBonusCount }}</i> @endif
            </a>
        </li>

        <li class="m-seventh @if($class=='Information') checked @endif">
            <a href="{:U('/user/Information/index')}" @if($class=='Information') checkeda @endif><i class="t1-icon26 iconfont">&#xe618;</i>账户设置</a>
        </li>
    </ul>--}}
    <ul>
        <li class="m-title">我的账户</li>
        <li class="m-first @if(Request::path() == 'user') checked @endif"><a href="{{ URL('/user') }}"  class="checkeda"><i class="t1-icon22 iconfont">&#xe615;</i>账户总览</a></li>
        <li class="m-fifth @if(Request::path() == 'user/fundhistory') checked @endif"><a href="{{ URL('/user/fundhistory') }}"   ><i class="t1-icon18 iconfont">&#xe611;</i>资金记录</a></li>
        <li class="m-fifth @if(Request::path() == 'user/investList') checked @endif"><a href="{{ URL('/user/investList') }}"   ><i class="t1-icon24 iconfont">&#xe616;</i>合同下载</a></li>
    </ul>
    <div class="menu-img">
        <img src="{{assetUrlByCdn('/static/images/menu-erweima.png')}}" width="126" height="126">
        <p>九斗鱼已升级为全新技术架构，PC版只保留最基本投资功能，更多完整功能请扫描二维码下载最新版手机App体验。</p><p><br>客服电话：<br>400-6686-568。</p>
    </div>
</div>
