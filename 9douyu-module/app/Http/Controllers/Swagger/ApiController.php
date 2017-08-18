<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @SWG\Definition(
 *   @SWG\Xml(name="##default")
 * )
 */
class ApiController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/swagger/index",
     *   summary="Swagger-UI页面",
     *   @SWG\Response(
     *     response=200,
     *     description="Swagger-UI页面",
     *     @SWG\Schema(ref="#/definitions/ApiController"),
     *   )
     * )
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        return view('api');
    }

    /**
     * @SWG\Get(
     *   path="/swagger/config",
     *   summary="Swagger Config",
     *   @SWG\Response(
     *     response=200,
     *     description="Swagger配置解析结果json",
     *     @SWG\Schema(ref="#/definitions/ApiController"),
     *   )
     * )
     *
     * @return string
     */
    public function config() {
        //关闭debug
        \Debugbar::disable();

        defined('SWAGGER_HOST') or define('SWAGGER_HOST', env('API_DOMAIN'));

        $swagger = \Swagger\scan([base_path('app/Http/Controllers/AppApi'), base_path('app/Http/Controllers/Swagger')]);
        header('Content-Type: application/json');
        exit((string)$swagger);
    }
}
