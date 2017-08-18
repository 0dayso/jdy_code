<?php
/**
 * @desc    数据统计
 * @date    2017-05-24
 */
namespace App\Http\Controllers\Admin\StatCenter;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Statistics\StatLogic;


class StatCenterController extends AdminController{


    /**
     * @desc    数据统计
     *
     */
    public function homeStatData(){

        $viewData   = StatLogic::homeStatData();

        return view('admin.statdata.statdata',$viewData);

    }

}