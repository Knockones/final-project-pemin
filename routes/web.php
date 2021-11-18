<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', [
        // TODO: Routes this to the right controller
        'uses' => 'AuthController@register',
    ]);

    $router->post('/login', [
        // TODO: Routes this to the right controller
        'uses' => 'AuthController@login',
    ]);
});

$router->group(['prefix' => 'books'], function () use ($router) {
    $router->get('/', [
        // TODO: Routes this to the right controller
        'uses' => 'BookController@index',
    ]);

    $router->get('/{bookId}', [
        // TODO: Routes this to the right controller
        'uses' => 'BookController@show',
    ]);
});

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/{userId}', [
            // TODO: Routes this to the right controller
            'uses' => 'UserController@show',
        ]);

        $router->put('/{userId}', [
            // TODO: Routes this to the right controller
            'uses' => 'UserController@update',
        ]);

        $router->delete('/{userId}', [
            // TODO: Routes this to the right controller
            'uses' => 'UserController@destroy',
        ]);
    });

    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->get('/', [
          // TODO: Routes this to the right controller
          'uses' => 'TransactionController@index',
        ]);

        $router->get('/{transactionId}', [
          // TODO: Routes this to the right controller
          'uses' => 'TransactionController@show',
        ]);

        $router->put('/{transactionId}', [
            // TODO: Routes this to the right controller
            'uses' => 'TransactionController@update',
        ]);
    });
});
//auth:admin
$router->group(['middleware' => ['auth', 'admin']], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', [
            // TODO: Routes this to the right controller
            'uses' => 'UserController@index',
        ]);
    });

    $router->group(['prefix' => 'books'], function () use ($router) {
        $router->post('/', [
            // TODO: Routes this to the right controller
            'uses' => 'BookController@create',
        ]);

        $router->put('/{bookId}', [
            // TODO: Routes this to the right controller
            'uses' => 'BookController@update',
        ]);

        $router->delete('/{bookId}', [
            // TODO: Routes this to the right controller
            'uses' => 'BookController@destroy',
        ]);
    });


});

$router->group(['middleware' => ['auth']], function () use ($router) {
    $router->group(['prefix' => 'transactions'], function () use ($router) {
        $router->post('/', [
            // TODO: Routes this to the right controller
            'uses' => 'TransactionController@create',
        ]);
    });
});
