<?php

$router->group(
    [
        'as' => 'agentravel',
    ],
    function () use ($router) {
        $router->post('/agentravel', ['uses'=> 'AgenTravelController@create',]);
        $router->get('/agentravel', ['uses' => 'AgenTravelController@index',]);
        $router->get('/agentravel/{id}', ['uses' => 'AgenTravelController@findById',]);
        $router->put('/agentravel/{id}', ['uses'=> 'AgenTravelController@update',]);
        $router->delete('/agentravel/{id}', ['uses'=> 'AgenTravelController@destroy',]);
    }
);