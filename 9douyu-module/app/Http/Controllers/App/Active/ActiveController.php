<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/8/10
 * Time: 上午10:27
 */

namespace App\Http\Controllers\App\Active;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Invite\InviteLogic;
use App\Http\Logics\Micro\MicroJournalLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\InviteModel;

/**
 * 活动
 *
 * Class ActiveController
 * @package App\Http\Controllers\App\Active
 */
class ActiveController extends AppController
{

    /**
     * 逻辑移植自老系统
     * @return mixed
     */
    public function getUrl(){
        $domain             =       env('APP_URL_IOS');
        $appUrl             =       env('APP_URL_WX');
        $faqNew             =       $appUrl . "/app/faq";      //常见问题
//        if( (strtolower($this->client) == 'android' && $this->compareVersion($this->version,'2.0.4') > 0) || (strtolower($this->client) == 'ios' && $this->compareVersion($this->version,'2.0.0') > 0)){
//            $active             =       $domain . "/app/topic/PhoneActive";  //活动中心"
//        }else{
//            $active             =       $domain . "/Activity/PhoneActive";  //活动中心
//        }
//        $intro              =       $domain . "/app/topic/intro";       //一分钟了解九斗鱼
        //月刊读取getConfig
        $newspaper          =       $this->newsPaperInfo();       //九斗鱼月刊

//        $about              =       $domain."/app/topic/about";       //关于

        $url['faqNew']      =       $faqNew;
//        $url['active']      =       $active ;
//        $url['intro']       =       $intro;
        $url['newspaper']   =       $newspaper;
//        $url['about']       =       $about;

        $teamInfo           =       SystemConfigModel::getConfig('TEAM_INFO');
        $url['team_intro']  =       $appUrl . "/app/team";
//        $url['active_share'] =      $this->getShareActive();

        //分享给加好友,返回过去标题、图片url、链接
        $share['title']     =       "吆~吆~九斗鱼，理财收益就是高，安心理财睡好觉！";
        $share['desc']      =       "下载九斗鱼App，快来一起赚钱吧！";
        $share['link']      =       env("WEIXIN_URL")."/zt/appguide.html?from=singlemessage&isappinstalled=1";
        $share['imgUrl']    =       $domain."/resources/weixin/share.png";

        //当前登录用户未读的站内信
        $noticeNum = 0;

//        if($userId = $this->getUserId()) {
//            //未读消息的数量
//            //获取2年内、已发布的未读消息id集合
//            $twoYearsAgo = date("Y-m-d H:i:s", strtotime('-2 year'));
//            $where['publish_time'] = array('egt', $twoYearsAgo);
//            $where['send_to'] = array("in", array(0, $userId));
//            $where['is_read'] = 0;
//            $noticeModel = new NoticeSFModel();
//            $notices = $noticeModel->getNoticeId($where);
//            $noticeReadModel = new NoticeReadSFModel();
//            $noticeId = $noticeReadModel->getNoticeIdByUserId($userId);
//            foreach ($notices as $key => $value) {
//                if (in_array($value['id'], $noticeId)) {
//                    unset($notices[$key]);
//                }
//            }
//            //未读消息数量
//            $noticeNum = count($notices);
//        }

        //返回资讯信息 todo 咨询信息
//        $contentLogic = new ContentSFLogic();
//        $size = self::MAXSIZEID;
//        $category_id = array(18);
//        $MaxArticelinfo = $contentLogic->getMaxArticelid($size,'id,picture_id',$category_id);
//        $picturestr = $MaxArticelinfo[0]['picture_id']==0?"0":C("WEB_URL")."/picture/".$MaxArticelinfo[0]['picture_id'];
//        $MaxArticelimsg = [
//            'min_picture_id'  => $MaxArticelinfo[0]['id'],
//            'max_news_id'     => $picturestr,
//            'app_news_id'     => env("WEIXIN_URL")."/Article/getArticleList",
//        ];
        $MaxArticleMsg = [
            'min_picture_id'  => '',
            'max_news_id'     => '',
            'app_news_id'     => env("APP_URL_WX")."/Article/getArticleList",
        ];

        //此方法需要优化，但是短期内用户定级暂时没出方案，暂定这么烂的方式实现
//        $levelConfig = getConfig('USER_LEVEL_COMMON');
//        $client = strtoupper($this->client);
//        $isShowVip = isset($levelConfig[$client.'_IS_SHOW']) ? $levelConfig[$client.'_IS_SHOW'] : 0;

//        $vip = array();
//        if($userId){
//
//            $userLogic = new UserSFLogic();
//            $userInfo  = $userLogic->getUserByIdReadOnly($userId);
//            $level     = empty($userInfo["vip"]) ? 1 : $userInfo["vip"];
//            $vip = [
//                'url'       => $levelConfig['JUMP_URL'],
//                'level'     => $level,
//            ];
//        }


        $userId = $this->getUserId();

        $phone = '';

        if( $userId ){

            $inviteLogic = new InviteLogic();

            $inviteInfo = $inviteLogic->getInfoByOtherUserId($userId);

            if( !empty($inviteInfo) && isset($inviteInfo['user_id']) ){

                $user = UserModel::getCoreApiUserInfo($inviteInfo['user_id']);

                $phone = isset($user['phone']) ? $user['phone'] : '';

            }

        }

        $items = [
            "url"                   => $url,
            "share"                 => $share,
            "notice_unread_num"     => $noticeNum ,
            "app_artice_msg"        => $MaxArticleMsg,
            'is_show_vip'           => 0,//$isShowVip,
            'partner_phone'         => $phone,
            'tip_list'              => [
                '填写邀请人的手机号，只能填写一次，不可更改。',
                '新用户可在注册之日起14天内填写添加邀请您加入九斗鱼的用户手机号。',
                '过期填写邀请手机号，不能形成绑定关系。'
            ]
//            'vip' => $vip
        ];

        $result = self::callSuccess($items);

        return $this->appReturnJson($result);
    }

    /**
     * 活动分享数据
     * @author liuqiuhui
     * @time   2015-10-15
     */
//    function getShareActive(){
        //App跳出到活动h5分享
//        $ActivityModel  = new ActiveSFModel();
//        $map['show_app']  = ActiveSFDb::SHOW_APP;
//        $map['status']  = self::STATUS_DONE;
//        //$map['order']   = array('sort_num desc');//从大到小
//        $res            = $ActivityModel->getActive($map);
//        if($res){
//            foreach($res as $key => $v){
//                $sign                    = 'activity'.($key+1);
//                if($this->isCheckPartnerShare($v['id'],self::TYPE_PARTNER_SHARE_ACTIVE)){
//                    $shareArr = $this->getParentShare();
//                }else{
//                    $shareArr['share_title'] = $v['share_title'];
//                    $shareArr['share_desc']  = $v['share_desc'];
//                    $shareArr['share_url']   = $v['url'];
//                    $shareArr['share_img']   = '';
//                }
//                $shareArr['url']         = $v['url'];
//                $activeShare["$sign"]      = $shareArr;
//
//            }
//        }
//        return $activeShare;
//    }

    /*
    * 九斗鱼月刊数据
    */
    public function newsPaperInfo()
    {
        //todo 月刊url  表sf_newspaper_config http://wx.9douyu.com/zt/newspaper1602.html
        $defaultLink    =   env('WEIXIN_URL_HTTPS')."/zt/newspaper1609";

        $microLogic     =   new MicroJournalLogic();

        $microInfo      =   $microLogic->getLastMicroByDate();

        if( empty($microInfo) ){

            return $defaultLink;
        }

        return $microInfo['link'];
    }

    /*
     * 活动链接参数
     * 安卓推广版的启动页的的活动数据接口
     */
    public function noviceInfo()
    {
        $client     = $this->getClient();
        $version    = $this->version;
        $lang       = empty(explode("2.2.",$version)[1]) ? 0 : strlen(explode("2.2.",$version)[1]);
        if( $client != 'Android' || $lang ==3){
            $return = array(
                "novice_url"    => "",
            );
            $result = self::callSuccess($return);

            return $this->appReturnJson($result);
        }

        $result = self::callSuccess();

        return $this->appReturnJson($result);

//todo h5
//        $config = getConfig('ACTIVITY_EIGHT_S9');
//        $startTime    = strtotime($config['START_TIME']);
//        $endTime      = strtotime($config['END_TIME']." 23:59:59");
//        if( time() < $startTime || time() >$endTime){
//            $return['items'] = array(
//                "novice_url"    => "",
//            );
//            $this->jsonReturn($return);
//        }
//        $item = [
//            'novice_url'   => $config['SCALE_NAME_URL'] ?$config['SCALE_NAME_URL'] :C('WEIXIN_URL')."/Activity2016/noviceActivityNine",
//        ];
//        $return = array('items'=> $item );
//        $this->jsonReturn($return);
    }

    //资讯分享数据接口 ios跟安卓通用
    public function getNewsShare()
    {
        $result = self::callError('不存在分享的内容');
        return $this->appReturnJson($result);

        //todo 咨询分享数据调用
//        $id = I('id',"");
//        if(empty($id)) {
//            $result = ["status" => self::STATUS_ERROR, "msg" => "未获取到ID值", "items" => "__EMPTYID__"];
//            return $this->jsonReturn($result);
//        }
//        //判断信息不存在
//        $contentLogic = new ContentSFLogic();
//        $newsinfo = $contentLogic->getArticle($id);
//        if( !$newsinfo )
//        {
//            $result = ["status" => self::STATUS_ERROR, "msg" => "不存在分享的内容", "items" => "__EMPTYID__"];
//            return $this->jsonReturn($result);
//        }
//        //返回分享的数据，并进行格式化
//        $imgurl = C("WEB_URL")."/picture/".$newsinfo['picture_id'];
//        if($newsinfo['picture_id']==0 ||empty($newsinfo['picture_id']))
//        {
//            $imgurl =  C("WEIXIN_URL")."/static/images/about_icon.png";
//        }
//        $sharenewinfo = [
//            "share_title"       =>  $newsinfo['title'],
//            "share_desc"        =>  $newsinfo['description']?$newsinfo['description']:$newsinfo['keywords'],
//            "share_url"         =>  C("WEIXIN_URL")."/Article/index/id/".$newsinfo['id'],
//            "share_img"         =>  $imgurl,
//            "purl"              =>  $imgurl
//        ];
//        $result = ["status" => self::STATUS_SUCCESS, "msg" => "请求成功", "items" => array("app_news_share"=>$sharenewinfo)];
//        return $this->jsonReturn($result);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 问答
     */
    public function faq()
    {

        return view('app.topic.faq');

    }

    public function team()
    {

        return view('app.topic.team');

    }

}