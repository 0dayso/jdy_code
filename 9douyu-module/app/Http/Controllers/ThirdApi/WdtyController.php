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
use App\Http\Logics\ThirdApi\WdtyLogic;
use Illuminate\Http\Request;

class WdtyController extends Controller
{

    /**
     * @param Request $request
     * @desc 返回数据接口
     */
    public function getProjectByDate(Request $request)
    {
        $statusKey  =   $request->input('status');

        $startTime  =   $request->input('time_from');

        $endTime    =   $request->input('time_to');

        $page       =   $request->input('page_index',1);

        $pageSize   =   $request->input('page_size',WdtyLogic::MAX_PAGE_SIZE);

        $formatParam=   WdtyLogic::doVerification($statusKey,$startTime,$endTime,$page,$pageSize);

        if( $formatParam['status'] ==false ){
            
            $returnMsg  =   WdtyLogic::setReturnResult($formatParam['msg']);

            return self::returnJson($returnMsg);
        }

        $projectInfo=   WdtyLogic::getProjectsByDate($formatParam['data']);

        return self::returnJson($projectInfo);
    }

    public function getInvestByProjectId( Request $request)
    {
        $projectId  =   $request->input('id',0);
        
        $page       =   $request->input('page_index',1);

        $pageSize   =   $request->input('page_size',WdtyLogic::MAX_PAGE_SIZE);

        $formatParam=   WdtyLogic::doVerificationInvest($projectId,$page,$pageSize);

        if( $formatParam['status'] ==false ){

            $returnMsg  =   WdtyLogic::setReturnResult($formatParam['msg']);

            return self::returnJson($returnMsg);
        }

        $projectInfo=   WdtyLogic::getInvestProject($formatParam['data']);

        return self::returnJson($projectInfo);
    }
    
}
