<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type="hidden" name="v_mid"         value="{{ $v_mid  }}">
    <input type="hidden" name="v_oid"         value="{{ $v_oid }}">
    <input type="hidden" name="v_amount"      value="{{ $v_amount }}">
    <input type="hidden" name="v_moneytype"   value="{{ $v_moneytype }}">
    <input type="hidden" name="v_url"         value="{{ $v_url }}">
    <input type="hidden" name="v_md5info"     value="{{ $v_md5info }}">
    <input type="hidden" name="pmode_id"      value="{{ $pmode_id }}">
    <input type="hidden" name="remark1"       value="{{ $remark1 }}">
    <input type="hidden" name="remark2"       value="{{ $remark2 }}">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>