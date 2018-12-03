<?php

namespace Iocaste\Microservice\Api\Services;

use Closure;
use Illuminate\Contracts\Container\Container;
use Iocaste\Microservice\Api\Routing\ResourceRegistrar;

/**
 * Class MicroApiService
 */
class MicroApiService
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $version;

    /**
     * MicroApiService constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->version = 'v1';
    }

    /**
     * Register the routes for an API.
     *
     * @param $version
     * @param array $options
     * @param Closure $routes
     *
     * @return void
     */
    public function register($version, array $options, Closure $routes): void
    {
        /** @var ResourceRegistrar $registrar */
        $registrar = $this->container->make('micro-api.registrar');

        // Делает обращение к регистру ресурсов
        // Создает API объект, который оборачивает все рауты в главную группу
        $registrar->api($version, $options, $routes);
    }
}
