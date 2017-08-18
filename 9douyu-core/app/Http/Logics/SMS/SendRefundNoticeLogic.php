<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/23
 * Time: 上午11:15
 * Desc: 发送回款短信提醒
 */

namespace App\Http\Logics\SMS;

use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\SmsModel;
use App\Tools\ToolArray;
use Log;

class SendRefundNoticeLogic extends Logic{

    /**
     * @param array $data
     * @return array
     * @desc 组装数据
     */
    public function combinationData($data=[]){

        $userIds = ToolArray::arrayToIds($data, 'user_id');

        $userDb = new UserDb();

        $userList = $userDb->getUserListByUserIds($userIds);
        
        $userList = $this->filterUserList($userList);

        $userList = ToolArray::arrayToKey($userList);

        $sendList = [];

        foreach ($data as $refund){

            if( isset($userList[$refund['user_id']]) ){

                //金额小于100
                if( $refund['total_cash'] <1000 ){

                    $sendList[] = [
                        'phone' => $userList[$refund['user_id']]['phone'],
                        'msg'   => '【九斗鱼】亲爱的'.$userList[$refund['user_id']]['real_name'].'，您明日有'.$refund['project_id_total'].'个项目回款，回款总额'.$refund['total_cash'].'元，回款将自动转入零钱计划'.(int)$refund['total_cash'].'元，让您天天享收益。'
                    ];

                }elseif( $refund['total_cash'] <10000 && $refund['total_cash'] >=1000 ){

                    $sendList[] = [
                        'phone' => $userList[$refund['user_id']]['phone'],
                        'msg'   => '【九斗鱼】亲爱的'.$userList[$refund['user_id']]['real_name'].'，您明日有'.$refund['project_id_total'].'个项目回款，回款总额'.$refund['total_cash'].'元，您已收到0.5%的定期加息券，使用此券可让财富翻倍哦。'
                    ];

                }elseif( $refund['total_cash'] >=10000 ){

                    $sendList[] = [
                        'phone' => $userList[$refund['user_id']]['phone'],
                        'msg'   => '【九斗鱼】亲爱的'.$userList[$refund['user_id']]['real_name'].'，您明日有'.$refund['project_id_total'].'个项目回款，回款总额'.$refund['total_cash'].'元，您已收到1%的定期加息券，使用此券可让财富翻倍哦。'
                    ];

                }

               /* $sendList[] = [
                    'phone' => $userList[$refund['user_id']]['phone'],
                    'msg'   => '【九斗鱼】亲爱的'.$userList[$refund['user_id']]['real_name'].'，您今日有'.$refund['project_id_total'].'个项目回款，回款总额'.$refund['total_cash'].'元，已自动转入零钱计划'.(int)$refund['total_cash'].'元，让您天天享收益，请留意账户资金变动。客服4006686568'
                ];*/

            }

        }

        return $sendList;

    }

    /**
     * @param array $data
     * @desc 执行发送
     */
    public function doSend($data=[])
    {
        
        $sendList = $this->combinationData($data);

        if( !empty($sendList) ){

            $smsModel = new SmsModel();

            foreach ($sendList as $send){

                $res = $smsModel->sendNotice($send['phone'], $send['msg']);
                
                if( !$res['status'] ){

                    Log::Error(__METHOD__.'Error', [$send]);

                }

            }

        }

    }

    /**
     * @param $userList
     * @desc 过滤手机号
     */
    public function filterUserList($userList){

        $userErrorList = [];

        foreach ($userList as $userKey => $user){

            //手机号长度不是11位,视为错误手机号
            if( strlen($user['phone']) != 11 ){

                $userErrorList[$user['id']] = $user['phone'];

                unset($userList[$userKey]);
            }

        }

        if( !empty($userErrorList) ){

            Log::Error(__METHOD__.'filterUserListErrorList', $userErrorList);

        }

        return $userList;

    }

}