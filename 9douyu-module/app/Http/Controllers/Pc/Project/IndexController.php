<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/16
 * Time: 上午11:58
 * Desc: 定期项目相关信息
 */

namespace App\Http\Controllers\Pc\Project;

use App\Http\Controllers\Controller;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 项目列表页
     */
    public function index( Request $request )
    {

        $type = $request->input('type', 'Preferred');

       // $type = $request->input('type', 'JSX');

        $page = $request->input('page', 1);

        $type = htmlspecialchars($type);

        $page = (int)$page;

        $size = 20;

        //项目列表
        $projectLogic = new ProjectLogic();

        //添加查询状态
        if( $page == 6 ){

            $size = 30;

        }

        //$list = $projectLogic->getListByProjectLine($type, $page, $size, [ProjectDb::STATUS_INVESTING, ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED]);

        $list   = $projectLogic->getPreferredProjectlist( [ProjectDb::PROJECT_PRODUCT_LINE_JSX, ProjectDb::PROJECT_PRODUCT_LINE_JAX], $page, $size, [ProjectDb::STATUS_INVESTING, ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED]);

        $needHiddenArr = SystemConfigLogic::getConfig('HIDE_PROJECT_ID');

        if( !empty($needHiddenArr) ){

            $hiddenProjectIds = explode(',', $needHiddenArr);

            foreach ($list['list'] as $key => $record) {

                if (in_array($record['id'], $hiddenProjectIds)) {

                    unset($list['list'][$key]);

                    continue;

                }

            }

        }

        //分页
        $pageNation = new ToolPaginate($list['total'], $page, $size, '/project/index?type='.$type);

        //投资风云榜
        $termLogic = new TermLogic();

        $fullWinList = $termLogic->getFulWinList();

        //闪电付息广告
        $ad = AdLogic::getUseAbleListByPositionId(16);

        $data = [
            'paginate'      => $pageNation->getPaginate(),
            'projectList'   => $list['list'],
            'type'          => $type,
            'fullWinList'   => $fullWinList,
            'ad'            => $ad
        ];

        return view('pc.project/index', $data);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 闪电付息列表页面
     */
    public function sdfList()
    {

        return view('pc.project/sdf');

    }


}

