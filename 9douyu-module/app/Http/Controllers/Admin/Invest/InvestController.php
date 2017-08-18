<?php
/**
 * create by PhpStorm
 * User: lgh
 * Date: 16/08/25
 * Time: 18:00
 * @desc 投资管理控制器
 */

namespace App\Http\Controllers\Admin\Invest;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use Illuminate\Http\Request;

class InvestController extends AdminController{
    const PAGE_SIZE  = 20;

    /**
     * 投资记录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $page = $request->input('page', 1);
        $startTime = $request->input('startTime');
        $endTime = $request->input('endTime');
        $phone   = $request->input('phone');
        $investLogic = new TermLogic();

        $param = [
            'startTime' =>  $startTime,
            'endTime'   =>  $endTime,
            'phone'     =>  $phone,
        ];

        //获取投资信息
        $investListData = $investLogic->getAdminInvestList($page, self::PAGE_SIZE, $param);

        $assign['investData'] = $investListData['data'];

        $assign['pageInfo'] = [
            "total"         => $investListData['total'],
            "last_page"     => $investListData['last_page'],
            "per_page"      => $investListData['per_page'],
            "current_page"  => $investListData['current_page'],
            "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
        ];
        //获取债权来源
        $assign['creditSource'] = CreditModel::getSourceType();
        $assign['search_form'] = $param;
        return view('admin.invest.index',$assign);
    }

}