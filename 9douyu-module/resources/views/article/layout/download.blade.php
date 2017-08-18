<extend name="Public@Template:frontHome" />
<block name="cssStyle">
<?php
$jscssminify->addStyleSheet(Genstatic::statics(__PUBLIC2__ . '/css/download.css'));
?>

</block>
<block name="main">
<div class="download-banner">
    <div class="download-wrap">
        <div class="download-btn-box">
            <a class="download-btn1" href="{$iosUrl}">apple itunes store</a>
            <a class="download-btn2" href="{$androidUrl}">android store</a>
        </div>
    </div>
</div>
<div class="download-wrap">
    <img src="__PUBLIC2__/images/topic/download-img3.png" class="download-img1">
    <img src="__PUBLIC2__/images/topic/download-img2.png" class="download-img2">
</div>



</block>
