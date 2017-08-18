<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/4
 * Time: 下午5:19
 */

namespace App\Http\Models\AppButton;


use App\Http\Dbs\AppButton\AppButtonDb;
use App\Http\Dbs\Picture\PictureDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Models\Model;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class AppButtonModel extends Model
{

    /**
     * @return array|mixed
     * @desc 用户中心app按钮列表
     */
    public function getAppUserCenterButton(){

        $db = new AppButtonDb();
        $picDb = new PictureDb();

        $data = $db -> getAppUserCenterButton();

        if(empty($data)){
            return [];
        }

        $pictureIds = ToolArray::arrayToIds($data, 'picture_id');

        $pictureList = ToolArray::arrayToKey($picDb->getPicturePaths($pictureIds),'id');

        foreach($data as $key => $value){


            $picInfo = '';
            if($value['end_time'] >= ToolTime::dbNow() && $value['start_time'] <= ToolTime::dbNow()){
                //$picInfo = $picDb->getPicturePath($value['picture_id']);
                $picInfo = isset($pictureList[$value['picture_id']]) ? '/resources/'.$pictureList[$value['picture_id']]['path'] : '';
            }

            // @todo 如果用户没有头像,则给他默认头像
            $data[$key]['pic_url'] = empty($picInfo) ? '/images/' : env('APP_URL').$picInfo;

            if(!empty($value['location_message'])){
                $data[$key]['share'] = unserialize($value['location_message']);
            }

        }

        $data = $this->getAppButtonByAd( $data );

        return $data;

    }

    public function getAppButtonByAd( $data ){

        $result = AdLogic::getUseAbleListByPositionId(23);

        if( !empty($result) && is_array($result) ){

            foreach($result  as $key => $val){

                $data[] = [
                    'name' => $val['title'],
                    'picture_id' => 0,
                    'position_num' => $val['sort'],
                    'pic_url' => $val['purl'],
                    'location_url' => $val['url'],
                    'share' => [
                        "share_title" => $val['share_title'],
                        "share_desc" => $val['share_desc'],
                        "share_url" => $val['share_url'],
                        "purl" => $val['share_img'],
                        "share_img" => $val['share_img'],
                        "share_type" => 1,
                        'invite_url' => 1,
                    ]
                ];

            }

        }

        return $data;

    }

}