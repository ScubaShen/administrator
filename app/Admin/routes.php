<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('users', 'UsersController@index');
    $router->get('companies', 'CompaniesController@index');
    $router->get('companies/create', 'CompaniesController@create');
    $router->post('companies', 'CompaniesController@store');
    $router->get('companies/{id}/edit', 'CompaniesController@edit');
    $router->put('companies/{id}', 'CompaniesController@update');
});
