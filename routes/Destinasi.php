<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'destinasis',
    ],
    function () use ($router) {
        $router->group(
            [
                'prefix' => 'destinasis',
            ],
            function () use ($router) {
                // deletes
                $router->get(
                    '/deleted',
                    [
                        'as' => 'deleted',
                        'uses' => 'DestinasiDeleteController@deleted',
                    ]
                );
                $router->put(
                    '/{id}/restore',
                    [
                        'as' => 'restore',
                        'uses' => 'DestinasiDeleteController@restore',
                    ]
                );
                $router->delete(
                    '/{id}/purge',
                    [
                        'as' => 'purge',
                        'uses' => 'DestinasiDeleteController@purge',
                    ]
                );

                // resources
                $router->get(
                    '/',
                    [
                        'as' => 'index',
                        'uses' => 'DestinasiController@index',
                    ]
                );
                $router->post(
                    '/',
                    [
                        'as' => 'store',
                        'uses' => 'DestinasiController@store',
                    ]
                );
                $router->get(
                    '/{id}',
                    [
                        'as' => 'show',
                        'uses' => 'DestinasiController@show',
                    ]
                );
                $router->put(
                    '/{id}',
                    [
                        'as' => 'update',
                        'uses' => 'DestinasiController@update',
                    ]
                );
                $router->delete(
                    '/{id}',
                    [
                        'as' => 'destroy',
                        'uses' => 'DestinasiController@destroy',
                    ]
                );
            }
        );
    }
);
