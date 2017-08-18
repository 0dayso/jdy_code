<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/7/22
 * Time: 下午2:33
 * Desc: 项目投资对接api
 */

namespace App\Http\Controllers\Api\Jdy;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\TermLogic;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/invest/term/submitApi",
     *   tags={"JDY-Api"},
     *   summary="九斗鱼对接定期投资［Api\Jdy\TermController@submitApi］",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户Id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="userBonusId",
     *      in="formData",
     *      description="用户使用红包ID",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Parameter(
     *      name="projectId",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ), 
     *   @SWG\Parameter(
     *      name="source",
     *      in="formData",
     *      description="来源",
     *      required=true,
     *      type="string",
     *      default="pc",
     *      enum={"pc","wap","ios","android"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="定投成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="定投失败。",
     *   )
     * )
     */
    public function submitApi(Request $request){

        $userId             = $request->input('userId');

        $projectId          = $request->input('projectId');

        $userBonusId        = $request->input('userBonusId');

        $cash               = $request->input('cash');

        $source             = $request->input('source');

        $tradingPassword    = $request->input('trading_password');

        $termLogic          = new TermLogic();
        //投资检测
        $res                = $termLogic->checkInvest($userId,$projectId,$cash,$userBonusId,$source,$tradingPassword);

        if(!$res['status']){
            return self::returnJson($res);
        }
        //投资操作
        $invest = $termLogic->invest($userId,$projectId,$cash,$userBonusId,$source);

        return self::returnJson($invest);
    }

}
