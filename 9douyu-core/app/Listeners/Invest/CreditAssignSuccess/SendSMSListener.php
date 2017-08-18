<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 下午6:36
 * Desc: 投资债转成功监听，发送短信
 */

namespace App\Listeners\Invest\CreditAssignSuccess;

use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\SmsModel;
use Illuminate\Support\Facades\Lang;
use App\Tools\ToolArray;
use Config;
use Log;

class SendSMSListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Invest/ProjectSuccessEvent  $event
     * @return void
     * @desc data 为二维数组，手机号，项目信息，投资金额信息，收益回款信息；参见message中的文字
     */
    public function handle($data)
    {

        //债权转让投资成功发送短信
        //'MESSAGE_CREDIT_ASSIGN_SELLER' => '【九斗鱼】恭喜，您申请转让的%s项目已完成转让，本次成功转让本金%s元，请注意查看账户。客服4006686568',
        //'MESSAGE_CREDIT_ASSIGN_BUYER' => '【九斗鱼】恭喜，您已成功购买债权转让项目%s，本次投资金额%s元，购买本金%s元，首次回款日为%s，回款金额为%s元。客服：4006686568',
        
        //卖方
        $sellerMsgTpl =  Lang::get('messages.MESSAGE_CREDIT_ASSIGN_SELLER');
        //买方
        $buyerMsgTpl    =  Lang::get('messages.MESSAGE_CREDIT_ASSIGN_BUYER');

        $projectId = $data['project_id'];
        $cash = $data['cash'];
        //短信内容拼装
        $sellerMsg = sprintf($sellerMsgTpl,$projectId,$cash);

        $buyerMsg = sprintf($buyerMsgTpl,$projectId,$cash,$cash,$data['refundDate'],$data['total']);

        $buyerUid = $data['buyer_uid'];//卖方UID

        $sellerUid = $data['seller_uid'];//羼方UID

        //获取买卖双方的手机号
        $userDb = new UserDb();

        $userList = $userDb->getUserListByUserIds([$sellerUid,$buyerUid]);

        $userList = ToolArray::arrayToKey($userList,'id');

        $model = new SmsModel();

        //给买卖双方发送短信提醒
        foreach($userList as $uid => $val){

            $phone = $val['phone'];

            if($uid == $sellerUid){
                $msg = $sellerMsg;
            }else{
                $msg = $buyerMsg;
            }


            $result = $model->sendNotice($phone,$msg);

            $result['phone'] = $phone;
            $result['msg'] = $msg;

            Log::info('INVEST_CREDIT_ASSIGN_SUCCESS',$result);
            

        }


    }





}
