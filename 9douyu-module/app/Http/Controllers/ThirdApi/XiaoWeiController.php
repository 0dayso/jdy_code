<?php
/**
 * Created by PhpStorm.
 * @author  @llper
 * @date    2016年12月06日
 * @desc    小微金融风险信息共享平台
 */

namespace App\Http\Controllers\ThirdApi;


use App\Http\Controllers\Controller;
use App\Http\Logics\ThirdApi\XiaoWeiLogic;
use Illuminate\Http\Request;


class XiaoWeiController extends Controller
{

    /**
     * @param   Request $request
     * @desc    小微金融-机构业务系统
     *
     */

    public function memberRishInfo( Request $request ){

        $requestData= $request->input('requestData', "");

        $resultData = XiaoWeiLogic::getMemberRiskInfo($requestData);

        return self::returnJson($resultData);

    }



    /**
     * @desc    接口1，接口2
     * 接口1、获取查询凭证
     * 接口2、根据凭证获取查询结果
     *
     **/
    public function queryRiskInfo(Request $request ){

        $requestData= $request->input('requestData', "");

        $resultData = XiaoWeiLogic::getQueryRiskInfo($requestData);

        return $resultData;
    }


}