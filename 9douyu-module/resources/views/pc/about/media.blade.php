@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    @include('pc.about.common.menu')
    <div class="t-wrap t-media">
        <h4>媒体报道</h4>
        <h5>MEDIA REPORTS</h5>
        <div class="t-ys-line"></div>
        <div class="clear"></div>
        <div class="t-media1">
            <a href="/article/816.html" class="t-media-2"><img src="{{assetUrlByCdn('/static/images/new/t-media-img.png')}}" width="161" height="47" alt="媒体报道"></a>

            <dl class="t-media-1">
                <dt><a href="http://news.cntv.cn/2014/07/08/VIDE1404798846710525.shtml" target="_blank"><img src="{{assetUrlByCdn('/static/images/new/t-meida-img1.jpg')}}" width="470" height="298" alt="九斗鱼联合创始人"></a></dt>
                <dd>

                    <h5><a href="/article/816.html">耀盛中国总裁原旭霖接受CCTV专访：<br/>打造中国特色的中小企业生态圈</a></h5>
                    <p>
                        作为现代综合金融集团的参会代表——耀盛中国总裁原旭霖先生在“投融资交易会”上表示，中小企业创造80%的城镇就业岗位，创造65%的GDP，中小企业是真正的实体经济。耀盛中国的永久使命是服务于8千万家的中小企业，打造最具中国特色的中小企业生态圈。

                    </p>
                </dd>
            </dl>

            <div class="clear"></div>
            @if( !empty($list['list']) )
            @foreach( $list['list'] as $k => $article )
                <dl class="t-media-2">
                    <?php

                        $picDb = new \App\Http\Dbs\Picture\PictureDb();

                        $pathUrl = $picDb->getPicturePath($article['picture_id']);

                    ?>
                    <dt>
                        @if ( !empty($pathUrl))
                            <img src="{{assetUrlByCdn($pathUrl)}}" alt="{{ $article['title'] }}" width="210" height="92">
                        @else
                            <img src="{{assetUrlByCdn('/static/images/new/logo-new-replace.png')}}"  width="210" height="92">
                        @endif
                    </dt>
                    <dd>
                        <h5><a href="/article/{{$article['id']}}">{{ $article['title'] }}</a></h5>
                        <p class="t-media-3">{{ $article['publish_time'] }}</p>
                        <p>{{ str_limit(strip_tags(stripslashes(htmlspecialchars_decode($article['intro']))), $limit=100, $end='...') }}</p>
                    </dd>
                </dl>
            @endforeach

            @endif

            <div class="t-mt34px">
                <div class="web-page">
                    @include('scripts.paginate', ['paginate'=>$paginate])
                </div>
            </div>
        </div>

    </div>

    <div class="t-wrap">
        <ul class="t-media2">
            <li><img src="{{assetUrlByCdn('/static/images/new/t-meida-img2.jpg')}}" width="192" height="61"></li>
            <li><img src="{{assetUrlByCdn('/static/images/new/t-meida-img3.jpg')}}" width="192" height="61"></li>
            <li><img src="{{assetUrlByCdn('/static/images/new/t-meida-img4.jpg')}}" width="192" height="61"></li>
            <li><img src="{{assetUrlByCdn('/static/images/new/t-meida-img5.jpg')}}" width="192" height="61"></li>
            <li><img src="{{assetUrlByCdn('/static/images/new/t-meida-img6.jpg')}}" width="192" height="61"></li>
        </ul>
    </div>
@endsection
@section('jspage')

@endsection