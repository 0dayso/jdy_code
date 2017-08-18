<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/11/14
 * Time: 19:12
 * desc: 重置剩余未匹配债权监听
 */
namespace App\Listeners\Admin\Credit;



use App\Http\Logics\Credit\CreditDisperseLogic;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetUnMatchCreditListener implements ShouldQueue
{

    /**
     * AddCreditThirdSuccessListener constructor.
     */
    public function __construct()
    {
        //
    }

    public function handle(){

        $logic = new CreditDisperseLogic();

        $logic->resetUnMatchCredit();

    }

}