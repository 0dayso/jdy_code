<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\PromotionLogic;
use Illuminate\Http\Request;

class NoviceController extends PcController
{

    public function extension(Request $request )
    {
        $channel        =   $request->input('channel');

        //$promotionLogic =   new PromotionLogic();

        $registerUrl    =   '/register';

        if( !empty($channel) ){

            $registerUrl=   $registerUrl."?channel=".$channel;
        }

        //$viewData       =   $promotionLogic->getStatistics();

        //$viewData['activityTime']   =   $promotionLogic->getTime();

        $viewData['registerUrl']    =   $registerUrl;

        return view("pc.activity.novice.extension",$viewData);
    }

}
