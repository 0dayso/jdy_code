<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/20
 * Time: 上午11:17
 * Desc: 监听回款成功，标记项目为完结状态，同时操作相关的债权信息
 */

namespace App\Listeners\Project;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Models\Common\UserFundModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Models\Refund\ProjectModel;
use Illuminate\Support\Facades\DB;
use Log;

class SdfProjectRefundListener implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {



    }

    /**
     * @param $data
     * @throws \Exception
     * @desc 接收参数，投资前置付息回款
     */
    public function handle($data)
    {

        $projectDb      = new ProjectDb();

        $refundDb       = new RefundRecordDb();

        $userFundModel  = new UserFundModel();

        $refundProject  = new ProjectModel();

        $investId       = $data['invest_id'];

        $projectId      = $data['project_id'];

        $userId         = $data['user_id'];

        $project        = $projectDb->getInfoById($projectId);

        if(!empty($project) && $project['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_SDF){

            $refundInfo = $refundDb->getSdfRefundInterestByInvestId($investId, $userId, $projectId);

            if(!empty($refundInfo)){

                Log::info('SdfProjectRefundListener', [$investId, $userId, $projectId]);

                DB::beginTransaction();

                try{

                    $userFundModel->increaseUserBalance($userId, $refundInfo['cash'], FundHistoryDb::PROJECT_REFUND, '项目 '.$projectId.' 回款');

                    $refundProject->updateRefundSuccessByIds([$refundInfo['id']]);

                    Log::info('SdfProjectRefundListenerSuccess', [$investId, $userId, $projectId]);

                    DB::commit();

                }catch(\Exception $e){

                    DB::rollback();

                    Log::info('SdfProjectRefundListenerErroe', [$e->getMessage()]);

                }

            }

        }

    }

}