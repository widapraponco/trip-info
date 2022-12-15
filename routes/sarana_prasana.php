<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'sarana_prasarana',
    ],
    function () use ($router) {
        $router->post('/sarana_prasarana', ['uses'=> 'sarana_prasaranaController@store',]);
        $router->get('/sarana_prasarana', ['uses' => 'sarana_prasaranaController@index',]);
        $router->get('/sarana_prasarana/{id}', ['uses' => 'sarana_prasaranaController@show',]);
        $router->put('/sarana_prasarana/{id}', ['uses'=> 'sarana_prasaranaController@update',]);

    }
);