<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/7/7
 * Time: 下午4:16
 * Desc: 定期项目
 */

namespace  App\Http\Controllers\App\Project;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Common\CoreApi\CreditAssignProjectModel;
use Illuminate\Http\Request;

class IndexController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/project_index",
     *   tags={"APP-Project"},
     *   summary="项目列表 [Project\IndexController@index]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目列表失败。",
     *   )
     * )
     */
    public function index()
    {

        $projectLogic = new ProjectLogic();

        $project = $projectLogic->getIndexProjectPack();

        $currentLogic = new CurrentLogic();

        $current['current'] = $currentLogic->getShowProject();

        $current['current']['activity'] = [];

        //去除数据统计
        if( isset($project['stat']) ){

            unset($project['stat']);

        }

        //去除闪电付息
        if( isset($project['sdf']) ){

            unset($project['sdf']);

        }

        $list['project_list'] = array_values(array_merge($current, $project));

        $creditAssignCount = CreditAssignProjectModel::getInvestingCount();

        //债权转让
        $list['credit_assign_count'] = empty($creditAssignCount['total'])?0:$creditAssignCount['total'];

        $return = Logic::callSuccess($list);

        return self::appReturnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project_more",
     *   tags={"APP-Project"},
     *   summary="已经完结项目列表 [Project\IndexController@refundingList]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目列表失败。",
     *   )
     * )
     */
    public function refundingList( Request $request )
    {

        $page = $request->input('page', 1);

        $size = $request->input('size', 10);

        $projectLogic = new ProjectLogic();

        $project = $projectLogic->refundingList($page, $size);
        
        return self::appReturnJson($project);

    }

}