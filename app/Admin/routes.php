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
    $router->get('users/create', 'UsersController@create');
    $router->post('users', 'UsersController@store');
    $router->get('users/{id}/edit', 'UsersController@edit');
    $router->put('users/{id}', 'UsersController@update');

    $router->get('companies', 'CompaniesController@index');
    $router->get('companies/create', 'CompaniesController@create');
    $router->post('companies', 'CompaniesController@store');
    $router->get('companies/{id}/edit', 'CompaniesController@edit');
    $router->put('companies/{id}', 'CompaniesController@update');

    $router->get('supervisions', 'SupervisionsController@index');
    $router->get('supervisions/create', 'SupervisionsController@create');
    $router->post('supervisions', 'SupervisionsController@store');
    $router->get('supervisions/{id}/edit', 'SupervisionsController@edit');
    $router->put('supervisions/{id}', 'SupervisionsController@update');

    $router->get('engineerings', 'EngineeringsController@index');
    $router->get('engineerings/create', 'EngineeringsController@create');
    $router->post('engineerings', 'EngineeringsController@store');
    $router->get('engineerings/{id}/edit', 'EngineeringsController@edit');
    $router->put('engineerings/{id}', 'EngineeringsController@update');

    $router->get('batches', 'BatchesController@index');
    $router->get('batches/create', 'BatchesController@create');
    $router->post('batches', 'BatchesController@store');
    $router->get('batches/{id}/edit', 'BatchesController@edit');
    $router->put('batches/{id}', 'BatchesController@update');
});
