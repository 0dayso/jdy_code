<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Keywords" content="{$currentArticle.keywords|default=C('META_KEYWORD')}" />
<meta name="Description" content="{$currentArticle.description|default=C('META_DESCRIPTION')}" />
<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
<title>鱼客私塾-详情页</title>
<link href="__PUBLIC2__/css/guest.css" rel="stylesheet" type="text/css">
</head>

<body>
<tagLib name="List"/>
<include file="Public@Index:header" />

<!-- main begins -->
<div class="wrap hidden">
  <div class="fl width650 mt30">
   <div class="school-title">
        <a href="/" class="mr10">九斗鱼</a>><a href="{:U('/guest')}" class="ml10 mr10">鱼客攻略</a>><a href="{:U('/guest/school')}" class="ml10">鱼客私塾</a>><span class="ml10">正文</span> 
      </div>
      <div class="school-content">
          <h1>{$currentArticle.title}</h1>
          <p class="text-indent">{$currentArticle.content|htmlspecialchars_decode|stripslashes}</p>
          
       </div>
       
       <!-- 百度分享 开始 -->
      <div class="school-share mb30">
      <div class="bdsharebuttonbox"><span class="share-txt">分享到：</span><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_more" data-cmd="more"></a></div>
        <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
      </div>
  <!-- 百度分享 结束 -->
  <if condition="$recentData">
  <div class="school-content-prevtitle mb20">往期私塾</div>
  <foreach name="recentData" key="key" item="a">
  
  <if condition="$key%2 eq 0">
      <div class="fl width280 school-pre-line mb30">
  <else />
      <div class="fr width280 mb20">
  </if>
  
  <h2 class="f16 mb10"><a href="{:U('/guest/school/detail', array('id' => $a['id']))}" class="fontblue">{$a.title}</a></h2>
<p class="text-indent line-heigh28">{$a.intro|htmlspecialchars_decode|stripslashes}</p>
  </div>
     </foreach>
     </if>
  <div class="clear"></div>
    </div>
    
    <div class="fr width282 mt30">
    
        <include file="Guest@Block:right" />
    </div>
</div>
<!-- main ends -->

<include file="Public@Index:blueFooter" />

</body>
</html>
