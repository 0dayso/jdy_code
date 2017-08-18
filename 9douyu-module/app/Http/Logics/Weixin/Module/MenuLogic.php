<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/27
 * Time: 上午11:38
 */

namespace App\Http\Logics\Weixin\Module;


/**
 * 微信菜单
 *
 * Class MenuLogic
 * @package App\Http\Logics\Weixin\Module
 */
class MenuLogic
{

    protected $wapUrl = '';
    protected $menu   = null;


    public function __construct(){
        $wechat        = app('wechat');
        $this->menu    = $wechat->menu;
        $this->wapUrl  = env('APP_URL_WX');
    }
    /**
     * todo 下方链接
     * 获取自定菜单
     */
    private function _getMenuArr() {
        $menuArr = array();
        $menuArr[] = array('type'=>'view',  'name'=>'我要出借',  'url'=>"{$this->wapUrl}");
        $menuArr[] = array('type'=>'view',  'name'=>'下载app',  'url'=>"http://a.app.qq.com/o/simple.jsp?pkgname=com.sunfund.jiudouyu");

//        $menuArr[] = array('name'=>'优惠活动', 'sub_button'=>array(
//            array('type'=>'view', 'name'=>'合伙人计划', 'url'=>"{$this->wapUrl}/activity/y2015partner?from=wap"),
//            array('type'=>'view', 'name'=>'闪电付息',   'url'=>"{$this->wapUrl}/project/lists"),
//            array('type'=>'view', 'name'=>'消费返利',   'url'=>"{$this->wapUrl}"),
//        ));

        $menuArr[] = array('name'=>'服务大厅', 'sub_button'=>array(
            array('type'=>'click', 'name'=>'绑定/解绑', 'key'=>'BIND_REGISTER_USER'),
//            array('type'=>'view', 'name'=>'下载九斗鱼app', 'url'=>"http://a.app.qq.com/o/simple.jsp?pkgname=com.sunfund.jiudouyu"),
            array('type'=>'click', 'name'=>'联系我们', 'key'=>'CONTACT_US'),
//            array('type'=>'view', 'name'=>'我的简历', 'url'=>"http://mp.weixin.qq.com/s?__biz=MzA3NDI3OTMxOQ==&mid=2697900583&idx=1&sn=c6375f1c49989deaa333717598a09893&chksm=ba3822df8d4fabc9ca3240b4779957dff379aa4c2884a6e032463e9d14d6b51d05aeef138488#rd"),
        ));


        return $menuArr;
    }

    /**
     * 创建菜单
     */
    public function add(){
        return $this->menu->add($this->_getMenuArr());
    }

    /**
     * 删除菜单
     */
    public function destroy(){
        $this->menu->destroy(); // 全部
    }
}