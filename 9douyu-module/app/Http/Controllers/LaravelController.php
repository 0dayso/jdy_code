<?php

namespace App\Http\Controllers;

use App\Http\Logics\User\SessionLogic;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Session,Request;

class LaravelController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
    

    /**
     * 获取用户ID
     * @return int
     */
    protected function getUserId(){
        //return 82692;
        $session = SessionLogic::getTokenSession();
        if(isset($session['id'])){
            return $session['id'];
        }
        return 0;
    }

    /**
     * 获取用户
     * @return int
     */
    protected function getUser(){
        $session = SessionLogic::getTokenSession();
        if(isset($session['id'])){
            return $session;
        }
        return [];
    }

}
