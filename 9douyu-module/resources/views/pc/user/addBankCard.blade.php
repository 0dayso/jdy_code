@extends('pc.common.layout')

@section('title', '添加提现银行卡')

@section('content')

    <div class="m-myuser">
        <!-- account begins -->

        @include('pc.common.leftMenu')

        <div class="m-rightcon m-pr">
            <p class="m-tocash m-addpo">添加提现银行卡</p>
            <div class="m-process m-pr">
                <p class="m-addinfor"><i class="m-addbefore m-bluest"></i><span class="m-blueco">填写银行卡信息</span></p>
                <p class="m-line"></p>
                <p class="m-success"><i class="m-addbefore m-grayst"></i><span class="m-grayco">成功添加银行卡</span></p>
            </div>
                <p class="m-cardtips">温馨提醒：下述银行卡号的开户人姓名必须为"<strong>{{$userInfo['real_name']}}</strong>"，银行卡号必须填写正确,否则会导致添提现银行卡失败。 </p>
            <span></span> 我们保证严格保密您填写的任何信息，绝不向任何第三方透露。 </p>
            <form method="post" action="/user/bankcard/submit" id="addBankCardForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="system-message form-tips text-{:oneOffSession('session_notice_type')}" style="color: red;"></div>

                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>姓名</td>
                        <td>{{$userInfo['real_name']}}</td> <input type="hidden" name="real_name" value="{{$userInfo['real_name']}}"/> <input type="hidden" name="user_id" value="{{$userInfo['id']}}"/>
                    </tr>
                    <tr>
                        <td>身份证号</td>
                        <td>{{ substr($userInfo['identity_card'],0,4) }}********{{ substr($userInfo['identity_card'],-4) }}</td><input type="hidden" name="id_card" value="{{$userInfo['identity_card']}}"/>
                    </tr>
                    <tr>
                        <td>银行卡号</td>
                        <td class="m-pr m-height50">
                            <p class="m-cardnum" id="cardNumberBig"></p>
                            <input type="text" name="card_no" class="m-user"/>
                            <p class="m-positi" id="cardnumber_msg"></p>
                        </td>
                    </tr>
                </table>
                <div class="m-wrongtip">
                    @if(Session::has('errors'))
                        {{  Session::get('errors') }}
                    @endif
                </div>
                <input type="hidden" name="phone" value="{{$userInfo['phone']}}"/>

                <button type="submit" class="btn btn-red btn-large btn-block w230px mauto" >确认提交</button>
            </form>
        </div>

        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection

@section('jspage')
    <script type="text/javascript">
        $(document).ready(function () {

            var luhn = function(num){
                var str='';
                var numArr = num.split('').reverse();
                for(var i=0;i<numArr.length;i++){
                    str+= (i % 2 ? numArr[i] * 2 : numArr[i]);
                }
                var arr = str.split('');
                return  eval(arr.join("+")) % 10 == 0;
            }
            $("#addBankCardForm").submit(function(){
                var card_no = $.trim($("input[name='card_no']").val());
                var len = card_no.length;
                if(card_no ==''){
                    $(".m-wrongtip").html('银行卡号不能为空');
                    return false;
                }

                if ((len == 19 || len == 16 || len == 18) && luhn(card_no)) {
                    $(".m-wrongtip").html('');
                } else {
                    $(".m-wrongtip").html('请输入正确的银行卡号');
                    return false;
                }

            });

        });
    </script>
@endsection
