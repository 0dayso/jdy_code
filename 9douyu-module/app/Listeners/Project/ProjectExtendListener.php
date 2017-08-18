<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/29
 * Time: 下午8:54
 * Desc: 项目扩展信息
 */

namespace App\Listeners\Project;

use App\Http\Logics\Project\ProjectExtendLogic;

class ProjectExtendListener
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


    public function handle($data)
    {

        if( $data['newcomer'] > 0 ){

            $logic = new ProjectExtendLogic();

            $logic->doAdd($data);

        }
        
    }
}