<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;

use App\Http\Controllers\Pc\PcController;
use App\Tools\ToolJump;


class Zt315Controller extends PcController
{
    
    public function index()
    {
        return view("pc.activity.zt315.index");
    }

}