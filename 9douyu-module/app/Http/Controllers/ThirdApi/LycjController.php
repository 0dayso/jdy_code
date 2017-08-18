<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/1
 * Time: 下午4:29
 */

namespace App\Http\Controllers\ThirdApi;


use App\Http\Controllers\Controller;
use App\Http\Logics\ThirdApi\LycjLogic;
use Illuminate\Http\Request;

class LycjController extends Controller
{
    /**
     * @param Request $request
     * @desc 获取项目数据
     */
    public function getProjectByStatus(Request $request)
    {
        $status         =   $request->input('status');

        $timeFrom       =   $request->input("time_from",'');

        $timeTo         =   $request->input("time_to",'');

        $pageSize       =   $request->input('page_size',100);

        $pageIndex      =   $request->input('page_index',1);

        $sign           =   $request->input('sign','');

        $projectList    =   LycjLogic::getProjectByStatus($timeFrom,$timeTo,$status,$pageIndex,$pageSize,$sign);


        return self::returnJson($projectList);
    }

    /**
     * @param Request $request
     * @return string
     * @desc 通过项目ID投标的信息
     */
    public function getInvestmentRecord( Request $request)
    {
        $id         =   $request->input('id');

        $page       =   $request->input('page_index',1);

        $pageSize   =   $request->input('page_size',100);

        $projectInfo    =   LycjLogic::getInvestmentRecord($id,$page,$pageSize);

        return self::returnJson($projectInfo);
    }

    /**
     * @param Request $request
     * @return string
     * @desc 数据验证接口
     */
    public function setDataValidation(Request $request)
    {
        $timeFrom       =   $request->input("time_from",'');

        $timeTo         =   $request->input("time_to",'');

        $status         =   $request->input('status');

        $verifyData     =   LycjLogic::setDataValidation($timeFrom,$timeTo,$status);

        return self::returnJson($verifyData);
    }
}