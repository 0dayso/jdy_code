<?php

/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 10:54
 * Desc: 自动创建零钱计划项目
 */

namespace App\Listeners\Invest\CurrentSuccess;

use App\Http\Logics\Project\CurrentLogic;
use App\Events\Invest\CurrentSuccessEvent;
use App\Http\Models\Invest\CurrentModel;

class AutoPublishProjectListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    
    /**
     * Handle the event.
     *
     * @param  $data
     * @return void
     */
    public function handle(CurrentSuccessEvent $event)
    {
        $projectInfo = $event->getProjectData();

        //项目被投满生成新的项目
        if($projectInfo['cash'] == $projectInfo['left_amount']){

            $model = new CurrentModel();

            $config = $model->getConfig();

            //是否自动创建
            if($config['IS_AUTO']){
                //项目名称
                $projectName = $config['AUTO_NAME'];
                //金额
                $amount      = $config['AUTO_AMOUNT'];
                //发布时间
                $date        = date('Y-m-d H:i:s');
                //发布新的零钱计划项目
                $logic       = new CurrentLogic();
                $result      = $logic->create($projectName,$amount,$date);

                //创建项目失败,发送报警邮件
                /*
                if(!$result['status']){
                    $email = new EmailModel();
                    $email->send();
                }
                */
            }
        }




       
    }
}
