<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'events',
    ],
    function () use ($router) {
        $router->group(
            [
                'prefix' => 'events',
            ],
            function () use ($router) {
                // deletes
                $router->get(
                    '/deleted',
                    [
                        'as' => 'deleted',
                        'uses' => 'EventController@deleted',
                    ]
                );
                $router->put(
                    '/{id}/restore',
                    [
                        'as' => 'restore',
                        'uses' => 'EventController@restore',
                    ]
                );
                $router->delete(
                    '/{id}/purge',
                    [
                        'as' => 'purge',
                        'uses' => 'EventController@purge',
                    ]
                );

                // resources
                $router->get(
                    '/',
                    [
                        'as' => 'index',
                        'uses' => 'EventController@index',
                    ]
                );
                $router->post(
                    '/',
                    [
                        'as' => 'store',
                        'uses' => 'EventController@store',
                    ]
                );
                $router->get(
                    '/{id}',
                    [
                        'as' => 'show',
                        'uses' => 'EventController@show',
                    ]
                );
                $router->put(
                    '/{id}',
                    [
                        'as' => 'update',
                        'uses' => 'EventController@update',
                    ]
                );
                $router->delete(
                    '/{id}',
                    [
                        'as' => 'destroy',
                        'uses' => 'EventController@destroy',
                    ]
                );
            }
        );
    }
);
