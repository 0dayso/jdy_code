<?php
/**
 * Created by 9douyu Coder.
 * User: scofie wu.changming@9douyu.com
 * Date: 15/05/2017.
 * Time: 5:49 PM.
 * Desc: ActivityController.php.
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\Common\ActivityLogic;
use Illuminate\Http\Request;

class ActivityController extends PcController
{

    public function setActToken( Request $request)
    {
        $actToken   =   $request->input('act_token') ;

        return ActivityLogic::setActToken($actToken) ;
    }
}