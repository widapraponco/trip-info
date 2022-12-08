<?php

$router->group(
    [
        'as' => 'restoran',
    ],
    function () use ($router) {
        $router->post('/restoran', ['middleware' => ['auth'], 'uses'=> 'restoranController@create',]);
        $router->get('/restoran', ['uses' => 'restoranController@find',]);
        $router->get('/restoran/{id}', ['middleware' => ['auth'], 'uses' => 'restoranController@findById',]);
        $router->put('/restoran/{id}', ['middleware' => ['auth'], 'uses'=> 'restoranController@update',]);
        $router->delete('/restoran/{id}', ['middleware' => ['auth'], 'uses'=> 'restoranController@destroy',]);
    }
);
