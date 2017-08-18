<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:33
 */
namespace App\Http\Dbs\Article;

use App\Http\Dbs\JdyDb;

class CategoryDb extends JdyDb
{

    const

        INTRO                   = 1,    //公司介绍
        HELP                    = 2,    //帮助中心
        SECURITY                = 3,    //安全保障
        FEATURE                 = 4,    //特色专题
        NOTICE                  = 5,    //网站公告
        AGGREEMENT              = 6,    //协议
        REFUND                  = 7,    //还款公告
        RECORDS                 = 7,    //行业资讯
        INDUSTRY                = 8,    //行业资讯
        GUEST                   = 9,    //鱼客私塾
        GREATV                  = 10,   //非著名金融大V专栏
        DREAMPLAN               = 11,   //圆梦计划
        CREDIT_LOAN             = 12,   //企业信用贷攻略
        MORTGAGE                = 13,   //企业抵押贷攻略
        LOAN                    = 14,   //成功贷款有窍门
        MEDIA                   = 15,   //媒体报道
        MONTHLY                 = 16,   //九斗鱼月刊
        OPENDAY                 = 17,   //开放日
        APPARTICLE              = 18,   //app文章
        ADVANCEREFUND           = 19,   //提前还款公告
        CLICK_IN                = 20,   //中国互联网大会
        ANNIVERSARY             = 21,   //周年庆活动
        CLICK_IN2               = 22,   //中国财经峰会
        MONTHLYREPORT           = 23,   //九斗鱼月刊－新
        HELP_TWO                = 24,   //帮助中心（新）
        HOT_QUESTION            = 28,   //热门问题
        ALL_QUESTION            = 29,   //所有问题

        STATUS_PUBLISH_FALSE    = 100, //未发布

        STATUS_PUBLISH_TRUE     = 200, //已发布

        END = TRUE;

    /**
     * @param $id
     * @return array
     * @desc 通过id获取类别
     */
    public function getById( $id ){

        $result = self::find( $id );

        return $this -> dbToArray($result);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 添加类别
     */
    public function add( $data ){

        return self::insert($data);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑类别
     */
    public function edit( $id, $data){

        return self::where('id', $id) -> update($data);

    }

    /**
     * @return mixed
     * @desc 获取取所有类别列表
     */
    public function getAllList(){

        return self::get() -> toArray();

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 分页列表数据
     */
    public function getList( $page, $size ){

        $start = $this->getLimitStart($page, $size);

        $total = $this->count('id');

        $list = $this->orderBy('sort_num', 'desc')
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }


    /**
     * @param $pid
     * @return array
     * @desc 根据父id获取子分类信息
     */
    public function getNameByPid($pid){

        return $this->select('id','parent_id','name')
            ->where('parent_id','=',$pid)
            ->orderBy('sort_num')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

    }
}