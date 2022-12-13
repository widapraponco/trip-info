<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'destinasi',
    ],
    function () use ($router) {
        $router->post('/destinasi', ['uses'=> 'DestinasiController@store',]);
        $router->get('/destinasi', ['uses' => 'DestinasiController@index',]);
        $router->get('/destinasi/{id}', ['uses' => 'DestinasiController@show',]);
        $router->put('/destinasi/{id}', ['uses'=> 'DestinasiController@update',]);
        $router->delete('/destinasi/{id}', ['uses'=> 'DestinasiController@destroy',]);
    }
);