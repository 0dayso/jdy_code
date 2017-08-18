<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/25
 * Time: 下午5:26
 * Desc: 支付return控制器
 */
namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use App\Http\Logics\Pay\BFAuthLogic;
use App\Http\Logics\Pay\SumaAuthLogic;
use App\Http\Logics\Pay\SumaOnlineLogic;
use App\Http\Logics\Pay\UCFAuthLogic;
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
use Cache;
use Log;


class ReturnController extends Controller{

    const SUCCESS_URL = '/pay/success/',
        ERROR_URL = '/pay/fail/',
        ING_URL = '/pay/ing/';

    static protected $channel = [
        'JdOnline'      => JdOnlineLogic::class,
        'ReaOnline'     => ReaOnlineLogic::class,
        'HnaOnline'     => HnaOnlineLogic::class,
        'SumaOnline'    => SumaOnlineLogic::class,  //丰付网银支付
        'LLAuth'        => LLAuthLogic::class,
        'YeeAuth'       => YeeAuthLogic::class,
        'BFAuth'        => BFAuthLogic::class,
        'UCFAuth'       => UCFAuthLogic::class,
        'SumaAuth'      => SumaAuthLogic::class,
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
        $object = new SELF::$channel[$platform];
        Log::info('rechargeReturn',$request->all());
        $return = $object->toReturn($request->all(),$from);

        if($return['status']){
            return Redirect::to(SELF::SUCCESS_URL.$from.'/'.$return['cash']);
        }else{
            if($return['msg']){
                return Redirect::to(SELF::ERROR_URL.$from.'/'.$return['msg']);

            }else{
                return Redirect::to(SELF::ERROR_URL.$from);

            }
        }
    }

    /**
     * 充值成功页
     * @param $from
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success($from,$cash=''){
        //$user = $this->getUser();
        //$assign = ['balance'=>$user['balance']];

        $client = Cache::get('client','');
        $version = Cache::get('version','');

        return view($from.'/pay/success',['cash' => $cash,'client'=>$client,'version'=>$version]);
    }

    /**
     * 充值失败页
     * @param $from
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fail($from,$msg=''){

        $client = Cache::get('client','');

        return view($from.'/pay/fail',['client' => $client,'msg'=> $msg]);
    }

    /**
     * 充值后回调处理
     * @param Request $request
     * @param $platform
     * @return $notice
     */
    public function notice(Request $request,$platform){
        $object = new self::$channel[$platform];
        Log::info('rechargeNotice',$request->all());
        $notice = $object->toNotice($request->all());
        echo $notice;
    }

}