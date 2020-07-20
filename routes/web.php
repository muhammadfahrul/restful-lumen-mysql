<?php

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

$router->get('/key', function() {
    return str_random(32);
});

$router->post('api/v1/login', 'LoginController@login');

$router->group(['middleware' => 'auth_token'], function () use ($router) {
    $router->group(['prefix' => 'api/v1'], function () use ($router) {
        $router->get('/users', 'UserController@showAll');
        $router->get('/users/{id}', 'UserController@showId');
        $router->post('/users', 'UserController@add');
        $router->put('/users/{id}', 'UserController@update');
        $router->delete('/users/{id}', 'UserController@delete');
    
        $router->get('/items', 'ItemController@showAll');
        $router->get('/items/{id}', 'ItemController@showId');
        $router->post('/items', 'ItemController@add');
        $router->put('/items/{id}', 'ItemController@update');
        $router->delete('/items/{id}', 'ItemController@delete');
    });
});