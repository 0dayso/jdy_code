# 客户端与dingo 版本号

标签（空格分隔）： dingo api

---
以下请客户端小伙伴确认1，2 和 5
@张维 测试数据格式地址：http://android.hexing.dev.9dy.in/test

以下如有不妥 请伙伴们无情拍砖

> 由于app4.0 服务端用dingo api 客户端请求接口形式出现改变；版本需要规范

----------

**1. 请求方式**

> 由之前的url+gateway + post{接口名} 改成 域名 + 接口名 + header 如下：
```
#    组件地址：https://github.com/dingo/api
#    中文文档：https://github.com/liyu001989/dingo-api-wiki-zh

# 4.0 为传递版本号、json为数据格式
header
Accept： application/vnd.jdy.4.0+json 

```


----------


**2.服务端版本控制规范**

> 主版本.功能迭代.客户端ui迭代


    1. 若 没有服务端配合的客户端迭代 则 请客户端 用 4.0.x 发布新版本， 4.0请求服务端；x 可以为任意数字

    2. 当 服务端接口 如：say/hello 接口 出现迭代  请客户端 用 4.1.x     发布新版本， 新版本用4.1请求， 老版本继续用4.0请求；

    3. 当服务端出现大规模迭代 ，请客户端 用 5.0.x 发布新版本， 客户端新版本 用5.0请求，老版本请求不变；

    4. 服务端接口修复 bug  请求版本号不变；


----------
**3.路由、控制器目录划分**
```
router
    #拆分router 【之前的app api 加载了 web 中间件组 多余的资源】
    #app/Http/groupAppApi.php dingo路由在此文件中定义 里面有demo
controller
    #【 格式化拆分到中间件：状态码拆分到logic； controller 直接返回数组即可】
    # app/Http/Controllers/AppApi/版本号
    # 如：app/Http/Controllers/AppApi/V4/demoController.php

``` 
**4.接口安全**
安全、加密、防重复、防篡改 从controller 中移到
中间件：app/Http/Middleware/AppApiAuth.php  
现有 防重复、防篡改 是不是用了https 后就不用太考虑安全问题了
     
    
**5.返回格式 规范状态码**
中间件：app/Http/Middleware/AppApiResponseFormat.php 格式化
```
# 当 value 为空 清除key
{
  "code": 20000, #状态码在 
  "msg": "原因短语",
  "data": {
    "key": "value"
  }
}
```

状态码现有

```
CODE_SUCCESS                = 2000,  //服务端返回正常数据
CODE_ERROR                  = 4000,  //服务端异常
CODE_TRADING_PASSWORD       = 4009,  //交易密码输入错误
CODE_LOGIN_EXPIRE           = 4010,  //登录超时
CODE_PHONE_NOT_ACTIVATION   = 6001,  //手机号未激活
CODE_PHONE_CAN_REGISTER     = 6000,  //手机号可注册
```

根据伙伴们说 状态码太少是不是可以像下面这个样子
可以按照一定规格来划分状态码

成功状态码
| 编码        |  原因短语  |
| --------   | -----:  |
| 20000     | 成功 | 


错误状态码
| 编码        |  原因短语  |
| --------   | -----:  |
|20001	|未知错误|
|20002	|请求方式错误|
|20003	|参数非法|
|20004	|sign错误|
|20005	|重复提交|
|20006	|操作频繁|
|...	|...|