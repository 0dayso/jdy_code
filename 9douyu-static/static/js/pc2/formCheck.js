/**
 * depend on jquery
 *
 */

/**
 * 扩展String 去除首尾空格
 * @returns {string}
 */
String.prototype.trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}

/**
 * 验证类
 */
var jdyVerify = function (options) {
    this.options = options;
}

/**
 * 是否为空
 * @param data
 * @returns {boolean}
 */
jdyVerify.prototype.isEmpty = function (data) {

    this.var_dump('run this.isEmpty(' + data + ')');

    var data = $.trim(data);
    if (data == '' || data == undefined || data == null)
        return true;
    else
        return false;
}
/**
 * 两个值是否相同
 * @param str1
 * @param str2
 * @returns {boolean}
 */
jdyVerify.prototype.isSame = function (str1, str2) {
    this.var_dump('run this.isSame(' + str1 + ',' + str2 + ')');
    if ($.trim(str1) == $.trim(str2)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否有效交易密码验证 || 密码
 * @param password
 * @returns {boolean}
 */
jdyVerify.prototype.isTradingPassword = jdyVerify.prototype.isPassword = function (password) {

    this.var_dump('run this.isTradingPassword(' + password + ')');

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
 * 是否有效手机号
 * @param phone
 * @returns {boolean}
 */
jdyVerify.prototype.isPhone = function (phone) {
    this.var_dump('run this.isPhone(' + phone + ')');
    var pattern = /^(13\d|14[57]|15[012356789]|18\d|17[013678])\d{8}$/;
    if (phone.length == 0) {
        return false;
    }
    ;
    if (!phone.match(pattern)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 是否有效email
 * @param email
 * @returns {boolean}
 */
jdyVerify.prototype.isEmail = function (email) {
    this.var_dump('run this.isEmail(' + email + ')');

    var pattern = /^[a-z_\\d](?:\\.?[a-z_\\d\\-]+)*@[a-z_\\d](?:\\.?[a-z_\\d\\-]+)*\\.[a-z]{2,3}$/i;
    if (email.length == 0) {
        return false;
    }
    ;
    if (!email.match(pattern) || email.indexOf('..') != -1) {
        return false;
    } else {
        return true;
    }
}

/**
 *长度限制
 * @param str
 * @param max number default 15 汉子
 * @returns {boolean}
 */
jdyVerify.prototype.isOverLimit = function (str, max) {
    var max = max || 15;
    this.var_dump('run this.isOverLimit(' + str + ',' + max + ')');
    this.var_dump(str.length);
    if(str.length > max){
        return false;
    }
    return true;
}
/**
 * debug
 * @param data
 * @returns {boolean}
 */
jdyVerify.prototype.var_dump = function (data) {
    if (this.options.debug)
        console.log(data);
}


$(function () {

    var options = {
        'debug': false
    };

    var jdyVerifyObj = new jdyVerify(options);

    /**
     * 修改手机号-交易密码验证
     */
    $("#modifyPhone").submit(function () {
        var $tradingpassword = $('#tradingpassword').val();
        if (jdyVerifyObj.isEmpty($tradingpassword)) {
            $("#tipMsg").text('请输入交易密码');
            return false;
        }
        if (!jdyVerifyObj.isTradingPassword($tradingpassword)) {
            $("#tipMsg").text('交易密码 6到16位的字母及数字组合');
            return false;
        }
        return true;
    });


//------------------------------------------------------------------------old
//表单
    $("#setNewPhone").submit(function () {
        if ($(this).data("lock")) return false;
        var flag = true;
        var tipMsg = '';
        $.each($(this).find("input[type=text], input[type=password]"), function () {
            if ($.trim($(this).val()) == '' || $(this).data("error")) {
                if (tipMsg == '') {
                    tipMsg = $(this).attr('tipMsg')
                }
                flag = false;
            }
        });
        if (!flag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $(this).data("lock", true);
            $("#tipMsg").text('').hide();
        }
    });

    /**
     * 修改||设置
     * 登录密码[modifyLoginForm]
     * 交易密码[modifyTP-form]
     *
     */
    $("#modifyLoginForm,#modifyTP-form").submit(function () {

        var formId = $(this).attr('id');

        var typeMsg = (formId == 'modifyLoginForm') ? '登录' : '交易';

        var textArr = {
            'oldPassword': '原' + typeMsg + '密码不能为空',
            'password': '新' + typeMsg + '密码不能为空',
            'password2': '请再次确认新' + typeMsg + '密码',
        }

        try {
            $("input[type=password]").each(function () {
                if ($(this).val() == '') {
                    throw textArr[$(this).attr("name")];
                }
            });
            var oldPassword = $("input[name=oldPassword]").val();
            var password1 = $("input[name=password]").val();
            var password2 = $("input[name=password2]").val();

            if (!jdyVerifyObj.isSame(password1, password2)) {
                throw '两次密码输入不一致';
            }

            //验证原密码是否正确
            var pwdFlag = (formId == 'modifyLoginForm') ? 'login' : 'trade';
            $.ajax({
                url:"/user/Information/doCheckPasswordAjax",
                type:"POST",
                data:{
                    oldPassword:oldPassword,pwdFlag:pwdFlag
                },
                dataType:"json",
                async:false,
                success:function(result) {
                    if (result.status) {
                        return true;
                    } else {
                        throw '原' + typeMsg + '密码错误';
                    }
                },
                error:function(msg) {
                    return false;
                }
            });
            //新登录密码
            if (formId == 'modifyLoginForm') {
                if (!jdyVerifyObj.isPassword(password1)) {
                    throw '新登录密码 6到16位的字母及数字组合';
                }
                if (!jdyVerifyObj.isPassword(password2)) {
                    throw '确认新登录密码 6到16位的字母及数字组合';
                }
            } else {
                if (!jdyVerifyObj.isTradingPassword(password1)) {
                    throw '新交易密码 6到16位的字母及数字组合';
                }
                if (!jdyVerifyObj.isTradingPassword(password2)) {
                    throw '确认新交易录密码 6到16位的字母及数字组合';
                }
            }
            $("#tipMsg").hide();
        } catch (tipMsg) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        }

    });

    /**
     * 找回交易密码－验证
     */
    $("#forgetTP-form").submit(function () {
        var textArr = {
            'captcha': '请输入图片验证码',
            'identity_card': '请输入身份证好吗',
            'code': '请输入手机验证码',
        }

        var failFlag = false;
        var tipMsg = '';
        $("input[type=text]").each(function () {
            if ($.trim($(this).val()) == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }
    });

    /**
     *  找回交易密码－设置
     */
    $("#setTP-form").submit(function () {

        var textArr = {
            'password': '新交易密码不能为空',
            'password2': '请再次确认新交易密码',
        }

        var failFlag = false;
        var tipMsg = '';
        $("input[type=password]").each(function () {
            if ($(this).val() == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });


        var str1 = $("input[name=password]").val();
        var str2 = $("input[name=password2]").val();


        if (!jdyVerifyObj.isTradingPassword(str1)) {
            if (tipMsg == '') {
                tipMsg = '新交易密码 6到16位的字母及数字组合';
            }
            failFlag = true;
        }

        if (!jdyVerifyObj.isTradingPassword(str2)) {
            if (tipMsg == '') {
                tipMsg = '确认新交易密码 6到16位的字母及数字组合';
            }
            failFlag = true;
        }

        if (!jdyVerifyObj.isSame(str1, str2)) {
            if (tipMsg == '') {
                tipMsg = '两次密码输入不一致';
            }
            failFlag = true;
        }

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }
    });

    /**
     * 设置紧急联系人－验证
     */
    $("#doModify-form").submit(function () {
        var textArr = {
            'code': '请输入手机验证码'
        }

        var failFlag = false;
        var tipMsg = '';
        $("input[type=text]").each(function () {
            if ($.trim($(this).val()) == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }
    });

    /**
     * 设置紧急联系人－设置
     */
    $("#doModifySet-form").submit(function () {
        var textArr = {
            'contactName': '联系人姓名不能为空.',
            'phone': '联系人手机号不能为空.'
        }

        var failFlag = false;
        var tipMsg = '';
        $("input[type=text]").each(function () {
            if ($.trim($(this).val()) == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });

        if (!jdyVerifyObj.isPhone($("#phone").val())) {
            if (tipMsg == '') {
                tipMsg = '请输入正确的手机号码';
            }
            failFlag = true;
        }

        //名字长度限制
        var username = $("input[name='contactName']").val();
        if(!jdyVerifyObj.isOverLimit(username, 10)){
            if (tipMsg == '') {
                tipMsg = '用户名最大长度为10个字符';
                failFlag = true;
            }
        }

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }
    });


    /**
     * 找回密码 第一步输入用户名
     */
    $("#preResetLoginPassword").submit(function () {
        var textArr = {
            'username': '用户名不能为空.'
        }

        var failFlag = false;
        var tipMsg = '';
        $("input[type=text]").each(function () {
            if ($.trim($(this).val()) == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }

    });

    /**
     *  找回密码－设置
     */
    $("#doResetLoginPassword").submit(function () {

        var textArr = {
            'captcha': '请输入图片验证码',
            'code': '请输入手机验证码',
            'password': '新登录密码不能为空',
            'password2': '请再次确认新登录密码',
        }

        var failFlag = false;
        var tipMsg = '';
        $("input").each(function () {
            if ($(this).val() == '') {
                if (tipMsg == '') {
                    tipMsg = textArr[$(this).attr("name")];
                }
                failFlag = true;
                return false;
            }
        });


        var str1 = $("input[name=password]").val();
        var str2 = $("input[name=password2]").val();


        if (!jdyVerifyObj.isTradingPassword(str1)) {
            if (tipMsg == '') {
                tipMsg = '新登录密码 6到16位的字母及数字组合';
            }
            failFlag = true;
        }

        if (!jdyVerifyObj.isTradingPassword(str2)) {
            if (tipMsg == '') {
                tipMsg = '确认新登录密码 6到16位的字母及数字组合';
            }
            failFlag = true;
        }

        if (!jdyVerifyObj.isSame(str1, str2)) {
            if (tipMsg == '') {
                tipMsg = '两次密码输入不一致';
            }
            failFlag = true;
        }

        if (failFlag) {
            $("#tipMsg").text(tipMsg).show();
            return false;
        } else {
            $("#tipMsg").hide();
        }
    });


});