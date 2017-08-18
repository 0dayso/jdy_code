<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/24
 * Time: 下午2:12
 */

namespace App\Http\Controllers;

use App, URL;
/**
 * https://support.google.com/webmasters/answer/183668?hl=en
 * http://jingyan.baidu.com/album/9158e0006d5dd9a2541228e9.html
 * 网站地图
 *
 * Class SiteMapController
 * @package App\Http\Controllers
 */
class SiteMapController extends LaravelController
{

    /**
     * 重写 robots
     */
    public function robots(){
        $request          = app('request');
        $domainName       = $request->getHttpHost();

        $robotsTxt  = "User-agent: *\r\n";
        $robotsTxt .= "Disallow: /admin/\r\n";
        $robotsTxt .= "Disallow: /login\r\n";
        $robotsTxt .= "User-agent: 360Spider\r\n";
        $robotsTxt .= "Disallow: /\r\n";

        try {
            $file = public_path() . '/base_robots.txt';
            if(file_exists($file))
                $robotsTxt = file_get_contents(public_path() . '/base_robots.txt');
        }catch (\Exception $e){
            \Log::info(__METHOD__, $e->getMessage());
        }
        header("Content-type: text/plain");

        echo $robotsTxt;
        //pc浏览器 site-map
        if($domainName == env('APP_DOMAIN_PC')){
            $keys = array_keys(self::getPcSiteMapData('web'));
            foreach($keys as $key){
                echo "Sitemap:http://" . $domainName . "/sitemap/web/" . $key . ".xml\r\n";
            }
        }elseif($domainName == env('APP_DOMAIN_WX')){
            //手机浏览器 site-map
            //echo "Sitemap: http://" . $domainName . "/sitemap/wechat";
        }
        exit();
    }
    /**
     * 获取 pc端 网站地图数据
     */
    private static function getPcSiteMapData($mapKey = null){
        $data = [
            'web' => [
                //首页
                'index'     => [
                    ['url' => URL::to('/'),                            'modify'=>date('Y-m-d H:i:s'), 'priority'=> '1.0', 'freq' => 'Hourly'],  // 首页链接
                    ['url' => URL::to('/project/current/detail'),      'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 活期
                    ['url' => URL::to('/project/index'),               'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 定期项目列表
                    ['url' => URL::to('/project/index?type=JAX'),      'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 九安心项目列表
                    ['url' => URL::to('/project/sdf'),                 'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 闪电付息项目列表
                    ['url' => URL::to('/about/insurance'),             'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 安全保障
                    ['url' => URL::to('/content/article/newentrance'), 'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 新手指引
                    ['url' => URL::to('/app_guide'),                   'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 客户端下载引导页面
                    ['url' => URL::to('/help/890'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                    ['url' => URL::to('/help/891'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                    ['url' => URL::to('/help/892'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                    ['url' => URL::to('/help/893'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                    ['url' => URL::to('/help/894'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                    ['url' => URL::to('/help/895'),                    'modify'=>date('Y-m-d H:i:s'), 'priority'=> '0.9', 'freq' => 'Hourly'],  // 帮助中心
                ],
                // 文章
                'article'   => [
//                    ['url' => URL::to('/help/895'),  'modify'=>date('Y-m-d H:i:s'), 'priority'=> '1.0', 'freq' => 'Hourly', 'googleNews' => [
//                        'sitename'         => '九斗鱼',
//                        'language'         => 'zh',
//                        'publication_date' => '发布时间',
//                        'access'           => '访问量',
//                        'keywords'         => ['关键字'],
//                    ]],
                ],
            ],
            'wechat' => [
                //首页
                'index'     => [
                    ['url' => URL::to('/'),                            'modify'=>date('Y-m-d H:i:s'), 'priority'=> '1.0', 'freq' => 'Hourly'],  // 首页链接
                ],
            ],
        ];
        $articleRecords = self::getArticle();
        $articleData = [];
        if(!empty($articleRecords)){
            foreach($articleRecords as $key => $articleRecord) {
                $keywords = [];
                if(!empty($articleRecord['keywords'])){
                    $keywords =  explode(' ', $articleRecord['keywords']);
                }

                $publishTime = ($articleRecord['publish_time'] == '0000-00-00 00:00:00') ? date('Y-m-d H:i', strtotime('-5 days')) : $articleRecord['publish_time'];

                $articleData[] = ['url' => URL::to('/article/'. $articleRecord['id']), 'modify' => date('Y-m-d H:i:s'), 'priority' => '1.0', 'freq' => 'Hourly',
                    'title'=> $articleRecord['title'],
                    'googleNews' => [
                        'sitename' => '九斗鱼',
                        'language' => 'zh',
                        'publication_date' => $publishTime,
                        'access' => $articleRecord['hits'],
                        'keywords' => $keywords,
                    ]
                ];
            }
        }
        $data['web']['article'] = $articleData;

        if(empty($mapKey))
            return $data;

        return isset($data[$mapKey]) ? $data[$mapKey] : $data;
    }

    /**
     * 获取文字
     */
    private static function getArticle(){
        $article        = new App\Http\Models\Article\ArticleModel;
        $articleRecords = $article->getAllPublishList();
        if(!empty($articleRecords)){
            return $articleRecords;
        }
        return [];
    }

    /**
     * pc 主页
     */
    public function getChildSiteMap($mapKey = null, $mapChildKey = null){
        $sitemap     = App::make("sitemap");

        $siteMapData = self::getPcSiteMapData($mapKey);
        // 链接、修改时间、优先级、频率
        if(!empty($siteMapData)) {
            foreach ($siteMapData as $k => $mapData) {
                if($mapChildKey != $k){
                    continue;
                }
                switch($k){
                    case 'article':
                        $templete    = 'google-news';
                        foreach($mapData as $mapDataChild) {
                            $sitemap->add($mapDataChild['url'], $mapDataChild['modify'], $mapDataChild['priority'], $mapDataChild['freq'], [], $mapDataChild['title'], [], [], $mapDataChild['googleNews']);
                        }
                        break;
                    default:
                        $templete    = 'xml';
                        foreach($mapData as $mapDataChild) {
                            $sitemap->add($mapDataChild['url'], $mapDataChild['modify'], $mapDataChild['priority'], $mapDataChild['freq']);
                        }
                }

            }
        }else{
            return null;
        }
        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render($templete);
    }

}