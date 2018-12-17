<?php

namespace Iocaste\Microservice\Api\Routing;

use ArrayAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Iocaste\Microservice\Api\Http\Controllers\MicroApiController;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Router as LumenRouter;

/**
 * Trait Registrable
 */
trait Registrable
{
    /**
     * @var Fluent
     */
    protected $options;

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return '/';
    }

    /**
     * @return string
     */
    protected function getResourceUrl(): string
    {
        return sprintf('%s/{%s}', $this->getBaseUrl(), ResourceRegistrar::PARAM_RESOURCE_ID);
    }

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
     * @param LumenRouter $router
     * @param $method
     * @param $uri
     * @param array $action
     *
     * @return LumenRouter
     */
    protected function createRoute(LumenRouter $router, $method, $uri, array $action): LumenRouter
    {
        $action['parameters'] = [
            ResourceRegistrar::PARAM_RESOURCE_NAME => $this->resourceName,
        ];

        // dd($uri);
        return $router->{$method}($uri, $action);

        // dd($action);

        // return $request->rou

//        dd($action);
//        if ($action == 'store') {
//            dd($route);
//        }
        // dd($route);

        // dd($this->resourceName);

        // dd($this->resourceName);
        // $route->defaults();

        // return $route;
    }

    /**
     * Returns controller name
     *
     * @return string
     */
    protected function getController(): string
    {
        // If controller was passed to override default one
        if (\is_string($controller = $this->options->get('controller'))) {
            return $controller;
        }

        // If controller was disabled, use our default one
        if (true !== $controller) {
            return $this->options['controller'] = '\\' . MicroApiController::class;
        }

        // If true, create controller from resource name
        return $this->options['controller'] = Str::studly($this->resourceName) . 'Controller';
    }
}
