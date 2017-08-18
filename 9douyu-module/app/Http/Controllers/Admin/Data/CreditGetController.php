<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/08
 * Time: 上午5:26
 * Desc: 通过项目信息获取债权控制器
 */

namespace App\Http\Controllers\Admin\Data;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Data\CreditListOutLogic;
use App\Http\Dbs\Credit\CreditDb;

use App\Tools\ExportFile;
use App\Tools\ToolTime;

class CreditGetController extends AdminController
{
    /**
     * @desc 关联项目债权借款人信息导出页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCredit( )
    {
        return view( 'admin.data.creditList' );
    }

    /**
     * @desc 获取项目关联的房抵的债权人信息[03-01后]
     * @param Request $request
     */
    public function getBuildCredit( Request $request )
    {
        $startTime = $request->input( 'start_time', ToolTime::dbDate() );
        $endTime = $request->input( 'end_time', ToolTime::dbDate() );
        $type = $request->input( 'type', CreditDb::SOURCE_HOUSING_MORTGAGE );

        $creditListLogic = new CreditListOutLogic();

        //项目相关的sql条件
        $data = [
            'start_time'        => $startTime,
            'end_time'          => $endTime,
            'is_before'         => 0,
        ];

        $creditData  =  $creditListLogic->getOutCreditData( $type, $data );

        $title = '当天到期项目的的债权信息';

        $return  = $creditListLogic->sendCreditEmailData( $creditData, $title );

        return redirect()->back()->with('message', $return['msg']);
    }
}
