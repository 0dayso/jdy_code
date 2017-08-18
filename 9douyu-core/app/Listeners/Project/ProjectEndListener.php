<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/20
 * Time: 上午11:17
 * Desc: 监听回款成功，标记项目为完结状态，同时操作相关的债权信息
 */

namespace App\Listeners\Project;

use App\Http\Logics\Project\ProjectLogic;
use App\Tools\ToolTime;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectEndListener implements ShouldQueue
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
     * 接收参数，自动标记项目状态为完结，同时操作债权信息
     */
    public function handle($data)
    {

        if( !isset($data['project_ids']) ){

            return false;

        }

        $endTime = isset($data['end_time']) ? $data['end_time'] : ToolTime::dbDate();

        //通过项目id获取项目信息
        $projectLogic = new ProjectLogic();

        $projectLogic->doProjectEnd($data['project_ids'], $endTime);

    }

}