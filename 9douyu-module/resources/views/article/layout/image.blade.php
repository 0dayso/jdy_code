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
{$currentArticle.content|htmlspecialchars_decode}
</block>