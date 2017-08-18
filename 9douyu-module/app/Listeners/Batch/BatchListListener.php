<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/18
 * Time: 下午7:05
 * Desc: 推送
 */

namespace App\Listeners\Batch;

use App\Http\Logics\Batch\BatchListLogic;
use Illuminate\Contracts\Queue\ShouldQueue;

class BatchListListener implements ShouldQueue {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handle($data)
    {

        $logic = new BatchListLogic();

        $logic->doBatch($data);

    }

}