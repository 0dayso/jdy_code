<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $js->config(
            array(
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'hideMenuItems',
                    'showMenuItems',
            ), false, false) ?>)
    //第二个参数为 开启debug
    //第三个参数为 开启beta
    ;
</script>

<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     * 如有问题请通过以下渠道反馈：
     * 邮箱地址：weixin-open@qq.com
     * 邮件主题：【微信JS-SDK反馈】具体问题
     * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
     */
    var weixin = function (title,link,imgurl,desc){
        wx.ready(function () {
            //分享到朋友圈
            wx.onMenuShareTimeline({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            //发送给朋友
            wx.onMenuShareAppMessage({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            //分享到QQ
            wx.onMenuShareQQ({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
            //微博
            wx.onMenuShareWeibo({
                title: title,
                desc: desc,
                link: link,
                imgUrl: imgurl
            });
        });
    };


    // 需要分享的内容，请放到ready里
    weixin('{{ $shareConfig['share_title'] }}','{{ $shareConfig['line_link'] }}','{{ $shareConfig['img_url'] }}','{{ $shareConfig['desc_content'] }}');


</script>