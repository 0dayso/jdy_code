<?php
/**
 * Created by PhpStorm.
 * User: lgh－dev
 * Date: 16/11/25
 * Time: 14:40
 * Desc: 活期计息历史记录
 */

namespace App\Http\Controllers\Admin\Current;


use App\Http\Controllers\Controller;
use App\Http\Logics\Current\FundLogic;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @desc 活期计息历史记录列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request ){

        $adminFundInterest = [];

        $page       = $request->input('page',1);
        $phone      = $request->input('phone');
        $startTime  = $request->input('startTime');
        $endTime    = $request->input('endTime');

        $param = [
            'phone'     =>  $phone,
            'startTime' =>  $startTime,
            'endTime'   =>  $endTime,
        ];

        if(empty($request->all())){
            $adminFundInterest = [
                'data'  => [],
                "total"         => 0,
                "last_page"     => '',
                "per_page"      => '',
                "current_page"  => '',
            ];
        }else{
            $fundInterestLogic = new FundLogic();
            $adminFundInterest = $fundInterestLogic->getAdminCurrentInterestHistory($page, self::PAGE_SIZE, $param);

        }
        $assign['interest_list'] = $adminFundInterest['data'];
        $assign['pageInfo'] = [
            "total"         => $adminFundInterest['total'],
            "last_page"     => $adminFundInterest['last_page'],
            "per_page"      => $adminFundInterest['per_page'],
            "current_page"  => $adminFundInterest['current_page'],
            "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
        ];
        $assign['search_form'] = $param;

        return view('admin.current.interest.history', $assign);
    }
}