<extend name="Public@Template:frontHome" />

<block name="cssPage">
<?php 
$jscssminify->addStyleSheet(Genstatic::statics(__PUBLIC2__ . '/css/style.css'));
?>
</block>

<block name="title">
    <title>{$currentArticle.title} - {:C('TITLE_SUFFIX')}</title>
</block>

<block name="main">
<div class="lefttab">
    <div class="lefttab-left">
        <ul>
            <foreach name="articleList" item="article">
            <if condition="$article['id'] == $currentArticle['id']">
            <li><a href="{:U(sprintf('/article/%s',$article['id']))}" class="on">{$article.title}</a></li>
            <else />
            <li><a href="{:U(sprintf('/article/%s',$article['id']))}">{$article.title}</a></li>
            </if>
            </foreach>
        </ul>
    </div>
    <div class="lefttab-right">
        {$currentArticle.content|htmlspecialchars_decode}
    </div>
    <div class="clear"></div>
    
</div>
</block>

<block name="jsPage">
<?php 
$jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/bootstrap/js/bootstrap.min.js'));
$jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/js/jquery.scrollfix.js'));
$js = <<<'BLOCK'
(function($){
$(document).ready(function(){
    $.scrollFix(".lefttab-right-sidebar", ".lefttab-right-main");
});
})(jQuery);
BLOCK;
$jscssminify->addScriptDeclaration($js);
?>
</block>
