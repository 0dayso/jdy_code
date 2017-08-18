<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 上午11:17
 */

namespace App\Http\Dbs\Notice;

use App\Http\Dbs\JdyDb;

class NoticeDb extends JdyDb{

    protected $table = 'notice';

    const   TYPE_DEFAULT = 0,

            TYPE_REGISTER = 1,                  //注册成功

            /*--------订单---------*/

            TYPE_ORDER_WITHDRAW_CREATE = 2,     //申请提现,创建记录成功

            /*--------定期---------*/

            TYPE_INVEST_PROJECT = 3,            //定期项目	买入成功

            /*--------债权转让---------*/

            TYPE_ASSIGN_CREATE = 4,             //申请转让

            TYPE_ASSIGN_CANCEL = 5,             //取消转让

            TYPE_ASSIGN_SUCCESS = 6,            //转让成功

            /*--------活期---------*/

            TYPE_CURRENT_IN = 7,                //活期项目	买入成功

            TYPE_CURRENT_OUT = 8,               //活期项目	卖出成功

            /*--------回款---------*/

            TYPE_REFUND_INTEREST = 9,           //回款	定期项目	利息回款

            TYPE_REFUND_CASH = 10,              //回款	定期项目	本息回款

            TYPE_REFUND_BEFORE = 11,            //提前回款

            /*--------红包加息券---------*/

            TYPE_BONUS_BIRTHDAY = 12,           //红包	生日发放

            TYPE_BONUS_EXPIRE = 13,             //红包 过期提醒

            TYPE_BONUS_RATE_EXPIRE = 14,        //加息券 过期提醒

            /*--------邀请---------*/

            TYPE_INVITE_SUCCESS = 15,           //合伙人	邀请成功

            TYPE_FAMILY = 16,                   //家庭账户

            TYPE_SYSTEM = 17,                   //系统消息

            TYPE_SITE_NOTICE = 18,              //公告

            /*-----------------------------------阅读状态------------------------------------*/

            READ    = 1,    //已读

            UNREAD  = 0;    //未读


}