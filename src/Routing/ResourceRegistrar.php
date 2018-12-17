<?php

namespace Iocaste\Microservice\Api\Routing;

use Closure;
use Iocaste\Microservice\Api\Api\Repository;
use Iocaste\Microservice\Api\Routing\Router as MicroRouter;
use Laravel\Lumen\Routing\Router as LumenRouter;

/**
 * Class ResourceRegistrar
 */
class ResourceRegistrar
{
    public const PARAM_RESOURCE_NAME = 'resource_name';

    public const PARAM_RESOURCE_ID = 'record';

    /**
     * @var LumenRouter
     */
    protected $router;

    /**
     * @var Repository
     */
    protected $apiRepository;

    /**
     * ResourceRegistrar constructor.
     *
     * @param LumenRouter $router
     * @param Repository $apiRepository
     */
    public function __construct(LumenRouter $router, Repository $apiRepository)
    {
        $this->router = $router;
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param $version
     * @param array $options
     * @param Closure $routes
     * @return void
     */
    public function api($version, array $options, Closure $routes): void
    {
        $api = $this->apiRepository->createApi($version);
        $url = $api->getUrl();

        $this->router->group([
            'middleware' => ["micro-api:{$version}", 'micro-api.bindings'],
            'as' => $url->getName(),
            'prefix' => $url->getNamespace(),
        ], function () use ($api, $options, $routes) {
            $microRouter = new MicroRouter($this->router, $api, $options);

            $this->router->group($options, function () use ($api, $microRouter, $routes) {
                $routes($microRouter, $this->router);
            });
        });
    }
}
