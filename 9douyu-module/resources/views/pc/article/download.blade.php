
@extends('pc.common.layout')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/css/download.css') }}">


    <div class="download-banner">
        <div class="download-wrap">
            <div class="download-btn-box">
                <a class="download-btn1" href="{{$iosUrl}}">apple itunes store</a>
                <a class="download-btn2" href="{{$androidUrl}}">android store</a>
            </div>
        </div>
    </div>
    <div class="download-wrap">
        <img src="{{assetUrlByCdn('/static/images/topic/download-img3-0607.png')}}" class="download-img1">
        <img src="{{assetUrlByCdn('/static/images/topic/download-img2-0607.png')}}" class="download-img2">
    </div>

@endsection