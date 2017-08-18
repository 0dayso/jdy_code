<?php
    if(empty($mediaItemId) && empty($mediaArticle)) {
        return false;
    } else if(empty($mediaArticle)) {  //没有自定制文章，通过id获取
        $mediaItemIds = (array)$mediaItemId;
        $mediaArticle = array();
        foreach($mediaItemIds as $mediaItemId) {
            $mediaArticle[] = getArticle($mediaItemId);
        }
    }

    $firstItem = null;
    foreach((array)$mediaArticle as $article) {
        if(isset($article['id']) && empty($article['link'])) {
            $article['link'] = U(sprintf('/article/%s',$article['id']));
        }
?>
<p>&nbsp;</p>
<?php if(!(!isset($firstItem) && ($firstItem = true))) { ?>
<div class="hr-line"></div>
<p>&nbsp;</p>
<?php } ?>
<div class="item fl">
    <div class="img fl">
    <img width="148" src="{$article['purl'][$view_ssl]}" alt="{$article.title}" />
    </div>
    <div class="item-content">
        <h2><a href="{$article.link}" target="_blank">{$article.title}</a></h2>
        <div class="item-descrition">{$article.intro|htmlspecialchars_decode|stripslashes|strip_tags|msubstr=###,0,100,'utf-8'}</div>
        <div class="item-time">{$article.publish_time|}</div>
    </div>
</div>
<?php
    }
?>
