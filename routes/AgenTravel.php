<?php

$router->group(
    [
        'as' => 'destinasi',
    ],
    function () use ($router) {
        $router->post('/agentravel', ['middleware' => ['auth'], 'uses'=> 'AgenTravelController@create',]);
        $router->get('/agentravel', ['uses' => 'DestinasiController@find',]);
        $router->get('/agentravel/{id}', ['middleware' => ['auth'], 'uses' => 'AgenTravelController@findById',]);
        $router->put('/agentravel/{id}', ['middleware' => ['auth'], 'uses'=> 'AgenTravelController@update',]);
        $router->delete('/agentravvel/{id}', ['middleware' => ['auth'], 'uses'=> 'AgenTravelController@destroy',]);
    }
);