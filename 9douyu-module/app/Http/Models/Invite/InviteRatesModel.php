<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/11/10
 * Time: 下午4:02
 * Desc: 邀请加息券相关
 */

namespace App\Http\Models\Invite;

use App\Http\Dbs\Invite\InviteRatesDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolTime;

class InviteRatesModel extends Model{

    private $db = null;

    function __construct()
    {

        $this->db = new InviteRatesDb();

    }

    public static $codeArr = [
        'doAdd'                                 => 1,
        'checkUsingRateByUserId'                => 2,
        'emptyInfo'                             => 3,
        'doUse'                                 => 4,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVITE_RATE;


    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 执行添加
     */
    public function doAdd($data){

        $res = $this->db->doAdd($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_RATE_INFO'), self::getFinalCode('doAdd'));

        }

        return $res;

    }

    //检测当前用户是否已经在使用加息券
    public function checkUsingRateByUserId($userId){

        $info = $this->db
            ->UserId($userId)
            ->Status(InviteRatesDb::STATUS_USED)
            ->EgtRateStartTime(ToolTime::getDateAfterCurrent())
            ->EltRateEndTime(ToolTime::dbNow())
            ->first();

        if( !empty($info) ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_RATE_USING'), self::getFinalCode('checkUsingRateByUserId'));

        }

    }

    //检测用户是否可以使用
    public function checkCanUse($id, $userId){

        //检测当前用户是否已经在使用加息券
        $this->checkUsingRateByUserId($userId);

        $info = $this->db
            ->Id($id)
            ->UserId($userId)
            ->get()
            ->toArray();

        if( empty($info) ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_RATE_INFO'), self::getFinalCode('emptyInfo'));

        }

    }

    /**
     * @param $id
     * @param $userId
     * @throws \Exception
     * @desc 执行使用
     */
    public function doUse($id, $userId){

        $this->checkCanUse($id, $userId);

        $res = $this->db->doUseRate($id);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_INVITE_RATE_USE'), self::getFinalCode('doUse'));

        }

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户可用的列表
     */
    public function getCanUseListByUserId($userId){

        return $this->db->UserId($userId)
            ->Status(InviteRatesDb::STATUS_CAN_USE)
            ->EltExpireTime(ToolTime::dbNow())
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return int
     * @desc 获取用户正在使用的加息利率
     */
    public function getUsingRateByUserIds($userIds){

        return $this->db->UserIds($userIds)
            ->Status(InviteRatesDb::STATUS_USED)
            //->EgtRateStartTime(ToolTime::dbNow())
            ->EltRateEndTime(ToolTime::dbNow())
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过userId删除
     */
    public function doDelByUserId($userId){

        return $this->db->UserId($userId)
            ->delete();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 根据id删除
     */
    public function doDel($id, $userId){

        return $this->db->Id($id)
            ->UserId($userId)
            ->delete();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过用户id获取列表
     */
    public function getListByUserIds($userId){

        return $this->db->UserIds($userId)
            ->get()
            ->toArray();

    }

}