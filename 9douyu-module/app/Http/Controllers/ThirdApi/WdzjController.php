<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/10
 * Time: 下午7:56
 */

namespace App\Http\Controllers\ThirdApi;



use App\Http\Controllers\Controller;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\ThirdApi\WdzjLogic;
use Illuminate\Http\Request;

class WdzjController extends Controller
{

    /**
     * 获取当前正在进行投标中的标信息
     * @version 1.5
     */
    public function getInvestingProject()
    {
        $project    =   WdzjLogic::getInvestingProject();

        return self::returnJson($project);
        
    }

    /**
     * @param Request $request
     * @desc 返回数据接口
     */
    public function getProjectByDate(Request $request)
    {
        $date       =   $request->input('date');

        $page       =   $request->input('page');

        $pageSize   =   $request->input('pageSize');

        $logic      =   new WdzjLogic();

        $formatParam=   $logic->doVerification($date,$page,$pageSize);

        if( $formatParam['status'] ==false ){

            return self::returnJson($formatParam);
        }

        $projectInfo=   $logic->getProjectsByDate($formatParam['data']);

        return self::returnJson($projectInfo);
    }
    
}