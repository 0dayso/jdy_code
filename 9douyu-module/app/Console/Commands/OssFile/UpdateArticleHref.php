<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/1/17
 * Time: 下午1:31
 */
namespace App\Console\Commands\OssFile;

use App\Http\Dbs\Article\ArticleDb;
use App\Http\Models\Picture\PictureModel;
use Illuminate\Console\Command;

class UpdateArticleHref extends Command
{

    //计划任务唯一标识
    protected $signature   = 'UpdateArticleHref';

    //计划任务描述
    protected $description = '修改文章表content字段中图片超链接的/picture/id部分为新地址';

    public function handle(){


        $db = new ArticleDb();
        $picModel = new PictureModel();
        $contentList = $db->getContent();

        foreach($contentList as $k=>$val){
            $content = $val['content'];
            $pattern = "/href=&quot;http:\\/\\/www.9douyu.com\\/picture\\/(\\d+)(\\.jpg|\\.png)?&quot;/";
            if(preg_match_all($pattern,$content,$mt)){
                foreach($mt[0] as $key1 => $value1){
                    $origin = $value1;
                    $id = $mt[1][$key1];
                    $result = $picModel->getById($id);
                    if($result){
                        $new_str = 'src=&quot;'.assetUrlByCdn('resources/'.$result['path']).'&quot;';
                    }else{
                        $new_str = 'src=&quot;'.assetUrlByCdn('resources/').'&quot;';
                    }
                    $content = str_replace($origin,$new_str,$content);
                }
            }

            $pattern = "/href=&quot;\\/picture\\/(\\d+)(\\.jpg|\\.png)?&quot;/";
            if(preg_match_all($pattern,$content,$mt)){
                foreach($mt[0] as $key2 => $value2){
                    $origin = $value2;
                    $id = $mt[1][$key2];
                    $result = $picModel->getById($id);
                    if($result){
                        $new_str = 'src=&quot;'.assetUrlByCdn('resources/'.$result['path']).'&quot;';
                    }else{
                        $new_str = 'src=&quot;'.assetUrlByCdn('resources/').'&quot;';
                    }
                    $content = str_replace($origin,$new_str,$content);
                }
            }

            $data = [
                'content' => $content
            ];
            $result = $db->edit($val['id'],$data);
            if($result){
                echo $val['id']."文章修改成功!\n";
            }else{
                echo $val['id']."修改失败!\n";
            }
        }

    }

}