<?php

namespace App\Http\Controllers\Admin\OutCall;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\OutCall\OutCallLogic;
use App\Http\Logics\User\UserLogic;
use App\Tools\ExportFile;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Excel;

class OutCallController extends AdminController
{
    //
    /**
     * @desc 外呼数据上传
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        if($request->isMethod('post')){

            if(!$request->file('outCall')){
                return false;
            }
            $outCallLogic = new OutCallLogic();
            $outCallInfo = $outCallLogic->getOutCallData($request);

            if(empty($outCallInfo)){
                return false;
            }
            ExportFile::exportExcel($outCallInfo,'导出处理外呼数据');
            exit;
        }
        return view('admin.outcall.index');
    }
}
