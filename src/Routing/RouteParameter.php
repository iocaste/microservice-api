<?php

namespace Iocaste\Microservice\Api\Routing;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class RouteParameter
 */
class RouteParameter
{
    /**
     * Get a route parameter from the request.
     *
     * @param Request $request
     * @param string $param
     * @return boolean
     */
    public static function exists(Request $request, $param): bool
    {
        return Arr::exists($request->route()[2], $param);
    }

    /**
     * Get a route parameter from the request.
     *
     * @param Request $request
     * @param string $param
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(Request $request, $param, $default = null)
    {
        if (! $value = Arr::get($request->route()[2], $param, $default)) {
            $value = Arr::get($request->route()[1]['parameters'], $param, $default);
        }

        return $value;
    }

    /**
     * Set a route parameter on the request.
     *
     * @param Request $request
     * @param $param
     * @param $value
     *
     * @return void
     */
    public static function set(Request $request, $param, $value): void
    {
        /** @var array $route */
        $route = $request->route();
        $route[2][$param] = $value;
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });
    }

    /**
     * Forget a route parameter on the Request.
     *
     * @param Request $request
     * @param string $param
     */
    public static function forget(Request $request, $param): void
    {
        $route = $request->route();
        Arr::forget($route[2], $param);
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });
    }
}
