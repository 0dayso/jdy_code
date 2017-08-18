<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/10
 * Time: 下午2:32
 * Desc: 零钱计划数据统计
 */

namespace App\Http\Controllers\Admin\Current;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Current\FundLogic;
use Illuminate\Http\Request;

class FundStatisticsController extends AdminController{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 列表信息
     */
    public function index( Request $request )
    {

        $startTime = $request->input('start_time');

        $endTime = $request->input('end_time');

        $export = $request->input('export');

        if( $export ){

            $this->doExport($startTime, $endTime);

            die;

        }

        $logic = new FundLogic();

        $data['list'] = $logic->getList($startTime, $endTime);

        return view('admin.current.fund_statistics', $data);

    }

    /**
     * @param Request $request
     * @desc 执行导出
     */
    public function doExport( $startTime, $endTime )
    {

        $logic = new FundLogic();

        $logic->doExport($startTime, $endTime);
        
    }

}