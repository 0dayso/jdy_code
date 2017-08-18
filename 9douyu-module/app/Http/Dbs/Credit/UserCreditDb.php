<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/3/21
 * Time: Pm 11:25
 * 新分散债权DB类
 */

namespace App\Http\Dbs\Credit;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class UserCreditDb extends JdyDb{

    protected $table = 'user_credit';

    /**
     * @desc 创建债权
     * @param $attributes
     * @reurn mixed
     */
    public function add($data=[]){

        return $this->insert($data);

    }





    public function del($id){

    }


    /**
     * @desc 获取新债权的列表
     * @author linguanghui
     * @param $size int
     * @param $condition array
     * @return mixed
     */
    public function getCreditList($userId, $size=100){


        return $this->join('credit_disperse', 'credit_disperse.id', '=', 'user_credit.credit_id')
            ->where('user_credit.user_id', '=', $userId)
            ->where('user_credit.created_at', '>', ToolTime::dbDate())
            ->select('credit_disperse.*','user_credit.amount as match_amount')
            ->orderBy('user_credit.id',' desc')
            ->paginate($size);

    }


    /**
     * @desc 获取昨日已匹配债权的用户数据
     * @author linguanghui
     * @return mixed
     */
    public function getYesterdayMatchAccount( )
    {
        return $this->select( 'user_id', \DB::raw( 'sum( amount ) as yesterday_cash' ) )
            ->where( 'created_at', '<', ToolTime::dbDate() )
            ->where( 'created_at', '>', ToolTime::getDateBeforeCurrent( 2 ))
            ->groupBy( 'user_id' )
            ->get()
            ->toArray();
    }

}
