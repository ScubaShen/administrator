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
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']  // 配合liyu/dingo-serializer-switch, 减少一层data
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {

        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');

            // 工程列表
            $api->get('engineerings', 'EngineeringsController@index')
                ->name('api.engineerings.index');
            // 某个用户发布的所有工程
            $api->get('users/{user}/engineerings', 'EngineeringsController@userIndex')
                ->name('api.users.engineerings.index');
            // 添加工程
            $api->post('engineerings', 'EngineeringsController@store')
                ->name('api.engineerings.store');
            // 修改工程
            $api->patch('engineerings/{engineering}', 'EngineeringsController@update')
                ->name('api.engineerings.update');
            // 删除工程
            $api->delete('engineerings/{engineering}', 'EngineeringsController@destroy')
                ->name('api.engineerings.destroy');
            // 工程详情
            $api->get('engineerings/{engineering}', 'EngineeringsController@show')
                ->name('api.engineerings.show');

            // 批次列表
            $api->get('batches', 'BatchesController@index')
                ->name('api.batches.index');
            // 某个用户发布的所有批次
            $api->get('users/{user}/batches', 'BatchesController@userIndex')
                ->name('api.users.batches.index');
            // 添加批次
            $api->post('batches', 'BatchesController@store')
                ->name('api.batches.store');
            // 修改批次
            $api->patch('batches/{batch}', 'BatchesController@update')
                ->name('api.batches.update');
            // 删除批次
            $api->delete('batches/{batch}', 'BatchesController@destroy')
                ->name('api.batches.destroy');
            // 批次详情
            $api->get('batches/{batch}', 'BatchesController@show')
                ->name('api.batches.show');
        });

    });

});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});