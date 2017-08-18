$("#modifyLoginForm,#modifyTP-form").submit(function() {
		var textArr = {
			'oldPassword':	'原登录密码不能为空',
			'password': '新登录密码不能为空',
			'password2':'请再次确认新登录密码',
		}
		
		var failFlag = false;
		$("input[type=password]").each(function(){
			if($(this).val() == '') {
				$(this).tableShowTips(textArr[$(this).attr("name")]);
				failFlag = true;
				return false;
			}
		});
		
		if(failFlag) return false;
});