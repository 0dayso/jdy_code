@if(!empty($pageInfo) && $pageInfo['last_page'] >1)
    <div class="">
        <ul class="pagination">
            <li><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }}page=1">首页</a></li>
            <li><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }} page={{ $pageInfo['current_page']-1 }}">上一页</a></li>
            @for($i=1;$i<=$pageInfo['last_page'];$i++)
                @if($i==$pageInfo['current_page'])
                    <li class="active"><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }}page={{ $i }}">{{ $i }}</a></li>
                @elseif( ($i<$pageInfo['current_page'] && ($i+4)>$pageInfo['current_page']) || ($i > $pageInfo['current_page'] && ($i-4)<$pageInfo['current_page']) )
                    <li><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }}page={{ $i }}">{{ $i }}</a></li>
                @endif
            @endfor
            <li><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }}page={{ ($pageInfo['current_page']+1)>$pageInfo['last_page']?$pageInfo['last_page']:($pageInfo['current_page']+1) }}">下一页</a></li>
            <li><a href="{{ $pageInfo['url'].(strpos($pageInfo['url'],'?') ? '&' : '?') }}page={{$pageInfo['last_page']}}">尾页</a></li>
            <li><a>共{{$pageInfo['last_page']}}页</a></li>
        </ul>
    </div>
@endif