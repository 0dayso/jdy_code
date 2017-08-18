<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/12
 * Time: 下午3:26
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\AppSecurityModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;

class TestController extends Controller
{
    //


    public function index()
    {


        $res = AppSecurityModel::getInfoByAppId(1);

        var_dump($res);die;

        $results = DB::table('user')->where('phone','18510258037')->first();

        //$results = DB::select("SELECT * FROM sf_user limit 1");

        //$results = app('db')->select("SELECT * FROM user limit 1");


        return new JsonResponse($results);

        var_dump($results);

    }

}
