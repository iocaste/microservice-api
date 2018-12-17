<?php

namespace Iocaste\Microservice\Api\Routing;

use Illuminate\Support\Fluent;
use Laravel\Lumen\Routing\Router as LumenRouter;

/**
 * Class ResourceGroup
 */
class ResourceGroup
{
    use Registrable;

    protected const DEFAULT_ACTIONS = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
    ];

    protected const METHOD_MAP = [
        'index' => 'get',
        'show' => 'get',
        'store' => 'post',
        'update' => 'patch',
        'destroy' => 'delete',
    ];

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var null
     */
    protected $resolver;

    /**
     * ResourceGroup constructor.
     *
     * @param $resourceName
     * @param $resolver
     * @param Fluent $options
     */
    public function __construct($resourceName, $resolver, Fluent $options)
    {
        $this->resourceName = $resourceName;
        $this->resolver = $resolver;
        $this->options = $options;
    }

    /**
     * @param LumenRouter $router
     *
     * @return void
     */
    public function add(LumenRouter $router): void
    {
        $router->group($this->getAction(), function (LumenRouter $router) {
            /** Creates primary resource routes. */
            $router->group([], function ($router) {
                $this->addResourceRoutes($router);
            });

            /** Creates primary resource relationship Routes */
            // $this->addRelationshipRoutes($router);
        });
    }

    /**
     * @return array
     */
    protected function getAction(): array
    {
        return [
            'middleware' => $this->getMiddleware(),
            'as' => $this->resourceName, // . '.',
            'prefix' => $this->resourceName,
        ];
    }

    /**
     * Returns list of middleware to attach
     *
     * @return array
     */
    protected function getMiddleware(): array
    {
        return (array) $this->options->get('middleware');
    }

    /**
     * Returns list of allowed resource actions
     *
     * @return array
     */
    protected function getResourceActions(): array
    {
        return $this->diffActions(self::DEFAULT_ACTIONS, $this->options);
    }

    /**
     * Sends command to lumen router to create route
     *
     * @param LumenRouter $router
     * @param $action
     *
     * @return LumenRouter
     */
    protected function setResourceRoute(LumenRouter $router, $action): LumenRouter
    {
        return $this->createRoute(
            $router,
            $this->mapRouteMethod($action),
            $this->getRouteUrl($action),
            $this->mapRouteAction($action)
        );
    }

    /**
     * @param $action
     *
     * @return string
     */
    protected function getRouteUrl($action): string
    {
        if (\in_array($action, ['index', 'store'], true)) {
            return $this->getBaseUrl();
        }

        return $this->getResourceUrl();
    }

    /**
     * Adds current resource REST routes
     *
     * @param LumenRouter $router
     */
    protected function addResourceRoutes(LumenRouter $router): void
    {
        foreach ($this->getResourceActions() as $action) {
            $this->setResourceRoute($router, $action);
        }
    }

    /**
     * Gets router method name.
     *
     * @param $action
     *
     * @return string
     */
    protected function mapRouteMethod($action): string
    {
        return self::METHOD_MAP[$action];
    }

    /**
     * Formats action to be compatible with lumen router
     * @link https://lumen.laravel.com/docs/5.7/routing#named-routes
     * @param $action
     *
     * @return array
     */
    protected function mapRouteAction($action): array
    {
        // dd($action)
        return [
            'uses' => $this->getControllerAction($action),
            'as' => $action,
        ];
    }

    /**
     * @param $action
     *
     * @return string
     */
    protected function getControllerAction($action): string
    {
        return sprintf('%s@%s', $this->getController(), $action);
    }
}
