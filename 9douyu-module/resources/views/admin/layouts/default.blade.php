<!DOCTYPE html>
<html lang="zh">
<head>

    @section('css')
        <link href="{{ assetUrlByCdn('css/admin/style.default.css') }}" rel="stylesheet">
        <link href="{{ assetUrlByCdn('css/admin/jquery.datatables.css') }}" rel="stylesheet">
        <link href="{{ assetUrlByCdn('css/admin/sweetalert.css') }}" rel="stylesheet">
    @show
    @include('admin/common/html-head')
</head>
<body>
<!-- start: Header -->
@include('admin/common/header')
<!-- start: Header -->

<div class="container-fluid-full">
    <div class="row-fluid">
                <!--测导航 -->
        @include('admin/common/sidebar')
                <!--测导航 -->

        <div id="content" class="span10">
            @yield('content')
        </div><!--/.fluid-container-->

        <!-- end: Content -->
    </div><!--/#content.span10-->

</div><!--/fluid-row-->

<div class="clearfix"></div>
@include('admin/common/footer')
@include('admin/common/js')

@section('javascript')
    <script src="{{ assetUrlByCdn('js/admin/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/jquery-ui-1.10.3.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/bootstrap.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/modernizr.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/toggles.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/retina.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/jquery.cookies.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/flot/flot.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/flot/flot.resize.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/morris.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/raphael-2.1.0.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/chosen.jquery.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/sweetalert.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('js/admin/custom.js') }}"></script>
    {{--<script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js') }}"></script>--}}

    {!! Toastr::render() !!}
    @yield('jsScript')
@show
</body>
</html>
