<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 18:39
 * Desc: 零钱计划利率相关
 */
namespace App\Http\Controllers\Admin\Current;

use App\Http\Controllers\Controller;
use App\Http\Logics\Current\RateLogic;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Current\RateRequest;

class RateController extends Controller{
    const PAGE_SIZE = 20; //设置列表每页条数
    /**
     * @desc 零钱计划利率列表
     * @author lgh
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request){
        $page       = $request->input('page',1);
        $phone      = $request->input('phone');
        $startTime  = $request->input('startTime');
        $endTime    = $request->input('endTime');

        $rateLogic = new RateLogic();

        $rateData = $rateLogic->getAdminCurrentRateList($page, self::PAGE_SIZE);
        $assign['rateList'] = $rateData['data'];
        //page分页信息
        $assign['pageInfo'] =[
            "total"         => $rateData['total'],
            "last_page"     => $rateData['last_page'],
            "per_page"      => $rateData['per_page'],
            "current_page"  => $rateData['current_page'],
            "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
        ];
        return view('admin.current.rate.list', $assign);
    }

    /**
     * @desc 创建零钱计划利率的页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('admin.current.rate.create');
    }

    /**
     * @param Request $request
     * 后台添加零钱计划利率
     */
    public function doCreate(RateRequest $request){

        $rate       = $request->input('rate','');   //利率
        $date       = $request->input('rate_date','');  //日期
        $profit     = $request->input('profit','');     //加息利率

        $logic      = new RateLogic();
        $logicResult = $logic->create($date,$rate,$profit);

        if($logicResult['status']){

            return redirect('/admin/current/rate/lists')->with('message', '创建利率成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }

    }

    /**
     * @desc 零钱计划利率编辑页面
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id,Request $request){

        $rateLogic = new RateLogic();

        $rateInfo = $rateLogic->getRateById($id);

        return view('admin.current.rate.edit',$rateInfo);
    }

    /**
     * @param RateRequest $request
     * @return mixed
     * 后台编辑零钱计划利率
     */
    public function doEdit(RateRequest $request){

        $id         = $request->input('id','');
        $rate       = $request->input('rate','');
        $profit     = $request->input('profit','');
        $date       = $request->input('rate_date','');

        $logic      = new RateLogic();
        $logicResult = $logic->edit($id,$date,$rate,$profit);

        if($logicResult['status']){

            return redirect('/admin/current/rate/lists')->with('message', '创建利率成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }

   
}