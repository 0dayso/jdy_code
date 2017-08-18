<div class="t-help-nav">
    <h3>帮助中心</h3>
    @foreach ( $helpList as $help )
        @if ( $help['id'] == $current['id'] )
            <a href="{{ App\Tools\ToolUrl::getUrl("/help/".$help['id']) }}" class="t-blue">{{ $help["title"] }}</a>
        @else
            <a href="{{ App\Tools\ToolUrl::getUrl("/help/".$help['id']) }}">{{ $help["title"] }}</a>
        @endif
    @endforeach
</div>