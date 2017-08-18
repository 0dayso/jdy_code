<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/22
 * Time: 下午4:57
 * Desc: 用户红包相关信息
 */

namespace App\Http\Controllers\Bonus;

use App\Http\Controllers\Controller;
use App\Http\Logics\Bonus\UserBonusLogic;
use Illuminate\Http\Request;

class UserBonusController extends Controller
{

    //屏蔽父类的session判断
    /*public function __construct()
    {



    }*/

    /**
     * @param Request $request
     * @desc 发送红包加息接口
     */
    public function doSendApi( Request $request )
    {

        $data = $request->all();

        $logic = new UserBonusLogic();

        $result = $logic->doSendBonusApi($data);

        return self::returnJson($result);

    }


}