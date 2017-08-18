@extends('wap.common.wapBase')

@section('title', 'App测试页')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')

@endsection

@section('content')

    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px" href="javascript:void(0)" onclick="goToProjectDetail(3232)" >项目详情</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="goToLogin()" >登录</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="goToInvest()" >出借列表</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="goToAccount()" >资产</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="gotToRegister()" >注册</a>
    {{--<br>
    <a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="goToShare()" >分享</a>--}}
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="gotToRechargeSuccess()" >充值</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="callTell()" >打客服</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="goToBonus()" >优惠券</a>
    <hr></label>
    <label><a style="font-size: 24px;color: #00a8c6;margin-left: 10px"  href="javascript:void(0)" onclick="unKnow()" >ios回原生</a>
    <hr>
    </label>

@endsection

@section('footer')

@endsection

@section('jsScript')

<script type="text/javascript">

    var client = getCookie('JDY_CLIENT_COOKIES');

    //项目详情
    function goToProjectDetail(projectId){
        try
        {

            if(client == 'android'){
                window.jiudouyu.fromNoviceActivity(projectId,1);
            }else{
                //window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                window.location.href="objc:toProjectDetail("+projectId+",1)";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //登录
    function goToLogin(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.login();
            }else{
                window.location.href="objc:gotoLogin";
                //window.location.href="objc:doFunc1";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //投资
    function goToInvest(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.gotoInvest();
            }else{
                window.location.href="objc:JumpToSecondPage";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //资产
    function goToAccount(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.gotoAccount();
            }else{
                window.location.href="objc:gotoAccount";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //注册
    function gotToRegister(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.gotoRegister()
            }else{
                window.location.href="objc:gotoRegister";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //分享
    function goToShare(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.goShare()
            }else{
                window.location.href="objc:doFunc3";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //充值
    function gotToRechargeSuccess(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.hideBackArrow();
            }else{
                window.location.href = "objc:rechargeSuccess";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //打客服
    function callTell(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.gotoCall('4006686568') ;
            }else{
                window.location.href = "tel:4006686568";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //优惠券
    function goToBonus(){
        try
        {
            if(client == 'android'){
                window.jiudouyu.gotoMyAllBonus();
            }else{
                window.location.href = "objc:goToDiscountCoupon";
            }
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    //什么鬼
    function unKnow(){
        try
        {
            window.location.href = "objc:gotoOrige";
        }
        catch(err)
        {
            var txt=err.message + "\n\n";
            console.info(txt);
        }

    }

    function getCookie(c_name)
    {
        if (document.cookie.length>0)
        {
            c_start=document.cookie.indexOf(c_name + "=")
            if (c_start!=-1)
            {
                c_start=c_start + c_name.length+1
                c_end=document.cookie.indexOf(";",c_start)
                if (c_end==-1) c_end=document.cookie.length
                return unescape(document.cookie.substring(c_start,c_end))
            }
        }
        return ""
    }

</script>
@endsection