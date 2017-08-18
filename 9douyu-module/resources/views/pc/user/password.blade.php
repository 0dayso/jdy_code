<!DOCTYPE html>
<html>
<head>
    <title>Be right back.</title>
    <script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js')}}"></script>
</head>
<body>
<div class="container">
    <div class="content">
        <form class="form-inline" role="form" action="{{ URL('/user/doPassword') }}" method="post">
            <div class="form-group">
                <label>原密码</label>
                <input name="oldPassword" type="text" value="" class="form-control" >
            </div>
            <div class="form-group">
                <label>新密码</label>
                <input name="newPassword" type="text" value="" class="form-control" >
            </div>
            <div class="form-group">
                <label>确认新密码</label>
                <input name="confirmPassword" type="text" value="" class="form-control" >
            </div>
            <div class="form-group">
                @if(Session::has('errors'))
                    {{  Session::get('errors') }}
                @endif
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-lg btn-success col-lg-12">确定</button>
        </form>
    </div>
</div>

</body>
</html>
