<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/29
 * Time: 上午10:53
 */

namespace App\Http\Controllers\Pc\Home;


use App\Http\Controllers\Pc\PcController;
use App\Http\Models\SystemConfig\SystemConfigModel;

class UntilController extends PcController
{
    public function plist()
    {

        $appConfig      =   SystemConfigModel::getConfig('APP_DOWNLOAD');

        $viewData   =   [
            'appUrl'    =>  $appConfig["IOS_IPA"],
            'version'   =>  $appConfig["IOS_VERSION"],
        ];
        header('Content-Type:text/xml; charset=utf-8');

        return view("pc.until.plist",$viewData);
    }

    /**
     * knet cnnic认证
     */
    public function cnnic() {

        return view("pc.until.cnnic");
    }
}