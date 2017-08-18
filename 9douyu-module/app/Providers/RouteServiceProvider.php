<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        $this->mapApiRoutes($router);
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {

            $domain = \Config::get('domain');

            if( strpos(\Config::get('domain'), 'wx') !== false ){

                require app_path('Http/Routes/groupWechat.php');

            }else{

                require app_path('Http/Routes/groupWeb.php');


            }

            require app_path('Http/routes.php');
            require app_path('Http/Routes/groupApp.php');//老版本app 接口路由文件
            require app_path('Http/Routes/groupAdmin.php');
            require app_path('Http/Routes/groupThirdApi.php');
        });

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapApiRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace
        ], function ($router) {
            require app_path('Http/Routes/groupAppApi.php'); //app4.0+ 接口路由文件
        });
    }
}
