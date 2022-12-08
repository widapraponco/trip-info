<?php

$router->group(
    [
        'as' => 'penginapan',
    ],
    function () use ($router) {
        $router->post('/penginapan', ['middleware' => ['auth'], 'uses'=> 'penginapanController@create',]);
        $router->get('/penginapan', ['uses' => 'penginapanController@find',]);
        $router->get('/penginapan/{id}', ['middleware' => ['auth'], 'uses' => 'penginapanController@findById',]);
        $router->put('/penginapan/{id}', ['middleware' => ['auth'], 'uses'=> 'penginapanController@update',]);
        $router->delete('/penginapan/{id}', ['middleware' => ['auth'], 'uses'=> 'penginapanController@destroy',]);
    }
);