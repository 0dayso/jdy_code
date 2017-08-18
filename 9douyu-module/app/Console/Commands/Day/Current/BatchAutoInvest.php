<?php
/**
 * Created by PhpStorm.
 * User: gyl
 * Date: 16/12/27
 * Time: 11:54
 * Desc: 每天凌晨3点批量获取自动投资活期的记录
 */
namespace App\Console\Commands\Day\Current;

use App\Http\Dbs\Current\InvestDb;
use App\Http\Models\Common\CoreApi\CurrentModel;
use App\Http\Models\Common\DbKvdbModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Tools\ToolTime;
use Illuminate\Console\Command;
use Config;

class BatchAutoInvest extends Command{

    //计划任务唯一标识
    protected $signature = 'BatchAutoInvest';

    //计划任务描述
    protected $description = '每天凌晨3点批量获取自动投资零钱计划记录';

    public function handle(){

        //记录当前已经接收记录
        $dbKv = new DbKvdbModel();

        $dbRes = $dbKv->getDbKvdbByRawkey('AUTO_INVEST_CURRENT_'.ToolTime::dbDate());

        if( !empty($dbRes) ){

            return false;

        }

        $investList = CurrentModel::getAutoInvestCurrentListByDate();

        //记录零钱计划投资记录
        $currentInvestDb = new InvestDb();

        try{

            if( empty($investList) ){

                throw new \Exception('当日自动投资零钱计划记录为空');

            }

            $result = $currentInvestDb->autoInvest($investList);

            if( !$result ){

                throw new \Exception('当日自动投资零钱计划记录写入失败');

            }

            $dbKv->addData('AUTO_INVEST_CURRENT_'.ToolTime::dbDate(), [ToolTime::dbDate()]);

        }catch (\Exception $e){

            $receiveEmails = Config::get('email.monitor.accessToken');

            $model = new EmailModel();

            try{

                $title = '【Warning】'.ToolTime::dbDate().$e->getMessage();

                $model->sendHtmlEmail($receiveEmails, $title, $title);

            }catch (\Exception $e){

                Log::Error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            }

        }

    }
}