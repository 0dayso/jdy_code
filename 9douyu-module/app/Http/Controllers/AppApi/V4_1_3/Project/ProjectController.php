<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 17/8/2
 * Time: 下午10:33
 */

namespace App\Http\Controllers\AppApi\V4_1_3\Project;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\Project\ProjectAppLogic;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Dbs\Project\ProjectDb;

class ProjectController extends AppController{

    protected $projectAppLogic = null;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->projectAppLogic = new ProjectAppLogic();

    }

    /**
     * @SWG\Post(
     *   path="/project_index?version=4.1.3",
     *   tags={"APP-Project"},
     *   summary="理财列表-定期项目 [Project\ProjectController@index]",
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
     *      default="4.0",
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
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=true,
     *      type="string",
     *      default="10",
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

    /**
     * @desc app4.0定期理财列表
     * @return array
     */
    public function index(Request $request){

        $page = $request->input('page', 1);
        $size = $request->input('size', 6);

        $projectList = [];

        //App4.0首页定期项目列表
        $projectLogic = new ProjectLogic();

        #新手项目
        $projectArr     = $projectLogic->getProjectPackAppV413();
        $projectNovice[]  = !empty($projectArr['novice']) ? $projectArr['novice'] : [];

        $projectList   = $projectLogic->getAppV4ProjectList( [ProjectDb::PROJECT_PRODUCT_LINE_JSX, ProjectDb::PROJECT_PRODUCT_LINE_JAX], $page, $size, [ProjectDb::STATUS_INVESTING, ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED],$projectNovice);

        return AppLogic::callSuccess($projectList);
    }

}


