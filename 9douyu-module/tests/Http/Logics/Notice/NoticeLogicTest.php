<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 下午2:38
 */

namespace Tests\Http\Logics\Notice;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Family\FamilyLogic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Models\Invest\InvestModel;
use App\Tools\ToolTime;

class NoticeLogicTest extends \TestCase{

    public function noticeData(){

        return [
            //userId为空
            [
                'result'    => false,
                'user_id'   => 0,
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_DEFAULT
            ],
            //title为空
            [
                'result'    => false,
                'user_id'   => 10,
                'title'     => '',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],
            //message为空
            [
                'result'    => false,
                'user_id'   => 10,
                'title'     => 'Test Title',
                'message'   => '',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],
            //type为空
            [
                'result'    => false,
                'user_id'   => 10,
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_DEFAULT
            ],
            //成功
            [
                'result'    => true,
                'user_id'   => 10,
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],
        ];
    }

    public function batchNoticeData(){
        return [
            //userId为空
            [
                'result'    => false,
                'user_id'   => [],
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_DEFAULT
            ],
            //title为空
            [
                'result'    => false,
                'user_id'   => [2,3,4],
                'title'     => '',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],
            //message为空
            [
                'result'    => false,
                'user_id'   => [2,3,4],
                'title'     => 'Test Title',
                'message'   => '',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],
            //type为空
            [
                'result'    => false,
                'user_id'   => [2,3,4],
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_DEFAULT
            ],
            //成功
            [
                'result'    => true,
                'user_id'   => [10, 20, 30],
                'title'     => 'Test Title',
                'message'   => 'Test Message',
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ],

        ];
    }

    /**
     * @param $is
     * @param $data
     * @dataProvider noticeData
     */
    public function testDoSend($returnResult, $userId, $title, $message, $type){

        $result = NoticeLogic::sendNoticeByType($title, $userId, $message, $type);

        $this->assertEquals($returnResult, $result['status']);

    }

    /**
     * @param $returnResult
     * @param $userIds
     * @param $title
     * @param $message
     * @param $type
     * @dataProvider batchNoticeData
     */
    public function testBatchSend($returnResult, $userIds, $title, $message, $type){

        $result = NoticeLogic::batchSend($title, $userIds, $message, $type);

        $this->assertEquals($returnResult, $result['status']);

    }

    /**
     * @desc 注册
     */
    public function testEventRegister(){

        //新手红包
        $data['notice'] = [
            'title'     => NoticeDb::TYPE_REGISTER,
            'user_id'   => 10,
            'message'   => NoticeLogic::getMsgTplByType(NoticeDb::TYPE_REGISTER),
            'type'      => NoticeDb::TYPE_REGISTER
        ];

        \Event::fire(new \App\Events\User\ActivityAwardSuccessEvent($data));

    }

    /**
     * 提现
     */
    public function testEventWithdraw(){

        //创建提现成功
        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ORDER_WITHDRAW_CREATE);

        $msg = sprintf($msgTpl, ToolTime::dbNow(), 100);

        $event['notice'] = [
            'title'     => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE,
            'user_id'   => 10,
            'message'   => $msg,
            'type'      => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE
        ];

        \Event::fire(new \App\Events\Order\WithdrawCreateSuccessEvent($event));

    }

    /**
     * 定期投资
     */
    public function testEventInvestProject(){

        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVEST_PROJECT);

        $msg = sprintf($msgTpl, ToolTime::dbNow(), 1033, 100, 20);

        $param['notice'] = [
            'title'     => NoticeDb::TYPE_INVEST_PROJECT,
            'user_id'   => 10,
            'message'   => $msg,
            'type'      => NoticeDb::TYPE_INVEST_PROJECT
        ];

        //调取事件使用红包，发送短信，本模块投资记录
        \Event::fire(new \App\Events\Invest\ProjectSuccessEvent($param));

    }

    /**
     * 创建债权转让
     */
    public function testEventCreateCreditAssignProjectSuccess(){

        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_CREATE);

        $investModel = new InvestModel();

        $investInfo = $investModel->getInvestByInvestId(275994);

        $msg = sprintf($msgTpl, ToolTime::dbNow(), $investInfo['project_id'], 100, 0, 0);

        //申请成功事件
        $param['notice'] = [
            'title'     => NoticeDb::TYPE_ASSIGN_CREATE,
            'user_id'   => 10,
            'message'   => $msg,
            'type'      => NoticeDb::TYPE_ASSIGN_CREATE
        ];

        \Event::fire(new \App\Events\Project\CreateCreditAssignProjectSuccessEvent($param));

    }

    /**
     * 取消债权转让
     */
    public function testEventCancelCreditAssignProjectSuccess(){

        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_CANCEL);

        $msg = sprintf($msgTpl, ToolTime::dbNow(), 10);

        //取消成功事件
        $param['notice'] = [
            'title'     => NoticeDb::TYPE_ASSIGN_CANCEL,
            'user_id'   => 10,
            'message'   => $msg,
            'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
        ];

        \Event::fire(new \App\Events\Project\CancelCreditAssignProjectSuccessEvent($param));

    }

    /**
     * 债转成功
     */
    public function testEventCreditAssignSuccess(){

        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_SUCCESS);

        $msg = sprintf($msgTpl, ToolTime::dbNow(), 1000, 100, 0, 0, 100);

        //申请成功事件
        $param['notice'] = [
            'title'     => NoticeDb::TYPE_ASSIGN_SUCCESS,
            'user_id'   => 10,
            'message'   => $msg,
            'type'      => NoticeDb::TYPE_ASSIGN_SUCCESS
        ];

        \Event::fire(new \App\Events\Project\CreditAssignProjectSuccessEvent($param));

    }

    /**
     * @desc 发送红包
     */
    public function testEventSendBonus(){

        //发送站内信
        $userIdArr = [10, 20, 30];

        $msg = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_BONUS_BIRTHDAY);

        $result = NoticeLogic::batchSend(NoticeDb::TYPE_BONUS_BIRTHDAY, $userIdArr, $msg, NoticeDb::TYPE_BONUS_BIRTHDAY);

        $this->assertEquals(true, $result['status']);

    }

    /**
     * @desc 邀请关系
     */
    public function testInvite(){

        $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVITE_SUCCESS);

        $msg = sprintf($msgTpl, '13661321203');

        $result = NoticeLogic::sendNoticeByType(NoticeDb::TYPE_INVITE_SUCCESS, 10, $msg, NoticeDb::TYPE_INVITE_SUCCESS);

        $this->assertEquals(true, $result['status']);

    }

    /**
     * @desc 文章发布成功事件
     */
    public function testCreateArticle(){

        //文章发布成功事件
        $param['notice'] = [
            'title'     => 1,
            'message'   => '文章站内公告',
            'type'      => 5
        ];

        \Event::fire(new \App\Events\Article\CreateArticleSuccessEvent($param));

    }
    





}