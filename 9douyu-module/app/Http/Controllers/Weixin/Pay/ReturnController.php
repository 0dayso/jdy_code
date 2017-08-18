<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/25
 * Time: 下午5:26
 * Desc: 支付return控制器
 */
namespace App\Http\Controllers\Weixin\Pay;

use App\Http\Controllers\Controller;
use App\Http\Logics\Pay\JdOnlineLogic;
use App\Http\Logics\Pay\ReaOnlineLogic;
use App\Http\Logics\Pay\HnaOnlineLogic;
use App\Http\Logics\Pay\LLAuthLogic;
use App\Http\Logics\Pay\YeeAuthLogic;
use App\Http\Logics\Pay\QdbWithHoldLogic;
use App\Http\Logics\Pay\UmpWithHoldLogic;
use App\Http\Logics\Pay\BestWithHoldLogic;
use App\Http\Logics\Pay\ReaWithHoldLogic;
use Illuminate\Http\Request;
use Redirect;


class ReturnController extends Controller{

    const SUCCESS_URL   = '/pay/success/',
        ERROR_URL       = '/pay/fail/',
        ING_URL         = '/pay/ing/';

    static protected $channel = [

        'JdOnline'      => JdOnlineLogic::class,
        'ReaOnline'     => ReaOnlineLogic::class,
        'HnaOnline'     => HnaOnlineLogic::class,
        'LLAuth'        => LLAuthLogic::class,
        'YeeAuth'       => YeeAuthLogic::class,
        'QdbWithHold'   => QdbWithHoldLogic::class,
        'UmpWithHold'   => UmpWithHoldLogic::class,
        'BestWithHold'  => BestWithHoldLogic::class,
        'ReaWithHold'   => ReaWithHoldLogic::class,

    ];

    /**
     * 充值后return页面跳转
     * @param Request $request
     * @param $platform
     * @param $from
     */
    public function index(Request $request,$platform,$from){

        $object = new self::$channel[$platform];
        $flag   = $object->toReturn($request);

        if($flag){
            return Redirect::to(self::SUCCESS_URL.$from);
        }else{
            return Redirect::to(self::ERROR_URL.$from);
        }
    }

    /**
     * 充值成功页
     * @param $from
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success($from){

        $user   = $this->getUser();
        $assign = ['balance'=>$user['balance']];

        return view($from.'/pay/success',$assign);
    }

    /**
     * 充值失败页
     * @param $from
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fail($from){

        return view($from.'/pay/fail');

    }

    /**
     * 充值后回调处理
     * @param Request $request
     * @param $platform
     * @return $notice
     */
    public function notice(Request $request,$platform){

        $object = new self::$channel[$platform];
        $notice = $object->toNotice($request);
        echo $notice;

    }

}