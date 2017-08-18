<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午6:19
 */

namespace App\Http\Models\User;


use App\Http\Dbs\User\InviteDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class InviteModel extends Model
{

    public static $codeArr = [
        'create'                => 1,
        'checkOtherUserId'      => 2,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVITE;

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 建立邀请关系
     */
    public function create ($data){

        $db = new InviteDb();

        //other_user_id是否已被邀请
        $this -> checkOtherUserId( $data['user_id'], $data['other_user_id'] );

        $result = $db -> add($data);

        if( !$result ) {
            throw new \Exception(LangModel::getLang('ERROR_INVITE_CREATE'), self::getFinalCode('create'));
        }

        return $result;

    }

    /**
     * @param $otherUserId 被邀请Id
     * @return bool
     * @throws \Exception
     * @desc 验证被邀请人是否已被邀请,如果已被邀请则不再建立邀请关系
     */
    public function checkOtherUserId( $userId, $otherUserId ){

        if( $userId == $otherUserId || $userId < 1 || $otherUserId < 1 ){

            throw new \Exception(LangModel::getLang('USER_REGISTER_INVITE_NO_ERROR'), self::getFinalCode('checkOtherUserId'));

        }

        $db = new InviteDb();

        $result = $db -> getByOtherUserId( $otherUserId );

        if( !empty($result) ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_OTHER_USER_ID'), self::getFinalCode('checkOtherUserId'));

        }

        $res = $db->getInfoByUserIdOtherUserId($userId, $otherUserId);

        if( !empty($res) ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_USER_TO_OTHER'), self::getFinalCode('checkOtherUserId'));

        }

        return true;

    }

    /**
     * @param $userIds
     * @param int $size
     * @return bool
     * @desc 获取邀请的合伙人数排行榜 默认前5
     */
    public function getCountInviteSortByUids($userIds,$size=5){
        if(!$userIds){ return false; }

        $inviteDb = new InviteDb();

        return $inviteDb->getCountInviteSortByUids($userIds,$size);
    }

    /**
     * @param array $params
     * @param int $userType
     * @param int $size
     * @return array
     * @desc 获取合伙人累计投资收益的排行榜
     */
    public static function getPartnerInvestmentRanking($params =array(),$userType =InviteDb::USER_TYPE_NORMAL, $size = 10)
    {
        $InviteDb   =   new InviteDb();

        return $InviteDb->getPartnerInvestmentRanking($params,$userType , $size );
    }


    /**
     * @param userIds | array
     * @param startTime | time
     * @param endTime | time
     * @return result | false | array
     * @desc  获取指定用户在指定时间内的邀请人
     */
    public static function getCountInviteSortByUidsTime($userIds = array(),$startTime = '',$endTime = '')
    {
        if(empty($userIds)){return false;}

            $inviteDb  =   new InviteDb();

        return $inviteDb->getCountInviteSortByUidsTime($userIds,$startTime,$endTime);
    }

    /**
     * @param startTime | time | empty
     * @param endTime | time | empty
     * @return array | totalList
     * @desc 获取知道时间内的邀请人信息数据
     */
    public static function getPartnerInviteInfo($params =array(),$userType )
    {
        return (new InviteDb())->getCountInviteSortByTime($params,$userType );
    }

    /**
     * @param array $params
     * @param int $userType
     * @param int $size
     * @return array
     * @desc 获取被邀请人累计投资收益的排行榜(必须是被邀请人)
     */
    public static function getInviteInvestList($params =array(),$userType =InviteDb::USER_TYPE_NORMAL, $size = 10)
    {
        $InviteDb   =   new InviteDb();

        return $InviteDb->getInviteInvestList( $params , $userType , $size );
    }
}
