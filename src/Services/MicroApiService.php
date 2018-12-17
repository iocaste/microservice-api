<?php

namespace Iocaste\Microservice\Api\Services;

use Closure;
use Illuminate\Contracts\Container\Container;
use Iocaste\Microservice\Api\Api\Api;
use Iocaste\Microservice\Api\Api\Repository;
use Iocaste\Microservice\Api\Http\Requests\MicroApiRequest;
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

    /**
     * @param string|null $version
     *
     * @return Api
     */
    public function getApi($version = null): Api
    {
        /** @var Repository $repository */
        $repository = $this->container->make(Repository::class);

        return $repository->createApi($version ?: $this->getDefaultApi());
    }

    /**
     * Get the JSON API request, if there is an inbound API handling the request.
     *
     * @return MicroApiRequest|null
     */
    public function getRequest(): ?MicroApiRequest
    {
        if (! $this->container->bound(Api::class)) {
            return null;
        }

        return $this->container->make('micro-api.request');
    }

    /**
     * Get either the request API or the default API.
     *
     * @return Api
     */
    public function getRequestApiOrDefault(): Api
    {
        return $this->getApi();
    }

    /**
     *
     * @return string
     */
    public function getDefaultApi(): string
    {
        return 'v1';
    }
}
