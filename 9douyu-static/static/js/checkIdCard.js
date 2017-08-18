(function($){
$(document).ready(function(){
	$("input[name=real_name]").blur(function(){
        var realname = $.trim($("input[name=real_name]").val());
		var pattern = /^[\u4E00-\u9FA5]+$/;
		if(!realname.match(pattern)){
			$(this).tableShowTips('请输入正确的名字');
		}else {			
			$(this).tableShowTips('','success');
		}
    });

	
	$("input[name=identity_card]").blur(function() {
		var card = $.trim($(this).val());
		if(card == '') {
			return false;	
		}
		if(!checkIdCard(card)) {
			$(this).tableShowTips("身份证校验失败");
			return false;
		} else {
			$(this).tableShowTips('','success');
		}
	});
	
	$("#verifyForm").submit(function() {
		var card = $.trim($("input[name=identity_card]").val());
		if(!checkIdCard(card)) {
			$("input[name=identity_card]").tableShowTips("身份证校验失败");
			return false;
		}
	});
});
})(jQuery);

/**
 * card		身份证号码
 * @return  boolean
 * @example
	var result = checkIdCard('440811199004152222');
 */
function checkIdCard(card) {
	if(typeof card == "undefined") {
		return false;
	}
    
    if(!card.match(/^(\d{15}|\d{17}X|\d{18})$/i)) {
        return false;
    }
	
	if(card.length == 15) {
		card = formatCard15to18(card);
	}
	
	return checkSum(card);
}

function calVerifyNumber(base) {
	if(base.length != 17) return false;
	
	var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
	
	var verify = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
	
	var checkSum = 0;
	
	for(var i = 0; i < base.length; i++) {
		checkSum += base.substr(i, 1) * factor[i];
	}
	
	var mod = checkSum % 11;
	
	var verifyNumber = verify[mod];
	
	return verifyNumber;
}

function formatCard15to18(card) {
	if(card.length != 15) return false;
	var specialArr = ['996', '997', '998', '999'];
	if(specialArr.in_array(card.substr(12, 3))) {
		card = card.substr(0, 6) + '18' + card.substr(6, 9);
	} else {
		card = card.substr(0, 6) + '19' + card.substr(6, 9);
	}
	
	card = card + calVerifyNumber(card);
}

function checkSum(card) {
	if(card.length != 18) return false;
	
	var base = card.substr(0, 17);
	
	if(calVerifyNumber(base) != card.substr(17, 1).toUpperCase()) {
		return false;
	}
	
	return true;
}

Array.prototype.S=String.fromCharCode(2);  
Array.prototype.in_array=function(e) {
    var r=new RegExp(this.S+e+this.S);  
    return (r.test(this.S+this.join(this.S)+this.S));  
}