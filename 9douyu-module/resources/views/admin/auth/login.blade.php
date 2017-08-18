@extends('layouts.admin-login')

@section('content')
    <div class="row">

        <div class="col-md-7">

            <div class="signin-info">
                <div class="logopanel">
                    <h1><span>[</span> Admin <span>]</span></h1>
                </div><!-- logopanel -->

                <div class="mb20"></div>

                <h5><strong>Welcome to 9douyu admin!</strong></h5>
                <br />
                <br />
                <img src="{{ assetUrlByCdn('/static/images/new/logo-new-replace.png') }}" width="445px"/>

                <div class="mb20"></div>
            </div><!-- signin0-info -->

        </div><!-- col-sm-7 -->

        <div class="col-md-5">
            <form method="post" action="/admin/login">
                @if(Session::has('message'))
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ Session::get('message') }}
                    </div>
                @elseif($errors->first())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{ $errors->first() }}!</strong>
                    </div>
                    @else
                @endif
                    <h4 class="nomargin">管理员登录</h4>
                    <input type="text" name="email" class="form-control uname" placeholder="邮箱"/>
                    <input type="password" name="password" class="form-control pword" placeholder="密码"/>
                    <input type="text" name="verify" class="form-control pword" placeholder="工号"/>
                    <input type="hidden" name="sign" value="{{ env('LOGIN_SIGN') }}"/>
                    {{--<input type="text" name="captcha" class="form-control" placeholder="验证码"/>
                        <img id="captcha" class="form-control" src="/captcha/111" onclick="this.src=this.src+Math.random()">--}}
                <div class="checkbox">
                    <input type="checkbox" name="remember"> 记住我
                    <span style="float: right"><a href="JavaScript:;" onclick="admin.forgetPassword();">
                        <small>忘记密码?</small>
                    </a></span>
                </div>

                <input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-success btn-block">确认登录</button>
            </form>
        </div><!-- col-sm-5 -->

    </div><!-- row -->
    <script type="text/javascript">
        var admin   ={
            signName:'sign',
            sendUrl:'/admin/forgetPassword',
            forgetPassword:function () {
                var name    =   this.signName;
                var sign    =   admin.getSign(name);
                this.sendEmail(sign);
            },
            getSign:function (name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                var r = window.location.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]); return null;
            },
            sendEmail:function (sign) {

                if( sign == '' || !sign || sign == null) {

                    return false;
                }
                window.location.href=this.sendUrl + '?sign=' + sign;
            }
        }

    </script>
@endsection