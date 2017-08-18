<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/22
 * Time: 下午8:37
 */

namespace App\Http\Models\Common\CoreApi;


use App\Http\Models\Common\CoreApiModel;
use App\Tools\ToolMoney;
use Cache;
use Config;
use App\Http\Models\Common\HttpQuery;

class StatisticsModel extends CoreApiModel
{

    /**
     * 获取首页平台数据明细
     * true
     */
    public static function getStatistics( $isFreshCash = true){

        $key = 'JDY_STATISTICS';
        
        $expire = 60*2;

        $data = Cache::get($key);

        if(!empty($data)){

            return json_decode($data,true);

        }else{

            $api  = Config::get('coreApi.moduleStatistics.getJdyStatistics');

            $params = [];

            $return = HttpQuery::corePost($api,$params);

            if($return['status'] && !empty($return['data'])){

                $data = $return['data'];

                Cache::put($key, json_encode($data), $expire);

                return $data;

            }else{

                return [];
            }

        }
    }


    /**
     * @desc    后台数据统计
     **/
    public static function homeStatData(){
        $api    = Config::get('coreApi.moduleStatistics.getHomeStat');
        $params = [];
        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data   = $return['data'];

            return $data;

        }else{

            return [];
        }

    }

}