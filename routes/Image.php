<?php

declare(strict_types=1);

/** @var Laravel\Lumen\Routing\Router $router */

$router->group(
    [
        'as' => 'images',
    ],
    function () use ($router) {
        $router->group(
            [
                'prefix' => 'images',
            ],
            function () use ($router) {
                // deletes
                $router->get(
                    '/deleted',
                    [
                        'as' => 'deleted',
                        'uses' => 'ImageDeleteController@deleted',
                    ]
                );
                $router->put(
                    '/{id}/restore',
                    [
                        'as' => 'restore',
                        'uses' => 'ImageDeleteController@restore',
                    ]
                );
                $router->delete(
                    '/{id}/purge',
                    [
                        'as' => 'purge',
                        'uses' => 'ImageDeleteController@purge',
                    ]
                );

                // resources
                $router->get(
                    '/',
                    [
                        'as' => 'index',
                        'uses' => 'ImageController@index',
                    ]
                );
                $router->post(
                    '/',
                    [
                        'as' => 'store',
                        'uses' => 'ImageController@store',
                    ]
                );
                $router->get(
                    '/{id}',
                    [
                        'as' => 'show',
                        'uses' => 'ImageController@show',
                    ]
                );
                $router->put(
                    '/{id}',
                    [
                        'as' => 'update',
                        'uses' => 'ImageController@update',
                    ]
                );
                $router->delete(
                    '/{id}',
                    [
                        'as' => 'destroy',
                        'uses' => 'ImageController@destroy',
                    ]
                );
            }
        );
    }
);