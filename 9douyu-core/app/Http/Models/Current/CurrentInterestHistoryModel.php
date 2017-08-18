<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/12
 * Time: 下午6:45
 */

namespace App\Http\Models\Current;


use App\Http\Dbs\CurrentInterestHistoryDb;
use App\Http\Models\Model;

class CurrentInterestHistoryModel extends Model
{

    /**
     * @param $userId
     * @return array|mixed
     * @desc 获取用户的7天利息记录
     */
    public static function getFundHistoryList($userId){

        $db = new CurrentInterestHistoryDb();

        $return = $db -> getFundHistoryList($userId);

        return $return ? $return : [];

    }

    /**
     * @desc 获取活期计息记录的列表【管理后台】
     * @param $pageSize
     * @param $attributes
     * @return array|mixed
     */
    public function getAdminFundHistoryListAll($pageSize, $attributes){

        $pageSize = $pageSize ? $pageSize : 20;

        $db = new CurrentInterestHistoryDb();

        $adminList = $db->getAdminFundHistoryListAll($pageSize, $attributes);

        return $adminList ? $adminList : [];
    }

}