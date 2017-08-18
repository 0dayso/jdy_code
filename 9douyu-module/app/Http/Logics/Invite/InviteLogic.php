<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/12
 * Time: 上午11:25
 * Desc: 邀请关系
 */

namespace App\Http\Logics\Invite;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Dbs\User\InviteDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\InviteModel;

class InviteLogic extends Logic
{

    /**
     * @param $phone 邀请人手机号
     * @param $userId 被邀请人用户Id
     * @return array
     * @desc App添加邀请关系
     */
    public function doAddAppInviteRecord($phone, $userId, $myPhone='')
    {

        $return = self::callError('信息有误');

        //获取邀请人的信息
        $userInfo = UserModel::getBaseUserInfo($phone);

        if( empty($userInfo) || !isset($userInfo['id']) || !$userInfo['id'] ){

            $return['msg'] = '手机号不存在';

            return $return;

        }

        //用户是否已经存在邀请关系
        $inviteModel = new InviteModel();

        $data = [
            'user_id'           => $userInfo['id'],
            'other_user_id'     => $userId,
            'type'              => InviteDb::TYPE_PHONE_NUM,
            'user_type'         => InviteDb::USER_TYPE_NORMAL,
            'source'            => InviteDb::SOURCE_APP
        ];

        try{

            $inviteModel->create($data);

            $return = self::callSuccess([]);

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVITE_SUCCESS);

            $msg = sprintf($msgTpl, $myPhone);

            NoticeLogic::sendNoticeByType(NoticeDb::TYPE_INVITE_SUCCESS, $userInfo['id'], $msg, NoticeDb::TYPE_INVITE_SUCCESS);

        }catch( \Exception $e ){

            $return['msg'] = $e->getMessage();

        }

        return $return;

    }

    /**
     * @param $otherUserId
     * @return array
     * @desc 通过被邀请人的id,获取邀请关系信息
     */
    public function getInfoByOtherUserId($otherUserId)
    {

        $inviteDb = new InviteDb();

        return $inviteDb->getByOtherUserId($otherUserId);

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 通过用户Id获取邀请列表
     */
    public function getListByUserId($userId , $page, $size){

        $inviteDb = new InviteDb();

        $inviteList = $inviteDb->getInviteListByUser(['user_id'=>$userId], $page, $size);

        $partnerLogic = new PartnerLogic();

        $inviteList['list'] =  $partnerLogic->formatUserInfoShow($inviteList['list']);

        return $inviteList;
    }

    /**
     * @param $userId 邀请人userId
     * @param $phone 被邀请人手机号
     * @desc  合伙人后台添加邀请关系
     * @return array
     */
    public function doAddPartnerInvite($userId, $phone){

        $return = self::callError('信息有误');

        //获取被邀请人的信息
        $userInfo = UserModel::getBaseUserInfo($phone);

        if( empty($userInfo) || !isset($userInfo['id']) || !$userInfo['id'] ){

            $return['msg'] = '手机号不存在';

            return $return;

        }

        //用户是否已经存在邀请关系
        $inviteModel = new InviteModel();

        $data = [
            'user_id'           => $userId,
            'other_user_id'     => $userInfo['id'],
            'type'              => InviteDb::TYPE_PARTNER_ADMIN,
            'user_type'         => InviteDb::USER_TYPE_NORMAL,
            'source'            => 0,
        ];

        try{

            ValidateModel::isPhone($phone);

            $inviteModel->create($data);

            $return = self::callSuccess([]);

        }catch( \Exception $e ){

            $return['msg'] = $e->getMessage();

        }

        return $return;

    }

    /**
     * @desc    解绑合伙人
     * @param   $userId     用户ID
     * @param   $ouserId    被解绑用户ID
     * @return  mixed
     *
     */
    public function unbindInvite($userId, $ouserId)
    {

        $db     = new InviteDb();

        return $db->unbindInvite($userId, $ouserId);

    }

}