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
{$currentArticle.content|htmlspecialchars_decode}
</block>

<block name="jsPage">
<?php
$jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/js/jquery.nyroModal.custom.js'));
$js = <<<'BLOCK'
(function($){
$(document).ready(function(){
//项目详情解释
$(".newer-item").each(function(){
$(this).hover(function(){
$(this).find(".newer-item-txt").show();
},function(){
$(this).find(".newer-item-txt").hide();
});
});
    //对文章中的每个图片加a标签，用于触发nyroModal弹窗
    $.nmProxy(".newer-qualify-small img");
});
})(jQuery)
BLOCK;
$jscssminify->addScriptDeclaration($js);
?>
</block>
</block>