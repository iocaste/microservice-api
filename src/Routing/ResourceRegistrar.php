<?php

namespace Iocaste\Microservice\Api\Routing;

use Closure;
use Laravel\Lumen\Routing\Router;
use Iocaste\Microservice\Api\Routing\Router as MicroRouter;

/**
 * Class ResourceRegistrar
 */
class ResourceRegistrar
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * ResourceRegistrar constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param $version
     * @param array $options
     * @param Closure $routes
     * @return void
     */
    public function api($version, array $options, Closure $routes): void
    {
        $api = null;

        // $as = $url->getName();
        $as = 'api:v1:';

        // $prefix = $url->getNamespace();
        $prefix = '/api/v1';

        $this->router->group([
            // 'middleware' => ["micro-api:{$version}", 'micro-api.bindings'],
            'as' => $as,
            'prefix' => $prefix,
        ], function () use ($api, $options, $routes) {
            $microRouter = new MicroRouter($this->router, $api, $options);

            $this->router->group($options, function () use ($api, $microRouter, $routes) {
                $routes($microRouter, $this->router);
            });
        });
    }
}
