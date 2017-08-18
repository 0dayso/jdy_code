<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 16:57
 */

namespace App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class PartnerPrincipalDb extends JdyDb{


    /**
     * @return array
     * 获取邀请人数
     */
    public function getInviteUserTotal(){

        return $this->dbToArray(
                self::select(\DB::raw('count(distinct user_id) as total'))
                ->where('created_at','>',ToolTime::dbDate())
                ->first());


    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取合伙人ID
     */
    public function getInviteUserIdByPage($page,$size){


        $offset = max(($page -1 ) * $size,0);

        return self::select('user_id')
            ->groupBy('user_id')
            ->orderBy('user_id')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();
    }


    /**
     * @param $userIds
     * @return mixed
     * 获取多个邀请人的信息
     */
    public function getByUserIds($userIds,$baseCash){

        $dbPrefix = env('DB_PREFIX');
        $sql = "select 
                  user_id,
                  sum(current_principal) as total_current_principal,
                  sum(term_principal) as total_term_principal,
                  sum(if(current_principal + term_principal >".$baseCash.", 1,0)) as invite_total,
                  count(user_id) as total_num
              from 
                  {$dbPrefix}partner_principal 
              where user_id in (".implode(',',$userIds).")
              and created_at > '".ToolTime::dbDate()."'
              group by user_id";

        return app('db')->select($sql);
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return mixed
     * @desc 通过用户id分页获取邀请列表
     */
    public function getListByUserId($userId, $page, $size){

        //$offset = $this->getLimitStart($page, $size);

        return self::where('user_id', $userId)
            ->orderBy('id', 'desc')
            /*->skip($offset)
            ->take($size)*/
            ->get()
            ->toArray();

    }


}