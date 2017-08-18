<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type="hidden" name="body"         value="{{ $body }}">
    <input type="hidden" name="defaultbank"  value="{{ $defaultbank }}">
    <input type="hidden" name="merchant_ID"   value="{{ $merchant_ID }}">
    <input type="hidden" name="order_no"   value="{{ $order_no }}">
    <input type="hidden" name="notify_url" value="{{ $notify_url }}">
    <input type="hidden" name="payment_type" value="{{ $payment_type }}">
    <input type="hidden" name="paymethod" value="{{ $paymethod }}">
    <input type="hidden" name="return_url"  value="{{ $return_url }}">
    <input type="hidden" name="seller_email" value="{{ $seller_email }}">
    <input type="hidden" name="service"  value="{{ $service }}">
    <input type="hidden" name="charset" value="{{ $charset }}">

    <input type="hidden" name="title"  value="{{ $title }}">
    <input type="hidden" name="total_fee"  value="{{ $total_fee }}">
    <input type="hidden" name="sign"   value="{{ $sign }}">
    <input type="hidden" name="sign_type" value="{{ $sign_type  }}">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>