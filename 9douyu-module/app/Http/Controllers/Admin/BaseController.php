<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Breadcrumbs;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->middleware('admin.login');
        $this->middleware('auth.checkUpdatePassword');

        Breadcrumbs::register('dashboard', function ($breadcrumbs) {
            $breadcrumbs->push('Dashboard', route('admin.home'));
        });
    }
}
