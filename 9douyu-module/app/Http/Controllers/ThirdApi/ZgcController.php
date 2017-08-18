<?php

namespace App\Http\Controllers\ThirdApi;

use App\Http\Controllers\Controller;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\ThirdApi\ZgcLogic;
use App\Http\Models\Credit\CreditModel;
use Illuminate\Http\Request;

class ZgcController extends Controller{

    /**
     * 负债信息查询接口规范
     * @author lgh
     * @param Request $request
     */
    public function searchCreditData(Request $request){

        $reqData = str_replace(' ','+',$request->input('reqData'));
        $sign = str_replace(' ','+',$request->input('sign'));
        $customerId = $request->input('customerId');

        $zgcLogic = new ZgcLogic();

        $return = $zgcLogic->searchCreditData($reqData,$sign);

        return self::returnJson($return);
    }




}