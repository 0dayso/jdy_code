@extends('wap.common.wapBase')

@section('title', '为谁开通')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
@endsection

@section('content')
    <table class="family-option">
    @foreach($familyRoles as $rowRoles)
        <tr>
        @foreach($rowRoles as $role)
            <td width="25%" onclick="location.href='/family/phone/{{ urlencode($role) }}'">{{ $role }}</td>
        @endforeach
        </tr>
    @endforeach
    </table>
@endsection
