/**
 * card     身份证号码
 * @return  boolean
 * @example
 var result = checkIdCard('440811199004152222');
 */
function checkIdCard(card) {
    if (typeof card == "undefined") {
        return false;
    }

    if (!card.match(/^(\d{15}|\d{17}X|\d{18})$/i)) {
        return false;
    }

    if (card.length == 15) {
        card = formatCard15to18(card);
    }

    return checkSum(card);
}

function calVerifyNumber(base) {
    if (base.length != 17)
        return false;

    var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

    var verify = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

    var checkSum = 0;

    for (var i = 0; i < base.length; i++) {
        checkSum += base.substr(i, 1) * factor[i];
    }

    var mod = checkSum % 11;

    var verifyNumber = verify[mod];

    return verifyNumber;
}

function formatCard15to18(card) {
    if (card.length != 15)
        return false;
    var specialArr = ['996', '997', '998', '999'];
    if (specialArr.in_array(card.substr(12, 3))) {
        card = card.substr(0, 6) + '18' + card.substr(6, 9);
    } else {
        card = card.substr(0, 6) + '19' + card.substr(6, 9);
    }

    card = card + calVerifyNumber(card);
}

function checkSum(card) {
    if (card.length != 18)
        return false;

    var base = card.substr(0, 17);

    if (calVerifyNumber(base) != card.substr(17, 1).toUpperCase()) {
        return false;
    }

    return true;
}

Array.prototype.S = String.fromCharCode(2);
Array.prototype.in_array = function (e) {
    var r = new RegExp(this.S + e + this.S);
    return (r.test(this.S + this.join(this.S) + this.S));
}

//---------------------------

var wapFormObj = null;

function wapFormValidate(options) {
    this.options = options;

}

wapFormValidate.prototype.onSubmit = function (formId) {
    var pThis = this;
    //表单是否存在
    if ($("#" + formId).length <= 0) {
        return true;
    }
    //开始验证
    $("#" + formId).submit(function () {
        var is = true;
        switch (formId) {
            case 'identity-verify':
                is = pThis.identityVerify();
                break;
            case 'modifyPhone':
                is = pThis.modifyPhone();
                break;
            case 'modifyLoginForm':
                is = pThis.modifyLoginForm();
                break;
            case 'modifyTP-form':
                is = pThis.modifyTPForm();
                break;
            case 'forgetTP-form':
                is = pThis.forgetTPForm();
                break;
            case 'setTP-form':
                is = pThis.setTPForm();
                break;
            case 'resetLP-form':
                is = pThis.resetLPForm();
                break;

        }
        return is;
    });
}

wapFormValidate.prototype.tip = function (msg) {
    $('#error_tip').html(msg).show();
}

/**
 * @desc 6到16位的字母及数字组合
 * @param jqueryClass
 * @returns {Boolean}
 */
wapFormValidate.prototype.checkPassword = function (jqueryClass) {
    var password = $.trim($('.' + jqueryClass).val());
    var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
    if (password.length == 0) {
        return false;
    }
    if (!password.match(pattern)) {
        return false;
    } else {
        return true;
    }
}

/**
 * @desc 6位数字
 * @param jqueryClass
 * @returns {Boolean}
 */
wapFormValidate.prototype.checkPasswordTwo = function (jqueryClass) {
    var password = $.trim($('.' + jqueryClass).val());
    var pattern = /[0-9]{6}/;
    if (password.length == 0) {
        return false;
    }
    if (!password.match(pattern)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 身份证号码验证 基于原来的js
 * @param {type} jqueryClass
 * @returns {Boolean}
 */
wapFormValidate.prototype.identityCard = function (jqueryClass) {
    var card = $.trim($('.' + jqueryClass).val());
    return checkIdCard(card);
}


/**
 * 验证两个值一致性
 * @param {type} jqueryClass
 * @returns {Boolean}
 */
wapFormValidate.prototype.aeqb = function (valClass2, valClass1) {
    var a = $.trim($('.' + valClass2).val());
    var b = $.trim($('.' + valClass1).val());
    return (a == b);
}

/**
 * 
 * 验证表单地址：http://testwx.9douyu.com/identity/verify.html 
 */
wapFormValidate.prototype.identityVerify = function () {
    var identityCard = $.trim($('.wapForm-check-identityCard').val());
    var real_name = $.trim($('#real_name').val());
    if (real_name == '') {
        //this.tip('您的真实姓名不能为空.');
        return false;
    }
    if (identityCard == '') {
        //this.tip('身份证不能为空.');
        return false;
    }
    if (!this.identityCard('wapForm-check-identityCard')) {
        this.tip('身份证校验失败');
        return false;
    }
    ;
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('6到16位的字母及数字组合');
        return false;
    }
    ;
    return true;
}



/**
 * 
 * 验证表单地址：https://testwx.9douyu.com/information/forgetTradingPassword.html
 */
wapFormValidate.prototype.forgetTPForm = function () {
    var identityCard = $.trim($('.wapForm-check-identityCard').val());


    if (identityCard == '') {

        return false;
    }
    if (!this.identityCard('wapForm-check-identityCard')) {
        this.tip('身份证校验失败');
        return false;
    }
    if (!this.checkPasswordTwo('wapForm-check-code')) {
        this.tip('请输入有效的短信验证码');
        return false;
    }

    return true;
}


/**
 * 
 * 验证表单地址：http://testwx.9douyu.com/information/modifyPhone.html
 */
wapFormValidate.prototype.modifyPhone = function () {
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('6到16位的字母及数字组合');
        return false;
    }
    return true;
}

/**
 * 
 * 验证表单地址：https://testwx.9douyu.com/information/resetTradingPassword
 */
wapFormValidate.prototype.setTPForm = function () {
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('6到16位的字母及数字组合');
        return false;
    }
    return true;
}
/**
 * 
 * 验证表单地址：https://testwx.9douyu.com/login/verifyPhoneCode.html
 */
wapFormValidate.prototype.modifyLoginForm = function () {
    if (!this.checkPassword('wapForm-check-checkPassword_old')) {
        this.tip('原登录密码 6到16位的字母及数字组合');
        return false;
    }
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('新登录密码 6到16位的字母及数字组合');
        return false;
    }

    return true;
}

/**
 * 
 * 验证表单地址：https://testwx.9douyu.com/information/modifyLoginPassword.html
 */
wapFormValidate.prototype.resetLPForm = function () {
    if (!this.checkPassword('wapForm-check-checkPassword_old')) {
        this.tip('原登录密码 6到16位的字母及数字组合');
        return false;
    }
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('新登录密码 6到16位的字母及数字组合');
        return false;
    }
    if (!this.aeqb('wapForm-check-checkPassword','wapForm-check-checkPassword_old')) {
        this.tip('两次输入密码不一致');
        return false;
    }
    return true;
}

/**
 * 
 * 验证表单地址：https://testwx.9douyu.com/information/modifyTradingPassword.html
 */
wapFormValidate.prototype.modifyTPForm = function () {
    if (!this.checkPassword('wapForm-check-checkPassword_old')) {
        this.tip('原交易密码 6到16位的字母及数字组合');
        return false;
    }
    if (!this.checkPassword('wapForm-check-checkPassword')) {
        this.tip('新交易密码 6到16位的字母及数字组合');
        return false;
    }
    return true;
}


wapFormValidate.prototype.debug = function (data) {
    if (!this.options.debug)
        return;
    console.log(data);
}

$(function () {
    var options = {
        'debug': true,
    }
    wapFormObj = new wapFormValidate(options);
    wapFormObj.onSubmit('identity-verify');
    wapFormObj.onSubmit('modifyPhone');
    wapFormObj.onSubmit('modifyLoginForm');
    wapFormObj.onSubmit('modifyTP-form');
    wapFormObj.onSubmit('forgetTP-form');
    wapFormObj.onSubmit('setTP-form');
    wapFormObj.onSubmit('resetLP-form');

});

//---------------------------
//修改交易密码、设置交易密码、修改手机号码、修改登录密码
$(".wapForm-check-checkPassword, .wapForm-check-checkPassword_old").blur(function () {
    var password = $.trim($(this).val());
    var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
    if (password.length == 0) {
        return false;
    }

    if (!password.match(pattern)) {
        $('#error_tip').html('6到16位的字母及数字组合').show();
    } else {
        $('#error_tip').html('').show();
    }
});

$("input[name=identity_card]").blur(function () {
    var card = $.trim($(this).val());
    if (card == '') {
        return false;
    }
    if (!checkIdCard(card)) {
        $('#error_tip').html('身份证校验失败').show();
        return false;
    } else {
        $('#error_tip').html('').show();
    }
});