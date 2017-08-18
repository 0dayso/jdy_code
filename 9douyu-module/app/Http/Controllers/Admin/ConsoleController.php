<?php

namespace App\Http\Controllers\Admin;

/**
 * 控制台
 * Class ConsoleController
 * @package App\Http\Controllers
 */
class ConsoleController extends AdminController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('admin.Console');
    }
}
