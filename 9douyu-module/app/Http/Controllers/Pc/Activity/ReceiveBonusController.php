<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\DoubleFestivalLogic;
use App\Http\Logics\Activity\ReceiveBonusLogic;
use App\Tools\ToolJump;
use Redirect;

class ReceiveBonusController extends PcController
{

    public function bonus()
    {
        $activityTime    =   ReceiveBonusLogic::getActivityTime();

        return view('pc.activity.receiveBonus.index',$activityTime);
    }
    
}
