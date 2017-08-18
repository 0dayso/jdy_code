<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{ assetUrlByCdn('images/admin/favicon.png') }}" type="image/png">

    <title>后台管理-9斗鱼</title>

    <link href="{{ assetUrlByCdn('css/admin/style.default.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ assetUrlByCdn('js/admin/html5shiv.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/respond.min.js') }}"></script>
    <![endif]-->
</head>

<body class="signin">

<!-- Preloader -->
<!-- <div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div> -->

<section>

    <div class="signinpanel">

        @yield('content')

        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2016. All Rights Reserved.
            </div>
            <div class="pull-right">
                Modify By: <a href="http://www.9douyu.com" title="访问首页">www.9douyu.com</a>
            </div>
        </div>

    </div><!-- signin -->

</section>


<script src="{{ assetUrlByCdn('js/admin/jquery-1.10.2.min.js') }}"></script>
<script src="{{ assetUrlByCdn('js/admin/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ assetUrlByCdn('js/admin/bootstrap.min.js') }}"></script>
<script src="{{ assetUrlByCdn('js/admin/modernizr.min.js') }}"></script>
<script src="{{ assetUrlByCdn('js/admin/retina.min.js') }}"></script>
</body>
</html>
