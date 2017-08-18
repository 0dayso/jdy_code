<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/5/31
 * Time: 下午3:58
 * Desc: 项目基础类
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Logics\Project\BeforeRefundRecordLogic;
use Illuminate\Http\Request;

class BeforeRefundRecordController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/project/beforeRefundRecord",
     *   tags={"Project"},
     *   summary="提前还款",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="251d478972d4a3cdbb7943785c3d48ac",
     *   ),
     *    @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="投资ID 多个项目id用逗号隔开",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="项目提前还款成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="项目提前还款失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 提前还款
     */
    public function index(Request $request){

        $projectId = $request->input('project_id');

        /**
         * 1. 通过projectId查询项目信息
         * 2. 通过pgojectId查询所有项目未还款的记录
         * 3. 循环investId
         */

        $logic = new BeforeRefundRecordLogic();

        $result = $logic->beforeRefundRecord($projectId);

        return self::returnJson($result);

    }

}