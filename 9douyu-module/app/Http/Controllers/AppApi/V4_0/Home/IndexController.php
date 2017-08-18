<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/2/23
 * Time: 下午3:31
 */

namespace App\Http\Controllers\AppApi\V4_0\Home;


use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;

class IndexController extends AppController
{

    public function index(){

        return AppLogic::callReturn(['test'=>'test']);

    }

}