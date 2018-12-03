<?php

namespace Iocaste\Microservice\Api\Routing;

use Illuminate\Support\Fluent;
use Laravel\Lumen\Routing\Router;

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
        'destroy'
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
     * @var Fluent
     */
    protected $options;

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
     * @param Router $router
     *
     * @return void
     */
    public function add(Router $router): void
    {
        $router->group($this->getAction(), function (Router $router) {
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
            'as' => $this->resourceName . '.',
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
     * @param Router $router
     * @param $action
     *
     * @return mixed
     */
    protected function setResourceRoute(Router $router, $action)
    {
        return $this->createRoute(
            $router,
            'get',
            '/',
            [
                'uses' => 'Iocaste\Microservice\Api\Http\Controllers\MicroApiController@index',
                'as' => 'index'
            ]
        );
    }

    /**
     * Adds current resource REST routes
     *
     * @param Router $router
     */
    protected function addResourceRoutes(Router $router): void
    {
        foreach ($this->getResourceActions() as $action) {
            $this->setResourceRoute($router, $action);
        }
    }
}
