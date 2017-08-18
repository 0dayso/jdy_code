<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<form action="{{ $apiGateWay }}" method="post" name="orderForm">
    <input name="version" type="hidden" id="version" value="{{$version}}" />
    <input name="input_charset" type="hidden" id="input_charset" value="{{$input_charset}}" />
    <input name="language" type="hidden" id="language" value="{{$language}}" />
    <input name="terminal_id" type="hidden" id="terminal_id" value="{{$terminal_id}}" />
    <input name="txn_type" type="hidden" id="txn_type" value="{{$txn_type}}" />
    <input name="txn_sub_type" type="hidden" id="txn_sub_type" value="{{$txn_sub_type}}" />
    <input name="member_id" type="hidden" id="member_id" value="{{$member_id}}" />
    <input name="data_type" type="hidden" id="data_type" value="{{$data_type}}" />
    <textarea name="data_content" style="display:none;" id="data_content">{{$data_content}}</textarea>
    <input name="back_url" type="hidden" id="back_url" value="{{$back_url}}" />
    <input type="hidden" name="_token" value="{{csrf_token()}}">
</form>

<script type="text/javascript">
    document.orderForm.submit();
</script>
</body>
</html>