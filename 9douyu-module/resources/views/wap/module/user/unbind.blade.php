@extends('wap.common.wapBase')

@section('content')
    <article>
        <section class="banner-box mb20">
            <form action="" method="post">

                <input type="submit" value="解除绑定" class="m-btn mb35 mt15">
                <input type="submit" value="取消" class="m-btn">
                <input type="hidden" name="_token" value="{{csrf_token()}}">

            </form>
        </section>
    </article>
@endsection

@section('jsScript')

    @include('wap.common.js')

    @if(Session::has('msg'))
        <script>
            $(document).ready(function () {
                $(this).mobileTip("{{ Session::get('msg') }}");
            });
        </script>
    @endif
@endsection
