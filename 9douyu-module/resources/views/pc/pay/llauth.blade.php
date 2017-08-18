<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>付款 - </title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
    <link href="__PUBLIC2__/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>
<body>
<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type="hidden" name="version"  value="{{ $version }}">
    <input type="hidden" name="oid_partner"  value="{{ $oid_partner }}">
    <input type="hidden" name="user_id"  value="{{ $user_id }}">
    <input type="hidden" name="timestamp"  value="{{ $timestamp }}">
    <input type="hidden" name="sign_type"  value="{{ $sign_type }}">
    <input type="hidden" name="sign"  value="{{ $sign }}">
    <input type="hidden" name="busi_partner"  value="{{ $busi_partner }}">
    <input type="hidden" name="no_order"  value="{{ $no_order }}">
    <input type="hidden" name="dt_order"  value="{{ $dt_order }}">
    <input type="hidden" name="name_goods"  value="{{ $name_goods }}">
    <input type="hidden" name="info_order"  value="{{ $info_order }}">
    <input type="hidden" name="money_order"  value="{{ $money_order }}">
    <input type="hidden" name="notify_url"  value="{{ $notify_url }}">
    <input type="hidden" name="url_return"  value="{{ $url_return }}">
    <input type="hidden" name="userreq_ip"  value="{{ $userreq_ip }}">
    <input type="hidden" name="url_order"  value="">
    <input type="hidden" name="valid_order"  value="{{ $valid_order }}">
    <input type="hidden" name="bank_code"  value="">
    <input type="hidden" name="pay_type"  value="{{ $pay_type }}">
    <input type="hidden" name="no_agree"  value="">
    <input type="hidden" name="shareing_data"  value="">
    <input type="hidden" name="risk_item"  value='{!! $risk_item !!}'>
    <input type="hidden" name="id_type"  value="{{ $id_type }}">
    <input type="hidden" name="id_no"  value="{{ $id_no }}">
    <input type="hidden" name="acct_name"  value="{{ $acct_name }}">
    <input type="hidden" name="flag_modify"  value="{{ $flag_modify }}">
    <input type="hidden" name="card_no"  value="{{ $card_no }}">
    <input type="hidden" name="back_url"  value="">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>