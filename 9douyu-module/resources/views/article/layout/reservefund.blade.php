<extend name="Public@Template:frontHome" />
<block name="main">
<div class="wrap">
    <include file="Content@Common:menu" />
    <div class="lefttab-right fl mt40">
        <div class="content-block fl">
            <h1 class="title">{$currentArticle.title}</h1>
            <div class="hr-line"></div>
            <div class="content"> <!-- Start -->
                <?php //echo $currentArticle['content']; ?>

            </div> <!-- End -->
        </div>
        <div class="clearfix mb40"></div>
    </div>
</div>
</block>