<?php

use Illuminate\Routing\Router;


Request::setTrustedProxies(array(
    '114.215.27.57' // IP address of your intermediary
));


Admin::registerHelpersRoutes();

Admin::registerInterfaceRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');

});

die("abc");