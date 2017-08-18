<extend name="Public@Template:frontHome" />
<block name="main">
<div class="wrap">
    <include file="Content@Common:menu" />
    <div class="lefttab-right fl mt40">
        <div class="content-block fl">
            <div class="content"> <!-- Start -->
                <?php echo $currentArticle['content']; ?>
            </div> <!-- End -->
        </div>
        <div class="clearfix mb40"></div>
    </div>
</div>
</block>

<block name="jsScript">
<?php if($currentArticle['id'] == 1 || $currentArticle['id'] == 125) { //公司简介//公司环境 
$jscssminify->addStyleSheet(Genstatic::statics(__PUBLIC2__ . '/css/nyroModal.css'));
$jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/js/jquery.nyroModal.custom.js'));
$js = <<<'BLOCK'
(function($) {
    $(function(){
        $.nmProxy(".aptitude img");
    });
})(jQuery);
BLOCK;
$jscssminify->addScriptDeclaration($js);
}

if($currentArticle['id'] == 2) { //加入我们
$js = <<<'BLOCK'
(function($) {
    $(function(){
        $(".top-tab-slide dt").each(function(){
            $(this).click(function(){
                if($(this).hasClass("on")) {
                    $(this).removeClass("on").next("dd").hide();
                } else {
                    $(this).addClass("on").next("dd").show();
                    $(this).parent("dl").siblings().find("dd").hide();
                }
            });
        });
    });
})(jQuery);
BLOCK;
$jscssminify->addScriptDeclaration($js);
} ?>

<?php if($currentArticle['id'] == 6) { //联系我们 ?>
<include file="Public@Module:baiduMap" />
<?php } ?>
</block>