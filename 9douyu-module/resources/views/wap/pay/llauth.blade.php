<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>连连支付wap交易接口</title>
</head>
<body>
<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input type='hidden' name='req_data' value="{{ $parameter }}" />
</form>
<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>