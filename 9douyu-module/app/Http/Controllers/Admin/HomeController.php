<?php

namespace App\Http\Controllers\Admin;

use App\Http\Logics\Statistics\StatLogic;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        #$viewData   = StatLogic::homeStatData();
        return view('admin.home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return redirect(route('admin.home'));
    }
}
