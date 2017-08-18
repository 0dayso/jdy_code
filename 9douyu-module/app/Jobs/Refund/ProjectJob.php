<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/25
 * Time: 下午2:16
 * Desc: 提前还款
 */

namespace App\Jobs\Refund;

use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Jobs\Job;
use Log;

class ProjectJob extends Job{

    protected $data = '';



    public function __construct($data)
    {

        $this->data = $data;

    }

    /**
     * 统一入口
     */
    public function handle()
    {

        $res = ProjectModel::beforeRefundRecord($this->data);

        if( empty($res) ){

            Log::Error(__METHOD__."beforeRefundRecordError", [$this->data]);

        }

    }

}