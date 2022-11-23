<?php

$router->group(
    [
        'as' => 'destinasi',
    ],
    function () use ($router) {
        $router->post('/destinasi', ['middleware' => ['auth'], 'uses'=> 'DestinasiController@create',]);
        $router->get('/destinasi', ['uses' => 'DestinasiController@find',]);
        $router->get('/destinasi/{id}', ['middleware' => ['auth'], 'uses' => 'DestinasiController@findById',]);
        $router->put('/destinasi/{id}', ['middleware' => ['auth'], 'uses'=> 'DestinasiController@update',]);
        $router->delete('/destinasi/{id}', ['middleware' => ['auth'], 'uses'=> 'DestinasiController@destroy',]);
    }
);