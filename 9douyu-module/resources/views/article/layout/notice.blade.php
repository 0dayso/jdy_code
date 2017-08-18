<extend name="Public@Template:frontHome" />

<block name="cssPage">
<?php 
$jscssminify->addStyleSheet(__PUBLIC2__ . '/css/style.css');
?>
</block>

<block name="title">
    <title>{$currentArticle.title} - {:C('TITLE_SUFFIX')}</title>
</block>

<block name="main">

<div class="wrap-white">
  <div class="wrap shadow-bg">
    <div class="notice-title"> <a href="{:U('/')}">九斗鱼</a>><a href="{:U(sprintf('/category/%s',$currentArticle['category']['id']))}">{$currentArticle.category.name}</a>><span>正文<!--{$currentArticle.title}--></span> </div>
  </div>
</div>

<!-- notice begins -->

<div class="wrap">
  <div class="notice-left">
    <div class="notice-left-title">
      <h1>{$currentArticle.title}</h1>
      <div class="fl">发布时间：<span>{$currentArticle.publish_time}</span>浏览量：<span>{$currentArticle.hits|intval}</span></div>
      <div class="fr"><include file="Public@Index:sharemore" /></div>
    </div>
<div class="clear"></div>
    {$currentArticle.content|htmlspecialchars_decode} 
    <!-- <div class="notice-left-page"> 
        <a href="#" class="fr">【下一篇】：1月鱼饭节：爱生活爱九斗鱼！</a>
        <a href="#">【上一篇】：九斗鱼1周年域名促销活动！</a>
      </div> -->
    
  </div>
  <div class="notice-right mt14">
     
    <div class="sidebar">
      <h2>更多消息</h2>
      <ul>
            <foreach item="a" name="articleList">
            <li><a href="{:U(sprintf('/article/%s',$a['id']))}" title="{$a.title}">{$a.title|msubstr=###,0,15,'utf-8'}</a></li>
            </foreach>
      </ul>
      <p><a href="{:U(sprintf('/category/%s',$currentArticle['category_id']))}">查看更多</a></p>
    </div>

    </div>
  <div class="clear"></div>
  </div>
</block>