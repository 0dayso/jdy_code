<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/6
 * Time: 下午3:17
 */

namespace App\Http\Logics\AppButton;


use App\Http\Dbs\AppButton\AppButtonDb;
use App\Http\Dbs\Picture\PictureDb;
use App\Http\Logics\Logic;
use App\Http\Logics\ThirdApi\JyfLogic;
use App\Http\Models\Picture\PictureModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolArray;

class AppButtonLogic extends Logic
{

    /**
     * @return array
     * @desc 请求tabBar图片
     */
    public function menuButton(){

        $db = new AppButtonDb();

        $result = $db -> getAppUserDownButton();

        $data   = $this -> formatMenuButton($result);

        return self::callSuccess($data);

    }

    /**
     * @param $data
     * @return array
     * @desc 请求tabBar图片数据格式化
     */
    public function formatMenuButton( $data ){
        $picDb = new PictureDb();
        if( empty($data) ){

            return [[]];

        }

        $result = [];

        $pictureIds = ToolArray::arrayToIds($data, 'picture_id');

        $pictureList = ToolArray::arrayToKey($picDb->getPicturePaths($pictureIds),'id');

        foreach ($data as $key => $item) {

            /*if($item['picture_id']){
                $picInfo = $picDb->getPicturePath($item['picture_id']);
            }*/

            $result[$key] = [
                'position_num' => $item['position_num'],
                //'pic_url'      => empty($picInfo) ? '' : env('APP_URL').$picInfo,
                'pic_url'      => isset($pictureList[$item['picture_id']]) ? env('APP_URL').'/resources/'.$pictureList[$item['picture_id']]['path'] : '',
            ];

        }

        return $result;

    }

    /**
     * @return array
     * @desc 微信用户中心页中间部分菜单
     */
    public function getUserCenterMenu($userId = 0){

        $db     = new AppButtonDb();

        $result = $db->getAppUserCenterButton();

        $data   = $this->formatUserCenterMenu($result);

        $menu   = JyfLogic::getYmfMenu($userId);

        if($menu){
            $data[] = $menu;
        }

        $newCurrent = $this->getNewCurrentProjectMenu($userId);

        if(!empty($newCurrent)){
            $data[] = $newCurrent;
        }

        return self::callSuccess($data);
    }

    /**
     * @param int $userId
     * @return array
     * 新版活期按钮
     */
    public function getNewCurrentProjectMenu($userId = 0){



        $config = SystemConfigModel::getConfig('NEW_CURRENT_SHOW');

        $configUserArr = [];

        if(!empty($config) && !empty($config['USER_IDS'])){

            $configUserArr = explode(',', $config['USER_IDS']);

        }

        $data = [];

        if(in_array($userId, $configUserArr)){

            $data = [
                "position_num" => 8,
                "title" => "新版活期",
                "picture" => 'https://img1.9douyu.com/static/weixin/images/wap2/wap2-asset-icon5.png?v=20170322',
                "location_url" => env('APP_URL_WX').'/current/new',
            ];

        }

        return $data;

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化用户中心菜单
     */
    private function formatUserCenterMenu( $data )
    {

        if( empty( $data ) ){

            return [];
        }

        $result     = [];

        $formatUrl  = AppButtonDb::centerMenuParam();

        $urlKey     = array_keys($formatUrl);

        $picDb      = new PictureDb();

        $pictureIds = ToolArray::arrayToIds($data, 'picture_id');

        $pictureList = ToolArray::arrayToKey($picDb->getPicturePaths($pictureIds),'id');


        foreach ($data as $key => $v) {

            if($v['position_num'] != 15){

                $parseData    = $this->_parseData($v);

                //$picture    = $picDb->getPicturePath($v['picture_id']);
                $picture      = isset($pictureList[$v['picture_id']]) ? '/resources/'.$pictureList[$v['picture_id']]['path'] : '';

                $url          = isset($parseData['share_url']) ? $parseData['share_url'] : '';

                if( in_array($v['position_num'], $urlKey)){

                    $picture  = $formatUrl[$v['position_num']]['image'];

                    $url      = $formatUrl[$v['position_num']]['location'];
                }else{

                    $picture  = assetUrlByCdn($picture);

                }

                $result[$key]['id']           = $v['id'];

                $result[$key]['position_num'] = $v['position_num'];

                $result[$key]['title']        = $v['name'] ? $v['name'] : $parseData['share_title'];

                $result[$key]['share_desc']   = isset($parseData['share_desc']) ? $parseData['share_desc'] : $v['name'];

                $result[$key]['picture']      = $picture;

                $result[$key]['location_url'] = $url;
            }
        }

        return $result;

    }

    //解析数据
    protected function _parseData($Data)
    {

        if( empty($Data['location_message']) ){

            return [];
        }

        $parseData = unserialize($Data['location_message']);

        return [
            "share_title" => $parseData['share_title'],
            "share_desc"  => $parseData['share_desc'],
            "share_url"   => $parseData['share_url'],
            "picture_id"  => $Data['picture_id'],
        ];
    }

}