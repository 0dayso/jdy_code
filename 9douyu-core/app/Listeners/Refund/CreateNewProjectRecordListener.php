<?php
/**
 * Created by PhpStorm.
 * User: liu.qiuhui
 * Date: 17/4/25
 * Time: 下午2:01
 * Desc: 监听投资成功，生成新定期项目的回款记录
 */

namespace App\Listeners\Refund;

use App\Http\Logics\Refund\ProjectLogic;
use Log;


class CreateNewProjectRecordListener
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
     * 接收参数，创建回款记录
     */
    public function handle($data)
    {

        if( empty($data) || empty($data['project_id']) ){

            Log::Error('Refund-InvestProjectSuccessListener',$data);

            return '';

        }

        $projectId = $data['project_id'];

        $logic = new ProjectLogic();

        $logic->projectFullCreateRefundRecord($projectId);

    }
}
