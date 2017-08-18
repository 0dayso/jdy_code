<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/20
 * Time: 下午8:01
 * Desc: 监听投资成功，自动发布项目
 */

namespace App\Listeners\Project;

use App\Http\Dbs\ProjectDb;
use App\Http\Logics\Project\ProjectLogic;
use Log;

class AutoPublishProjectListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {



    }


    /**
     * 接收参数，自动发布项目
     */
    public function handle($data)
    {

        if( empty($data) || !isset($data['invest_id']) || !isset($data['project_id']) ){

            Log::Error('Listeners-PublishProject',$data);

            return '';

        }

        $projectId = $data['project_id'];

        $projectLogic = new ProjectLogic();

        $projectInfo = $projectLogic->getDetailById($projectId);

        if( $projectInfo['total_amount'] <= $projectInfo['invested_amount'] ){

            //更新为还款中
            $projectLogic->updateStatusRefunding($projectId);

            //通过项目类型，未发布状态获取一个项目
            $projectDb = new ProjectDb();

            $unPublishProject = $projectDb->getOneUnPublishByType($projectInfo['type']);

            if( !empty($unPublishProject) ){

                $projectLogic->autoUpdateStatusInvesting($unPublishProject['id']);

            }

        }



        return '';

    }
}
