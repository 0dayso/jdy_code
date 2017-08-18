<?php
/**
 * User: caelyn
 * Date: 16/8/8
 * Time: 16:44
 */

namespace App\Http\Controllers\Api\Jdy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\Order\WithdrawBillLogic;

class WithdrawBillController extends Controller{

    /**
     * @param $csvData
     * @return json
     * @desc 自动对账对接九斗鱼
     */
    public function matchBill(Request $request){
        $res = [];
        $csvData = $request->input('csvData');
        $csvData = urldecode($csvData);
        $csvData = preg_replace('/\d+=/','',$csvData);
        $csvData = explode('&',$csvData);
        foreach($csvData as $key => $csvLine){
            $data[] = get_object_vars(json_decode($csvLine));
        }
        if(!empty($data)){
            $withdrawBillLogic = new WithdrawBillLogic();
            $res = $withdrawBillLogic->addBillInfo($data);
        }
        return json_encode($res);
    }

}