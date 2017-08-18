<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/28
 * Time: 下午4:20
 */

namespace App\Http\Controllers\ThirdApi;


use App\Http\Controllers\Controller;
use App\Http\Logics\ThirdApi\R360Logic;
use Illuminate\Http\Request;

class R360Controller extends Controller
{

    /**
     * @param Request $request
     * @return string
     * @desc 获取token 的接口
     */
    public function getToken(Request $request)
    {
        $userName       =   $request->input('userName');

        $password       =   $request->input('password');

        $tokenStatus    =   R360Logic::getToken($userName,$password);

        if( $tokenStatus['status'] == true ){

            $tokenStatus=[
                'return'    =>  true,
                'data'      =>  ['token'=>$tokenStatus['msg']],
            ];

        }

        return self::returnJson($tokenStatus);
    }

    /**
     * @param Request $request
     * @return string
     * @desc 获取满标的项目
     */
    public function getSuccessProjectList( Request $request)
    {
        $date       =   $request->input('date');

        $page       =   $request->input('page',1);

        $pageSize   =   $request->input('pageSize',50);

        $returnMsg  =   R360Logic::getSuccessProjectList($date,$page,$pageSize);

        return self::returnJson($returnMsg);
    }

    /**
     * @param Request $request
     * @return string
     * @desc 正在投标中的项目
     */
    public function getInvestProjectList(Request $request)
    {
        $date       =   $request->input('date');

        $page       =   $request->input('page',1);

        $pageSize   =   $request->input('pageSize',50);

        $returnMsg  =   R360Logic::getInvestIngProjectList($date,$page,$pageSize);

        return self::returnJson($returnMsg);
    }

    /**
     * @desc    批量查询标的状态
     * @param   Request $request
     * @return  string
     *
     */
    public function getProjectStatus(Request $request)
    {
        $idStr      =   $request->input('idStr','');

        $resData    =   R360Logic::getProjectStatus($idStr);

        return self::returnJson($resData);
    }
}