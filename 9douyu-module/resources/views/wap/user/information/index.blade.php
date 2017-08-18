@extends('wap.common.wapBase')
@section('title', '安全中心')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')

@endsection
@section('content')
    <script type="text/javascript">
        //普付宝调用函数
        function isPfb(){
            if(typeof window.jiudouyu.toLogout === 'function'){
                window.jiudouyu.toLogout();
                return false;
            }
            return true;
        }
    </script>


    <List:user item='loginUser' all='1'/>
    <article>
        <div class="w-img2 mb15px">
        <div class="wap2-head-img">
            <img src="{{assetUrlByCdn('/static/images/touxiang.png')}}">
        </div>
            {{--<a href="javascript:;" class="wap2-message">
                <if condition="$noticeNum eq true">
                    <span>动态新消息提示红点</span>
                </if>
            </a>--}}
        </div>
        <section class="wap2-input-group wap2-input-border">

            @if(!$verifyStatus)
                    <!-- 未经过实名认证开始 -->
            <div class="wap2-input-box2 wap2-after-border">
                <a href="/user/verify" class="wap2-link-block">
                    <p class="fr"><span class="mr2 blue">立即认证</span><span class="wap2-arrow-1 wap2-arrow-right"></span></p>
                    <p><span class="wap2-icon wap2-icon-12"></span>实名认证</p>
                </a>
            </div>
            <!-- 未经过实名认证结束 -->
            @else
                    <!-- 已经实名认证开始 -->
            <div class="wap2-input-box2 wap2-after-border">
                <p class="fr"><span class="color8c">{{$user['real_name']}}（{{substr($user['identity_card'],0,4)}}********{{substr($user['identity_card'],-4)}}）</span></p>
                <p><span class="wap2-icon wap2-icon-12"></span>实名认证</p>
            </div>
            <!-- 已经实名认证结束 -->
            @endif

            <div class="wap2-input-box2 wap2-after-border">
                <a href="javascript:;" class="wap2-link-block">
                    <p class="fr"><span class="mr2 blue">{{substr($user['phone'],0,3)}}********{{substr($user['phone'],-4)}}</span></p>
                    <p><span class="wap2-icon wap2-icon-13"></span>手机号码</p>
                </a>
            </div>
            <div class="wap2-input-box2 wap2-after-border">
                <a href="/user/modifyLoginPassword" class="wap2-link-block">
                    <span class="wap2-arrow-1 wap2-arrow-right"></span>
                    <p><span class="wap2-icon wap2-icon-14"></span>修改登录密码</p>
                </a>
            </div>
            <div class="wap2-input-box2">
                @if($isSetPassword)
                    <a href="/user/modifyTradingPassword" class="wap2-link-block">
                        <span class="wap2-arrow-1 wap2-arrow-right"></span>
                        <p><span class="wap2-icon wap2-icon-15"></span>修改交易密码</p>
                    </a>
                    @else
                    <a href="/user/setTradingPassword" class="wap2-link-block">
                        <span class="wap2-arrow-1 wap2-arrow-right"></span>
                        <p><span class="wap2-icon wap2-icon-15"></span>设置交易密码</p>
                    </a>
                @endif
            </div>
           {{-- <if condition="$is_weixin eq true">
                <div class="wap2-input-box2 wap2-after-border">
                    <if condition="$is_dingyue eq true">
                        <a class="wap2-link-block" href="{:U('dingyue/td')}">
                            <span class="wap2-arrow-1 wap2-arrow-right"></span>
                            <p><span class="wap2-icon wap2-icon-16"></span>取消订阅项目预告消息</p>
                        </a>
                        <else/>
                        <a class="wap2-link-block" href="{:U('dingyue/dy')}">
                            <span class="wap2-arrow-1 wap2-arrow-right"></span>
                            <p><span class="wap2-icon wap2-icon-16"></span>订阅项目预告消息</p>
                        </a>
                    </if>
                </div>
                <div class="wap2-input-box2">
                    <if condition="$is_bind eq true">
                        <a class="wap2-link-block" href="{:U('dingyue/bind')}">
                            <span class="wap2-arrow-1 wap2-arrow-right"></span>
                            <p><span class="wap2-icon wap2-icon-17"></span>取消绑定微信账号</p>
                        </a>
                        <else/>
                        <a class="wap2-link-block" href="{:U('dingyue/bind/')}">
                            <span class="wap2-arrow-1 wap2-arrow-right"></span>
                            <p><span class="wap2-icon wap2-icon-17"></span>绑定微信账号</p>
                        </a>
                    </if>
                </div>
            </if>--}}
        </section>
        <section class="wap2-btn-wrap mb2">
            <a onclick="return isPfb()" href="/logout" class="wap2-link-block">
                <input class="wap2-btn wap2-btn-blue2" type="button" value="退出当前账号"/>
            </a>
        </section>
    </article>
@endsection
@section('footer')
    @include('wap.common.footer')
@endsection