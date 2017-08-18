<?php
/**
 * Created by PhpStorm.
 * User: 林光辉
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 定时发送每日数据统计邮件 前日0点到今日0点，每日0点1分钟执行
 */
namespace App\Console\Commands\Day\Data;

use App\Http\Logics\Data\DataStatisticsLogic;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\JdyDataApi\RegisterLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserInfoModel;
use App\Http\Models\User\UserModel;
use Illuminate\Console\Command;

class SendWebData extends Command{

    //计划任务唯一标识
    protected $signature = 'SendWebData';

    //计划任务描述
    protected $description = '每天00:17发送九斗鱼统计报表.';


    public function handle(){

        $dataStatisticsLogic = new DataStatisticsLogic();

        $dataStatisticsLogic->SendDayWebData();

    }
}