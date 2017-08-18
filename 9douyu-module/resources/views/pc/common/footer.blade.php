@if( isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'jiudouyu') === false )
    @include('pc.common.9douyuFooter')
@else
    @include('pc.common.jiudouyuFooter')
@endif
