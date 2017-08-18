{{--*/ $userId=App\Http\Logics\User\SessionLogic::getTokenSession()['id'] /*--}}

{{--*/ $familyAuthLogoutStr=App\Tools\FamilyAuth::getAuthStr(App\Tools\FamilyAuth::getAuthToUserId($userId), $userId) /*--}}

@if(!empty($familyAuthLogoutStr))
    <div class="family-auth-logout" onclick="familyLogout();">
        点击退出家庭账户
    </div>
@endif

<script>

    //家庭账户退出
    function familyLogout(){
        var logoutStr = '{{ $familyAuthLogoutStr }}';
        if(logoutStr!=''){
            var client = '{{ Session::get("CLIENT") }}';
            $.ajax({
                url : '/family/logoutAuthAccount',
                type: 'POST',
                dataType: 'json',
                data: {'familyAuth': logoutStr},
                success : function(data) {
                    $('.family-auth-logout').addClass('hide');
                    if(data.status===false) {
                        location.href='/family/home';
                    } else {
                        if(client=='ios'){
                            window.location.href="objc:gotoAccount";
                        }else{
                            window.jiudouyu.gotoAccount();
                        }
                    }
                }
            });
        }
    }
</script>