<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="{{ assetUrlByCdn('images/admin/favicon.png') }}" type="image/png">

    <title>403</title>

    <link href="{{ assetUrlByCdn('css/admin/style.default.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/admin/html5shiv.js"></script>
    <script src="js/admin/respond.min.js"></script>
    <![endif]-->
    <script src="{{ assetUrlByCdn('js/admin/jquery-1.10.2.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/jquery-ui-1.10.3.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/bootstrap.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/modernizr.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/jquery.cookies.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/bootbox.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/custom.js') }}"></script>
</head>


<body class="notfound" style="overflow: visible;">

<!-- Preloader -->
<!-- <div id="preloader" style="display: none;">
    <div id="status" style="display: none;"><i class="fa fa-spinner fa-spin"></i></div>
</div> -->

<section>

    <div class="lockedpanel">
        <div class="locked">
            <i class="fa fa-lock"></i>
        </div>
        <div class="logged">
            <h4>403</h4>
            <small class="text-muted">对不起，你没有权限操作这个页面</small>
        </div>
        <form method="post" action="#">
            <a href="{{ $previousUrl }}" class="btn btn-success btn-block">点击返回</a>
        </form>
    </div><!-- lockedpanel -->

</section>


</body>
</html>
