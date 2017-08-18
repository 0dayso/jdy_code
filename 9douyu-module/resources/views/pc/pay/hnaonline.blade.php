<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type="hidden" name="version"  value="{{ $version }}">
    <input type="hidden" name="serialID"  value="{{ $serialID }}">
    <input type="hidden" name="submitTime"  value="{{ $submitTime }}">
    <input type="hidden" name="failureTime"  value="{{ $failureTime }}">
    <input type="hidden" name="customerIP"  value="{{ $customerIP }}">
    <input type="hidden" name="orderDetails"  value="{{ $orderDetails }}">
    <input type="hidden" name="totalAmount"  value="{{ $totalAmount }}">
    <input type="hidden" name="type"  value="{{ $type }}">
    <input type="hidden" name="buyerMarked"  value="{{ $buyerMarked }}">
    <input type="hidden" name="payType"  value="{{ $payType }}">
    <input type="hidden" name="orgCode"  value="{{ $orgCode }}">
    <input type="hidden" name="currencyCode"  value="{{ $currencyCode }}">
    <input type="hidden" name="directFlag"  value="{{ $directFlag }}">
    <input type="hidden" name="borrowingMarked"  value="{{ $borrowingMarked }}">
    <input type="hidden" name="couponFlag"  value="{{ $couponFlag }}">
    <input type="hidden" name="platformID"  value="{{ $platformID }}">
    <input type="hidden" name="returnUrl"  value="{{ $returnUrl }}">
    <input type="hidden" name="noticeUrl"  value="{{ $noticeUrl }}">
    <input type="hidden" name="partnerID"  value="{{ $partnerID }}">
    <input type="hidden" name="remark"  value="{{ $remark }}">
    <input type="hidden" name="charset"  value="{{ $charset }}">
    <input type="hidden" name="signType"  value="{{ $signType }}">
    <input type="hidden" name="signMsg"   value="{{ $signMsg }}">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>