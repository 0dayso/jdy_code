<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/12
 * Time: 14:58
 */

namespace App\Http\Models\Current;

use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Models\Common\HttpQuery;
use Log;

class BonusModel extends Model{

    public static $codeArr = [
        'getYesterdayBonusUserNum'             => 1,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BONUS_CURRENT;

    /**
     * 获取昨日零钱计划加息用户总数
     */
    public static function getYesterdayBonusUserNum(){


        $num = UserBonusDb::getYesterdayBonusUserNum();

        if($num === 0){
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_BONUS_INTEREST_USER_NOT_EXIST'), self::getFinalCode('getYesterdayBonusUserNum'));
        }

        return $num;
    }

    /**
     * 分页获取零钱计划计息用户总数
     */
    public static function getYesterdayBonusUserList($total){

        $size = 100;
        
        $totalPage  = ceil($total / $size); //总页数

        $interestList = [];

        for($page = 1;$page <= $totalPage;$page++) {

            //分页获取零钱计划计息用户列表
            $userList = UserBonusDb::getYesterdayBonusUserList($page,$size);

            $bonusIds = ToolArray::arrayToIds($userList,'bonus_id');

            //获取多个零钱计划加息券的信息
            $bonusData = BonusDb::getByIds($bonusIds);

            $bonusList = ToolArray::arrayToKey($bonusData,'id');

            //数据组装
            foreach($userList as $val){

                $bonusId = $val['bonus_id'];

                $interestList[] = [
                    'user_id' => $val['user_id'],
                    'rate'    => $bonusList[$bonusId]['rate']
                ];

            }
            //发送数据到核心进行计息
            self::sendInterestData($interestList);

        }    
    }

    /**
     * @param $params
     * 零钱计划加息券需要计息的用户发送到核心处理
     */
    private static function sendInterestData($data){

        $params= [
            'interest_list' => json_encode($data),
        ];

        $result = HttpQuery::corePost('/current/bonusInterestAccrual', $params);

        if(!$result['status']){

            $data['msg'] = '发送零钱计划加息券计息数据失败';

            Log::error(__METHOD__.'Error',$data);
            //发送报警邮件,待完善
        }

    }


}