<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:16
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
use App\Tools\ToolCurl;
use App\Http\Logics\Micro\MicroJournalLogic;
use Cache;

class ZtController extends WeixinController{

    /**
     * app引导安装页面
     */
    public function appGuide(Request $request) {

        $from           = $request->input("channel","");
        $ClineAgent     = strtolower($_SERVER['HTTP_USER_AGENT']);

        //统计APP下载配置
        $config         = SystemConfigModel::getConfig("APP_DOWNLOAD");
        //先判断微信内置浏览器
        if(strpos($ClineAgent, 'micromessenger')){

            $down = $config['APPSTORE_URL'];

        }elseif(strpos($ClineAgent , 'iphone') !== false){

            $down = $config['IOS_IPA'];

        }elseif(strpos($ClineAgent, 'android') !== false){
            //推广活动APP包配置（安卓）
            $apkConfig      = SystemConfigModel::getConfig("PUSH_ACTIVITY_ANDROID_APK");
            if(!empty($apkConfig[$from])){
                $down = env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$apkConfig[$from];
            }else{
                $down = env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$config['ANDROID_APK'];
            }
        }else{
            $errorMsg = '抱歉，暂不支持您的手机系统';
        }

        $viewData['title'] = "九斗鱼APP安装";

        if(isset($down)){
            $viewData['link'] = $down;
        }else{
            $viewData['linkError'] = $errorMsg;
        }

        return view('wap.activity.zt.appGuide', $viewData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 8月份微刊
     */
    public function newspaper1608()
    {
        return view('wap.activity.zt.newspaper1608');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 9月份微刊
     */
    public function newspaper1609()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取9月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_09');

        return view('wap.activity.zt.newspaper1609', $data);
    }

    /**
     *
     * @desc 10月份微刊
     */
    public function newspaper1610()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取10月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_10');

        return view('wap.activity.zt.newspaper1610', $data);
    }

    /**
     *
     * @desc 11月份微刊
     */
    public function newspaper1611()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取11月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_11');

        return view('wap.activity.zt.newspaper1611', $data);
    }

    /**
     *
     * @desc 12月份微刊
     */
    public function newspaper1612()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取11月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_12');

        return view('wap.activity.zt.newspaper1612', $data);
    }

     /**
     *
     * @desc 1月份微刊
     */
    public function newspaper1701()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取1月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_1701');

        return view('wap.activity.zt.newspaper1701', $data);
    }

    /**
     *
     * @desc 2月份微刊
     */
    public function newspaper1702()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        //获取2月微刊配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WEIKAN_1702');

        return view('wap.activity.zt.newspaper1702', $data);
    }


    /**
     *
     * @desc 3月份微刊
     */
    public function newspaper1703()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        $shareImage =   "/static/weixin/paper/images/news1703/100.png";

        $logic      =   new MicroJournalLogic();

        $newsInfo   =   $logic->getLastMicroByDate();

        if( isset($newsInfo['picture_id']) && !empty($newsInfo['picture_id'])){

            $shareImage =   $logic->getPictureById($newsInfo['picture_id']);
        }
        $shareImage =
        //获取3月微刊配置
        $data['shareConfig']  =   [
                'share_title'   =>  isset($newsInfo['title']) && !empty($newsInfo['title']) ? $newsInfo['title'] : '鱼乐微刊',
                'line_link'     =>  isset($newsInfo['link']) && !empty($newsInfo['link']) ? $newsInfo['link'] : env('WEIXIN_URL_HTTPS')."/zt/newspaper1703",
                'img_url'       =>  env('STATIC_URL_HTTPS').$shareImage,
                'desc_content'  =>  isset($newsInfo['content']) && !empty($newsInfo['content']) ? $newsInfo['content'] : '鱼乐微刊' ,
            ];

        return view('wap.activity.zt.newspaper1703', $data);
    }



    /**
     *
     * @desc 6月份微刊
     */
    public function newspaper1706()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        $shareImage =   "/static/weixin/paper/images/news1706/100.png";

        $logic      =   new MicroJournalLogic();

        $newsInfo   =   $logic->getLastMicroByDate();

        if( isset($newsInfo['picture_id']) && !empty($newsInfo['picture_id'])){

            $shareImage =   $logic->getPictureById($newsInfo['picture_id']);
        }
        $shareImage =
        //获取6月微刊配置
        $data['shareConfig']  =   [
                'share_title'   =>  isset($newsInfo['title']) && !empty($newsInfo['title']) ? $newsInfo['title'] : '内刊6月期',
                'line_link'     =>  isset($newsInfo['link']) && !empty($newsInfo['link']) ? $newsInfo['link'] : env('WEIXIN_URL_HTTPS')."/zt/newspaper1706",
                'img_url'       =>  env('STATIC_URL_HTTPS').$shareImage,
                'desc_content'  =>  isset($newsInfo['content']) && !empty($newsInfo['content']) ? $newsInfo['content'] : '内刊6月期' ,
            ];

        return view('wap.activity.zt.newspaper1706', $data);
    }


    /**
     *
     * @desc 7月份微刊
     */
    public function newspaper1707()
    {

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        $shareImage =   "/static/weixin/paper/images/news1707/100.png";

        $logic      =   new MicroJournalLogic();

        $newsInfo   =   $logic->getLastMicroByDate();

        if( isset($newsInfo['picture_id']) && !empty($newsInfo['picture_id'])){

            $shareImage =   $logic->getPictureById($newsInfo['picture_id']);
        }
        $shareImage =
        //获取7月微刊配置
        $data['shareConfig']  =   [
                'share_title'   =>  isset($newsInfo['title']) && !empty($newsInfo['title']) ? $newsInfo['title'] : '内刊7月期',
                'line_link'     =>  isset($newsInfo['link']) && !empty($newsInfo['link']) ? $newsInfo['link'] : env('WEIXIN_URL_HTTPS')."/zt/newspaper1707",
                'img_url'       =>  env('STATIC_URL_HTTPS').$shareImage,
                'desc_content'  =>  isset($newsInfo['content']) && !empty($newsInfo['content']) ? $newsInfo['content'] : '内刊7月期' ,
            ];

        return view('wap.activity.zt.newspaper1707', $data);
    }





    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 常见问题
     */
    public function faq()
    {

        return view('app.topic.faq');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 开发团队
     */
    public function team()
    {

        return view('app.topic.team');

    }
}
