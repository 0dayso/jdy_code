<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2016/12/20
 * Time: 上午10:43
 * Desc: 提现失败报警处理
 */

namespace App\Listeners\Withdraw;

use App\Http\Logics\Warning\OrderLogic;

class WithdrawErrorListener{

    public function __construct()
    {



    }


    public function handle($data)
    {

        if( isset($data['failed_order']) && json_decode($data['failed_order'], true) ){

            OrderLogic::batchWithdrawError($data['failed_order']);

        }

    }

}