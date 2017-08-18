<?php

namespace App\Http\Controllers\Admin\Sms;

use Illuminate\Http\Request;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Logics\Sms\SmsLogic;
use App\Http\Models\Common\ServiceApi\SmsModel;

class SmsController extends AdminController
{

    /**
     * @desc 营销短信内容敏感词检测
     */
    public function smsCheck()
    {
        return view('admin.sms/smsCheck');
    }
    //

    /**
     * @desc 执行运营营销短信检测的内容
     * @param Request $request
     * @return string
     */
    public function doSmsContentCheck( Request $request )
    {
        $smsContent = $request->input('sms_content', '');

        $smsLogic = new SmsLogic();

        $result = $smsLogic->checkSmsContent( $smsContent );

        return $this->ajaxJson( $result );
    }
}
