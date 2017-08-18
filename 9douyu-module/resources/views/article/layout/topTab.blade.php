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
    <h1 class="inner-title">{$currentArticle.title}</h1>
  </div>
</div>
<div class="wrap">
  <div class="inner-box ptnone">
    <div class="toptab-title">
      <ul>
        <foreach name="articleList" item="article">
          <if condition="$article['id'] != $currentArticle['id']">
            <li><a href="{:U(sprintf('/article/%s',$article['id']))}">{$article.title}</a></li>
            <else />
            <li class="on">{$article.title}</li>
          </if>
        </foreach>
      </ul>
    </div>
    {$currentArticle.content|htmlspecialchars_decode} </div>
  </div> 
</block>

<block name="jsPage">
<?php 
    $jscssminify->addScript(__PUBLIC2__ . '/js/jquery.nyroModal.custom.js');
    $js = <<<'BLOCK'
(function($){
$(document).ready(function(){
//对资质安全保障中的每个图片加a标签，用于触发nyroModal弹窗
    $.nmProxy(".newer-qualify-img-left img,.newer-qualify-img-right img");
});
})(jQuery);
BLOCK;
    $jscssminify->addScriptDeclaration($js);
?>
</block>