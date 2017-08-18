<?php
if(!function_exists('splitToRows')) {
    /**
     * 按行字数分隔文章内容
     * @param $content 文章内容
     * @param $wordsPerRow 每行字数
     */
    function splitToRows($content, $wordsPerRow = 30) {
        $times = ceil(mb_strlen($content) / $wordsPerRow);
        $rows  = array();
        for($i = 0; $i < $times; $i++) {
            $rowContent = mb_substr($content, $i * $wordsPerRow, $wordsPerRow, "UTF-8");
            if(empty($rowContent)) continue;
            $rows[] = "<p>" . $rowContent . "</p>";
        }
        
        return $rows;
    }
    
    /**
     * 按页行数分隔文章段落
     * @param $rows 文章段落内容数组
     * @param $rowsPerPage 每页的行数
     */
    function splitToPage($rows, $rowsPerPage = 20) {
        $times = ceil(count($rows) / $rowsPerPage);
        
        $html  = '';
        for($i = 0; $i < $times; $i++) {
            $html .= "<li>" . implode(array_slice($rows, $i * $rowsPerPage, $rowsPerPage)) . "</li>";
        }
        
        return $html;
    }
}

//Ajax or page
if(!$isAjax) { //不是Ajax 公告文章模板
    $templateFile = TMPL_PATH . 'Content/Article/notice.html';
    foreach($this->tVar["articleList"] as $key => $article) {
        if($this->tVar["currentArticle"]["id"] === $article["id"]) {
            if(preg_match('#^(.*?)<hr\s*/?>[\r\n]*(.*?)$#is', htmlspecialchars_decode($article["content"]), $match)) {
                $sections = explode("\n", str_replace("\r", "", $match[2]));
                $rows = array();
                foreach($sections as $section) {
                    $rows = array_merge($rows, splitToRows("　　" . ltrim($section), 42));
                }
                
                $content = $match[1] . "<p>" . implode("</p><p>", $rows) . "</p>";
            }
            $this->tVar["currentArticle"]["content"] = $content;    //覆盖成公告模板的内容
            break;
        }
    }
    $content = $this->fetch($templateFile,$this->tVar,$prefix='');
    
    echo $content;
} else { //是Ajax 弹窗文章模板
    $content = htmlspecialchars_decode($currentArticle['content']);
    if(preg_match('#^(.*?)<hr\s*/?>[\r\n]*(.*?)$#is', $content, $match)) {
        $sections = explode("\n", str_replace("\r", "", $match[2]));
        $rows = array();
        foreach($sections as $section) {
            $rows = array_merge($rows, splitToRows("　　" . ltrim($section)));
        }
        
        $rowsContent = splitToPage($rows, 17);
    }
?>
<ul>
    <li>
        {$match[1]}
    </li>
    {$rowsContent}
</ul>
<?php
}
?>
