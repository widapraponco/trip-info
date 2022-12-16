<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'events',
    ],
    function () use ($router) {
        $router->post('/event', ['uses'=> 'EventController@store',]);
        $router->get('/event', ['uses' => 'EventController@index',]);
        $router->get('/event/{id}', ['uses' => 'EventController@show',]);
        $router->put('/event/{id}', ['uses'=> 'EventController@update',]);
        $router->delete('/event/{id}', ['uses'=> 'EventController@destroy',]);
    }
);
