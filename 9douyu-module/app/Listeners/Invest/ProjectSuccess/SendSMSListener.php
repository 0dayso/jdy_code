<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/8
 * Time: 下午6:36
 * Desc: 投资成功监听，发送短信
 */

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Tools\ToolTime;
use App\Tools\ToolMoney;
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
    public function handle(CommonEvent $event)
    {
        $data = $event->getDataByKey('sms');
        
        //累加项目编号
        $projectLinkCreditModel = new ProjectLinkCreditModel();

        $project = $projectLinkCreditModel->getCoreProjectDetail($data['project_id']);

        $projectNumber = $project['type'] + $project['product_line'];

        $todayStr = ToolTime::dbNow();

        $msgTpl =  \Lang::get('sms.PROJECT_'.$projectNumber);

        $endAt = (isset($project['end_at']) && $project['end_at']!='0000-00-00') ? $project['end_at'] : ToolTime::getDateAfterCurrent($project['invest_time']);

        switch( $projectNumber ){

            case 101:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $endAt, $data['interest_total']);
                break;

            case 103:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $project['invest_time'].'个月', $project['profit_percentage']);
                break;

            case 106:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $project['invest_time'].'个月', $project['profit_percentage']);
                break;

            case 112:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $project['invest_time'].'个月', $project['profit_percentage']);
                break;

            case 200:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $endAt, $data['interest_total']);
                break;

            case 306:
            case 312:
                $msg = sprintf($msgTpl, $todayStr, $data['project_id'], ToolMoney::formatDbCashDelete($data['cash']), $project['invest_time'].'个月', $project['profit_percentage'], $data['refunded_interest']);
                break;

            default:
                $msg = '';
        }

        if( $msg ){

            $postData = [
                'phone' => $data['phone'],
                'msg'   => $msg
            ];

            $url   = Config::get('serviceApi.moduleSms.notice');

            $return = HttpQuery::serverPost($url, $postData);

            if( $return['code'] == Logic::CODE_ERROR ){

                \Log::Error(__CLASS__.__METHOD__.'Error', $postData);
                
            }

        }

    }

}
