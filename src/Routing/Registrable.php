<?php

namespace Iocaste\Microservice\Api\Routing;

use ArrayAccess;
use Laravel\Lumen\Routing\Router;

/**
 * Trait Registrable
 */
trait Registrable
{
    /**
     * @param array $defaults
     * @param array|ArrayAccess $options
     * @return array
     */
    protected function diffActions(array $defaults, $options): array
    {
        if (isset($options['only'])) {
            return array_intersect($defaults, (array) $options['only']);
        }

        if (isset($options['except'])) {
            return array_diff($defaults, (array) $options['except']);
        }

        return $defaults;
    }

    /**
     * Attaches route to lumen router
     *
     * @param Router $router
     * @param $method
     * @param $uri
     * @param array $action
     *
     * @return Router
     */
    protected function createRoute(Router $router, $method, $uri, array $action): ?Router
    {
        // dd(1);
//        dd([
//            $method,
//            $uri,
//            $action
//        ]);

//        $route = $router->get('/', [
//            'uses' => 'Iocaste\Microservice\Api\Http\Controllers\MicroApiController@index',
//            'as' => 'index'
//        ]);

        $route = $router->{$method}($uri, $action);

        // dd($route);

        return $route;
    }
}
