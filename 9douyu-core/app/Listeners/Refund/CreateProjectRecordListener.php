<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/20
 * Time: 下午8:01
 * Desc: 监听投资成功，生成定期项目的回款记录
 */

namespace App\Listeners\Refund;

use App\Http\Logics\Refund\ProjectLogic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class CreateProjectRecordListener
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

        if( empty($data) || empty($data['invest_id']) ){

            Log::Error('Refund-InvestProjectSuccessListener',$data);

            return '';

        }

        $investId = $data['invest_id'];

        $logic = new ProjectLogic();

        $logic->createRecord($investId);

    }
}
