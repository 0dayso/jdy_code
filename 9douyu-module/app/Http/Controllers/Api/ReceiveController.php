<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/3
 * Time: 上午10:35
 * Desc: 接收数据控制器
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\CurrentLogic;
use App\Tools\IPLimit;
use Illuminate\Http\Request;
use App\Http\Models\Order\WithdrawBillModel;


class ReceiveController extends Controller{
	
	
	public function __construct(Request $request){
		
		$ip =  $request->ip();

		//ip判断
		$result = IPLimit::coreRequestIpCheck($ip);

		if(!$result['status']){

			return self::returnJson($result);
			exit();
		}
		
	}

	public function updateBill(Request $request){

		$data = $request->input('failed_order');

		if(empty($data)){

			return self::returnJson(['status'=>false]);

		}

		$fail_ids = json_decode($data,true);

		$withdrawBillModel = new WithdrawBillModel();

		$result = $withdrawBillModel->updateWithdrawBillFail($fail_ids);

		return self::returnJson($result);

	}

}

