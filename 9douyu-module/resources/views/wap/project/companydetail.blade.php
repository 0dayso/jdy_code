@extends('wap.common.wapLayout')

@section('title','项目详情')

@section('css')
    <link rel="stylesheet" href="{{assetUrlByCdn('/static/weixin/css/public.css')}}" type="text/css"/>
@endsection

@section('content')


    @if($project['product_line'] == 200)

        @include('wap.project.jax')

    @else

        @include('wap.project.jsx')

    @endif

@endsection
