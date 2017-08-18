<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/**
 * Oauth2.0
 */
$app->singleton('oauth2', function() use ($app) {
    //custom lifetime
    $token_lifetime         = env('OAUTH_TOKEN_LIFETIME') ? env('OAUTH_TOKEN_LIFETIME') : 3600;//一小时
    $token_refresh_lifetime = env('OAUTH_REFRESH_TOKEN_LIFETIME') ? env('OAUTH_REFRESH_TOKEN_LIFETIME') : 7200;//两小时

    //custom table name
    $storage = new OAuth2\Storage\Pdo($app->make('db')->getPdo(),[
        'client_table'        => 'module_oauth_clients',
        'access_token_table'  => 'module_oauth_access_tokens',
        'refresh_token_table' => 'module_oauth_refresh_tokens',
        'code_table'          => 'module_oauth_authorization_codes',
        'jwt_table'           => 'module_oauth_jwt',
        'jti_table'           => 'module_oauth_jti',
        'scope_table'         => 'module_oauth_scopes',
        'public_key_table'    => 'module_oauth_public_keys',
    ]);
    //init
    $server  = new App\Http\Logics\User\OAuthServer($storage,
        [
            'access_lifetime'       =>  $token_lifetime,
            'refresh_token_lifetime'=> $token_refresh_lifetime
        ]);
    //add grant
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, ['always_issue_new_refresh_token'=> true]));
    return $server;
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
