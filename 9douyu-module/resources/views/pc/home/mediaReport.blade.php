
<div class="new-media">
    <div class="wrap">
        <div class="web-media clearfix">
            <div class="web-media-title">
                <p><strong>媒体报道</strong></p>
                <p><span>来自国内外知名媒体的客观报道</span></p>
            </div>
            <ul class="web-media-list">
                @foreach( $mediaList as $key => $media )
                    @if ( empty($media['id']) )
                        @continue
                    @endif
                    <li>
                        <a href="">
                            <div class="web-media-img">
                                @if ( !empty($picList[$key]['path']))
                                    <img src="{{assetUrlByCdn('/resources/'.$picList[$key]['path'])}}" width="266" height="72">
                                @else
                                    <img src="{{assetUrlByCdn('/static/images/new/logo-new-replace.png')}}"  width="266" height="72">
                                @endif
                            </div>
                            <h4>{{$media['title']}}</h4>
                            <p>{{ str_limit(strip_tags(stripslashes(htmlspecialchars_decode($media['intro']))), $limit=70, $end='...') }}</p>
                        </a>
                        <a href="/article/{{$media['id']}}.html" target="_blank" class="mask"></a>
                        <a href="/article/{{$media['id']}}.html" target="_blank" class="text">查看更多</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>