<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:52
 * Desc: 广告位
 */

namespace App\Http\Dbs\Ad;

use App\Http\Dbs\JdyDb;

class AdPositionDb extends JdyDb
{

    protected $table = "ad_position";

    const   TYPE_PC                 = 1,
            TYPE_WAP                = 2,
            TYPE_APP                = 3,

            P_WEB_BANNER            =   1,  //PC首页滚动banner
            P_WEB_INVEST            =   2,  //PC理财产品-我要投资页
            P_WEB_PAGE              =   3,  //PC首页广告位
            P_START_PAGE            =   4,  //APP启动页
            P_LEAD_PAGE             =   5,  //App引导页
            P_REGISTERED            =   6,  //App注册成功页
            P_TRAD_PASS_SET         =   7,  //App交易密码设置成功页
            P_REG_RESERVATION       =   8,  //App注册预留页
            P_BOUTIQUE              =   9,  //App精品推荐页(上)
            P_BOUTIQUE_BANNER       =  10,  //App精品推荐页banner(下)
            P_INVEST_SUCCESS        =  11,  //App投资成功页(上),改名：APP-投资成功页（图片
            P_INVEST_BELOW_SUCCESS  =  12,  //App投资成功页(下)
            P_PRODUCT_LIST          =  13,  //App理财产品滚动页
            P_PRODUCT_DETAIL        =  14,  //App产品简介
            P_MYASSETS1             =  15,  //App我的资产1
            P_MYASSETS2             =  16,  //App我的资产2
            P_ENVELOPE              =  17,  //App我的红包
            P_WEB_REGIST            =  18,  //PC注册页面
            P_WEB_INVEST_SUCCESS    =  19,  //PC投资成功页
            P_REAL_NAME_OK          =  20,  //App实名认证成功页按钮广告
            P_LOGIN                 =  21,  //APP登录活注册页文字广告
            P_REAL_NAME             =  22,  //App实名认证页文字广告
            P_WAP_INVEST_SUCCESS    =  23,  //WAP投资成功页面
            P_WAP_RECHARGE_SUCCESS  =  24,  //WAP充值成功页面
            P_WAP_INVESTOUT_SUCCESS =  25,  //WAP转出成功页面
            P_WAP_MYBONUS           =  26,  //WAP我的优惠券页面
            P_REAL_NAME_NO          =  27,  //App取消实名认证返回页(推广版用)
            P_REGISTERED_BTN        =  28,  //App注册成功页按钮广告
            P_PRODUCT_DETAIL_INFO   =  29,  //App-产品详情页（按产品类型发布活动）
            P_INDEX_PLAY            =  30,  //App-首页轮播
            P_INDEX_DOWN            =  31,  //App-首页下拉介绍
            P_ASSIGNED_SUCCESS      =  32,  //App-债权转让成功页banner
            P_INDEX_POP             =  33,  //App-首页弹出广告
            P_USER_CENTER_ADS       =  34,  //App-我的资产,邀请好友
            P_CURRENT               =  35,  //App-零钱计划广告
            P_WEB_POP               =  36,  //PC首页弹屏广告
            P_WEB_LOGIN_LEFT        =  37,  //PC首页登录页左侧
            P_WEB_INVEST_RIGHT      =  38,  //PC我要投资页右侧
            P_WEB_LOGO              =  39,  //PC网站的logo图标
            P_WAP_INDEX             =  40,  //WAP站首页广告
            P_WEB_INDEX_LEFT_BOTTOM =  50,  //首页左下角
            P_INDEX_TEST            =  99,  //APP-测试banner

            STATUS_ERROR            =  4000,//错误码
            STATUS_DONE             =  200, //广告位图片已发布
            TYPE_URL                =  1,   //跳转url
            TYPE_MODULE             =  2,   //跳转app制定模块
            TYPE_NONE               =  3,   //不跳转

            //配置跳转类型的常量
            JUMP_TYPE_DEFAULT       =  0,   //默认0
            JUMP_TYPE_H5            =  1,   //H5链接
            JUMP_TYPE_INDEX         =  2,   //App首页
            JUMP_TYPE_MONEY         =  3,   //App理财页
            JUMP_TYPE_MY_MONEY      =  4,   //App我的资产
            JUMP_TYPE_CURRENT       =  5,   //零钱计划
            JUMP_TYPE_ONE           =  6,   //九省心1个月
            JUMP_TYPE_THREE         =  7,   //九省心3个月
            JUMP_TYPE_SIX           =  8,   //九省心6个月
            JUMP_TYPE_TWELVE        =  9,   //九省心12个月
            JUMP_TYPE_SAFE          =  10,  //九安心
            JUMP_TYPE_ASSIGN        =  11,  //债权转让

            //广告类型
            TYPE_ID_PC              = 1,   //pc广告
            TYPE_ID_APP_I           = 2,   //APP2.0广告
            TYPE_ID_WAP             = 3,   //wap端广告
            TYPE_ID_APP_II          = 4,   //app2.1及以上版本广告

            END = FALSE;


    /**
     * @param $data
     * @return bool
     * @desc 添加广告位
     */
    public function addInfo($data)
    {

        $this->name = $data['name'];

        $this->type = $data['type'];

        $this->param = $data['param'];

        return $this->save();

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新广告位
     */
    public function editById($id, $data)
    {

        return $this->where('id', $id)
            ->update(
                [
                    'name' => $data['name'],
                    'type' => $data['type']
                ]
            );

    }

    /**
     * @param $type
     * @return mixed
     * @desc 获取列表
     */
    public function getListByType($type)
    {

        return $this->where('type', $type)
            ->get()
            ->toArray();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除广告位
     */
    public function delPosition($id){

        return $this->where('id', $id)
            ->delete();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取信息
     */
    public function getInfoById($id)
    {

        return $this->where('id', $id)->first();

    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     * 编辑广告位信息
     */
    public function editPosition($id,$data){

        return $this->where('id',$id)
            ->update($data);
    }


}