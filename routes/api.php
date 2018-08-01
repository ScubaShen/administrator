<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        $api->get('engineerings', 'EngineeringsController@index')
            ->name('api.engineerings.index');
        // 需要 token 验证的接口
//        $api->group(['middleware' => 'api.auth'], function($api) {
//            // 当前登录用户信息
//            $api->get('user', 'UsersController@me')
//                ->name('api.user.show');
//            $api->get('engineerings', 'EngineeringsController@index')
//                ->name('api.engineerings.index');
//        });

    });

});