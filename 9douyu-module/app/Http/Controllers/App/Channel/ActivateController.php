<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/10
 * Time: 下午4:51
 * Desc: 渠道激活相关功能入口
 */

namespace App\Channel;

use App\Http\Controllers\App\AppController;
use App\Http\Requests\Request;

class ActivateController extends AppController{

    /**
     * @param Request $request
     * @return array
     * @desc 执行激活 @todo
     * @author gyl
     */
    public function doActivate( Request $request ){

        return self::callSuccess([]);

    }

}


