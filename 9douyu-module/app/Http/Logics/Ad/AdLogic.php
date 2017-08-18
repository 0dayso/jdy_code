<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:45
 * Desc: 广告管理
 */

namespace App\Http\Logics\Ad;

use App\Http\Dbs\Ad\AdDb;
use App\Http\Dbs\Ad\AdPositionDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\Ad\AdModel;
use App\Http\Logics\Logic;
use App\Tools\ToolArray;
use App\Tools\ToolEnv;

class AdLogic extends Logic
{

    CONST AD_CACHE_KEY = 'ADC_PRE_V5_';

    /**
     * @param $type
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 获取广告位列表信息
     */
    public function getPositionList($type)
    {

        $db = new AdPositionDb();

        $list = $db->getListByType($type);

        $positionIds = ToolArray::arrayToIds($list);

        $adDb = new AdDb();

        $positionCountArr = $adDb->getNumByPositionIds($positionIds);

        $positionCount = ToolArray::arrayToKey($positionCountArr, 'position_id');

        foreach($list as $key => $info) {

            if( isset($positionCount[$info['id']]) ){

                $list[$key]['ad_num'] = $positionCount[$info['id']]['total'];

            }else{

                $list[$key]['ad_num'] = 0;

            }

            $param = json_decode($info['param'], true);

            if($param){
                $img = $param['path'].$param['name'];
            }else{
                $img = '';
            }

            $list[$key]['show_img'] = $img;



        }

        return $list;

    }

    /**
     * @param $positionId
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 根据广告位id获取广告列表信息
     */
    public function getListByPositionId($positionId, $page=1, $size=50)
    {

        $db = new AdDb();

        $list = $db->getListByPositionId($positionId, $page, $size);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $info ){

                $list['list'][$key]['param'] = json_decode($info['param'], true);

            }

        }

        return $list;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取广告位信息
     */
    public function getPositionInfoById($id)
    {

        $positionDb = new AdPositionDb();

        return $positionDb->getInfoById($id);

    }

    /**
     * @param $positionId
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 根据广告位id获取可用的广告列表
     */
    public static function getUseAbleListByPositionId($positionId)
    {

        $data = self::getAdCacheByPositionId($positionId);

        if( !empty($data) ){

            return $data;

        }

        $db = new AdDb();

        $res = $db->getUseAbleListByPositionId($positionId);

        if( !empty($res) ){

            foreach ($res as $key => $val){

                $param = json_decode($val['param'], true);

                $path = isset($param['path']) ? $param['path'] : '';

                $name = isset($param['name']) ? $param['name'] : '';

                $param['file'] = assetUrlByCdn($path.$name);

                $res[$key]['param'] = $param;

                $res[$key]['purl']  = $param['file'];

                $res[$key]['name']  = $res[$key]['title'];

                $res[$key]['type']  = 1;

                $res[$key]['url']   = $param['url'];

                $res[$key]['share_title']   = !empty($param['share_title']) ? $param['share_title'] : '';
                $res[$key]['share_img']     = (!empty($param['share_image_name'])&&!empty($param['share_image_path'])) ? assetUrlByCdn($param['share_image_path'].$param['share_image_name']) : '';
                $res[$key]['share_desc']    = !empty($param['share_desc']) ? $param['share_desc'] : '';
                $res[$key]['share_url']     = !empty($param['share_url']) ? $param['share_url'] : '';

            }

            self::setAdCacheByPositionId($positionId, $res);

        }

        return $res;

    }

    /**
     * @param $positionId
     * @param array $data
     * @return bool
     * @desc 设置缓存
     */
    private static function setAdCacheByPositionId($positionId, $data=[]){

        $cacheKey = self::AD_CACHE_KEY.$positionId;

        if( !empty($data) && is_array($data) ){

            \Cache::put($cacheKey, json_encode($data), 30);

        }

        return true;

    }

    /**
     * @param $positionId
     * @return bool|mixed
     * @desc 清除缓存
     */
    private static function getAdCacheByPositionId($positionId){

        $cacheKey = self::AD_CACHE_KEY.$positionId;

        $jsonData = \Cache::get($cacheKey);

        if( !empty($jsonData) && json_decode($jsonData, true) ){

            return json_decode($jsonData, true);

        }

        return false;

    }

    /**
     * @param $positionId
     * @return mixed
     * @desc 清除缓存
     */
    public static function forgetAdCacheByPositionId($positionId){

        $cacheKey = self::AD_CACHE_KEY.$positionId;

        return \Cache::forget($cacheKey);

    }


    /**
     * @param $data
     * @return bool
     * @desc 添加广告位
     */
    public function addPosition($data)
    {

        $db = new AdPositionDb();

        return $db->addInfo($data);

    }

    public function doEditPosition($id,$data){

        $db = new AdPositionDb();
        return $db->editPosition($id,$data);
    }

    /**
     * @param $id
     * @return array
     * @desc 删除广告位
     */
    public function delPosition($id)
    {

        $adDb = new AdDb();

        $result = $adDb->getNumByPositionIds([$id]);

        if( $result ){

            return self::callError('请先移除该广告位下的广告');

        }

        $positionDb = new AdPositionDb();

        $res = $positionDb->delPosition($id);

        if( !$res ){

            return self::callError('删除失败');

        }else{

            return self::callSuccess();

        }

    }

    /**
     * @param $data
     * @return array
     * @desc 添加广告
     */
    public function addAd($data)
    {

        $db = new AdDb();

        return $db->addInfo($data);

    }

    public function editAd($id){

        $db = new AdDb();
        $ad = $db->getById($id);
        $ad['url_type'] = AdModel::getUrlType();
        return $ad;
    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除广告
     */
    public function delAd($id)
    {

        $db = new AdDb();

        return $db->delInfo($id);

    }

    /**
     * @param $positionId
     * @param $limit
     * @return mixed
     * @desc 获取要显示的广告列表
     */
    public function getAdsByPositionId($positionId, $limit){

        $db = new AdDb();

        $list = $db->getAdsByPositionId($positionId, $limit);

        if( !empty($list) ){

            foreach( $list as $key => $info ){

                $list[$key]['param'] = json_decode($info['param'], true);

            }

        }

        return self::callSuccess($list);

    }

    /**
     * @param $positionId
     * @param $limit
     * @return array
     * @desc app广告数据接口
     */
    public function getAppAdsByPositionId($positionId, $limit){

        $list = self::getUseAbleListByPositionId($positionId);

        //$res = $this -> getAdsByPositionId($positionId, $limit);

        //$list = $res['data'];

        if(empty($list)){

            return self::callSuccess([[]]);

        }

        return self::callSuccess($list);

    }

    /**
     * @return array
     * @desc 请求活动是否弹出显示
     */
    public function getMaxAdSid(){

        $list = self::getUseAbleListByPositionId(21);

        $return = empty($list) ? ['max_ads_id' => 0] : ['max_ads_id' => max(ToolArray::arrayToIds($list))];

        return self::callSuccess(['items' => $return, 'item' => $return]);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * 编辑广告
     */
    public function doEditAd($id,$data){


        $db = new AdDb();

        return $db->editById($id,$data);
    }

    /**
     * @desc app4.0首页可用的banner列表格式化
     * @param $data array
     * @param $isDown 是否底部广告
     * @return $result array
     */
    public static function formatAppV4AdData($data = [], $isDown = false, $userId = 0){
        if (empty($data)) {

            return [];

        }

        $result = [];

        foreach ($data as $key => $value) {

            $param = $value['param'];


            if ($isDown) {

                $param['word'] = explode('|', $param['word']);

            }

            //为了添加邀请人ID,所以固定广告ID
            if($value['id'] == 97){
                $param['share_url'] = !empty($param['share_url']) ? $param['share_url'] : '';
                if($userId != 0 && !empty($param['share_url'])){
                   $param['share_url']= $param['share_url'].'?inviteId='.$userId;
                }
            }

            $result[] = [
                'id'    => $value['id'],
                'word'  => $param['word'],
                'url'   => $param['url'],
                'file'  => $param['file'],
                'sort'  => $value['sort'],
                'title' => $value['title'],
                'shareInfo' => [
                    'share_title' => empty($param['share_title']) ? $value['title'] : $param['share_title'],
                    'share_desc'  => empty($param['share_desc']) ? '' : $param['share_desc'],
                    'share_url'   => empty($param['share_url']) ? $param['url'] : $param['share_url'],
                    'share_image' => empty($value['share_img']) ? $param['file'] : $value['share_img'],
                ],
            ];
        }


        return $result;
    }

    /**
     * @desc  App4.0用户中心－用户资产按钮列表
     * @author linguanghui
     * @param $data array
     * $return $result
     */
    public static function formatAppV4UserInfoButton($data,$userId=0){

        if(empty($data)){
            return [];
        }

        $result = [];

        foreach($data as $key=>$value){
            $result[$key]['name'] = $value['title'];
            $result[$key]['position_num'] = $value['sort'];
//            $result[$key]['group'] = self::setUserButtonGroup($value['sort']);
            $result[$key]['group'] = $value['group_sort'];
            $result[$key]['image_url'] = $value['param']['file'];
            $result[$key]['word'] = $value['param']['word'];
            //跳转H5链接信息处理
            if($value['sort']<=20 && $value['sort']>10){
                $result[$key]['url'] = $value['url'];
                $result[$key]['share'] = [
                    'share_title'  => empty($value['share_title'])? $value['name'] : $value['share_title'],
                    'share_img'    => $value['share_img'],
                    'share_url'    => empty($value['share_url']) ? $value['url'] : $value['share_url'],
                    'share_desc'   => empty($value['share_desc']) ? '' : $value['share_desc'],
                ];

                if($value['id'] == 180){
                    $shareUrl = !empty($value['share_url']) ? $value['share_url'] : '';
                    if($userId != 0 && !empty($shareUrl)){
                        $shareUrl = $value['share_url'].'?inviteId='.$userId;
                    }
                    $result[$key]['share']['share_url'] =  empty($shareUrl)? $value['url'] : $shareUrl;
                }

            }
        }
        return $result;
    }

    /**
     * @desc  设置用户中心按钮分组
     * @author linguanghui
     * @param $type int
     * @return mixed
     */
    public static function setUserButtonGroup($type){

        $group = 1;
        switch($type){

        case $type <=10:
            $group = 1;
            break;

        case $type<=20:
            $group = 2;
            break;
        case $type<=30:
            $group = 3;
            break;
        }
        return $group;
    }

    /**
     * @param $data
     * @return array
     * 格式化
     */
    public static function formatAdData( $data ){

        $ad = [];

        if(!empty($data)){

            $ad = [
                'id'    => $data['id'],
                'word'  => $data['param']['word'],
                'url'   => $data['param']['url'],
                'file'  => $data['param']['file'],
                'title' => $data['title'],
                'shareInfo' => [
                    'share_title'  => empty($data['share_title'])? $data['name'] : $data['share_title'],
                    'share_img'    => $data['share_img'],
                    'share_url'    => empty($data['share_url']) ? $data['url'] : $data['share_url'],
                    'share_desc'   => empty($data['share_desc']) ? '' : $data['share_desc'],
                    'img_url'      => $data['share_img'],
                ]
            ];

        }

        return $ad;
    }

    /**
     * @param $data
     * @return array
     * 格式化
     */
    public static function formatV41AdData( $data ){

        $ad = [];
        $type = AdModel::type();
        if(!empty($data)){
            $ad = [
                'id'    => $data['id'],
                'word'  => !empty($data['param']['word']) ? $data['param']['word'] : '',
                'file'  => !empty($data['param']['file']) ? $data['param']['file'] : '',
                'url'   => !empty($data['param']['url'])  ? $data['param']['url'] : '',
                'jumpType' => !empty($data['param']['jump_to_type']) ? $type[$data['param']['jump_to_type']] : $type[AdDb::JUMP_TO_URL],
            ];

        }

        return $ad;
    }

    /**
     * @param $id
     * @return array
     * @desc 获取推荐的活动信息
     */
    public static function getUseAbleListByAdId( $id )
    {
        if( empty($id) ) {

            return [];
        }

        $adDb   =   new AdDb() ;

        return $adDb->getUseAbleListByAdId( $id );
    }

    /**
     * @desc    根据广告位id获取可用的广告列表
     * @param   $positionId
     * @return  mixed
     *
     * @author  @linglu
     *
     */
    public static function getAppUserInfoButton($positionId ,$userId)
    {

        $db     = new AdDb();
        $res    = $db->getUseAbleListByPositionId($positionId);
        #var_dump($res);
        $result = [];
        if( !empty($res) ){
            foreach ($res as $key => $val){

                $param  = json_decode($val['param'], true);
                $path   = isset($param['path']) ? $param['path'] : '';
                $name   = isset($param['name']) ? $param['name'] : '';

                $picUrl = assetUrlByCdn($path.$name);

                $result[$key] = [
                    'name'          => $val['title'],
                    'picture_id'    => $val['id'],
                    'position_num'  => $val['sort'],
                    'pic_url'       => $picUrl?$picUrl:"/images/",
                ];
                $share  = [];
                if(trim($val['title'])  == "邀请好友"){
                    $share[0]['share_title']    = !empty($param['share_title'])? $param['share_title']  : '点击领取收益';
                    $share[0]['share_desc']     = !empty($param['share_desc']) ? $param['share_desc']   : '我在九斗鱼赚了好多钱，邀请你一起来，注册后即可赚钱！';
                    $share[0]['invite_url']     = 1;
                    $share[0]['share_type']     = 1;
                    $share[0]['share_url']      = env('APP_URL_WX').'/register?inviteId='.$userId;
                    $share[0]['purl']           = $picUrl;
                    $share[0]['share_img']      = (!empty($param['share_image_name'])&&!empty($param['share_image_path'])) ? assetUrlByCdn($param['share_image_path'].$param['share_image_name']) : '';
                    $result[$key]['share']      = $share;
                    $result[$key]['location_url']=!empty($param['share_url'])   ? $param['share_url']   : '';

                }elseif(trim($val['title'])  == "家庭账户"){
                    $share[0]['share_title']    = !empty($param['share_title']) ? $param['share_title'] : '';
                    $share[0]['share_desc']     = !empty($param['share_desc'])  ? $param['share_desc']  : '';
                    $share[0]['share_url']      = !empty($param['share_url'])   ? $param['share_url']   : '';
                    $result[$key]['share']      = $share;
                    $result[$key]['location_url']=!empty($param['share_url'])   ? $param['share_url']   : '';
                }
            }

        }

        return $result;

    }

    /**
     * @param $data
     * @param $userId
     * @return array
     * @desc 格式化App4.1.3首页新手广告数据
     */
    public static function formatAppNoviceAdData($data, $userId){

        //无可用广告
        if (empty($data)) {
            return [];
        }

        //用户已登录
        if (!empty($userId)) {
            //有投资记录，隐藏广告
            $investData = TermLogic::getUserInvestDataByUserId($userId);
            if(!empty($investData)){
                return [];
            }
        }

        $result = [];

        foreach ($data as $key => $value) {

            $param = $value['param'];

            $path = isset($param['path']) ? $param['path'] : '';
            $name = isset($param['name']) ? $param['name'] : '';

            $result = [
                'id'          => $value['id'],
                'title'       => $value['title'],
                'content_url' => $param['url'],
                'image_url'   => assetUrlByCdn($path.$name),
            ];

            break;
        }

        return $result;
    }

}
