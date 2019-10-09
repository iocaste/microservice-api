<?php

namespace Iocaste\Microservice\Api;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Iocaste\Microservice\Api\Api\Repository;
use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Contracts\Factory\FactoryInterface;
use Iocaste\Microservice\Api\Contracts\Resource\ResourceInterface;
use Iocaste\Microservice\Api\Factories\Factory;
use Iocaste\Microservice\Api\Http\Middleware\Authorize;
use Iocaste\Microservice\Api\Http\Middleware\BootMicroApi;
use Iocaste\Microservice\Api\Http\Middleware\SubstituteBindings;
use Iocaste\Microservice\Api\Http\Requests\MicroApiRequest;
use Iocaste\Microservice\Api\Http\Responses\MicroResponse;
use Iocaste\Microservice\Api\Routing\ResourceRegistrar;
use Iocaste\Microservice\Api\Services\MicroApiService;
use Laravel\Lumen\Application;
use Neomerx\JsonApi\Contracts\Schema\SchemaFactoryInterface;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface as NeomerxFactoryInterface;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootMiddleware();
        $this->bootResponseMacro();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerFactory();
        $this->registerService();
        $this->registerInboundRequest();
        $this->registerRouteRegistrar();
        $this->bindApiRepository();
    }

    /**
     * Register package middleware.
     *
     * @return void
     */
    protected function bootMiddleware(): void
    {
        $this->app->routeMiddleware([
            // Core micro api middleware class
            'micro-api' => BootMicroApi::class,

            // Binds resource ids or uuids to Models
            'micro-api.bindings' => SubstituteBindings::class,

            // General authorization middleware
            'micro-api.auth' => Authorize::class,
        ]);
    }

    /**
     * Register a response macro.
     *
     * @return void
     */
    protected function bootResponseMacro(): void
    {
        response()->macro('microApi', function ($api = null) {
            return MicroResponse::create($api);
        });
    }

    /**
     * Bind Api Factory
     */
    protected function registerFactory(): void
    {
        $this->app->singleton(Factory::class, function (Application $app) {
            return new Factory($app);
        });

        $this->app->alias(Factory::class, FactoryInterface::class);
        $this->app->alias(Factory::class, NeomerxFactoryInterface::class);
        $this->app->alias(Factory::class, SchemaFactoryInterface::class);
    }

    /**
     * Bind the JSON API service as a singleton.
     */
    protected function registerService(): void
    {
        $this->app->singleton(MicroApiService::class);
        $this->app->alias(MicroApiService::class, 'micro-api');
        $this->app->alias(MicroApiService::class, 'micro-api.service');
    }

    /**
     * Bind the inbound request services so they can be type-hinted in controllers and authorizers.
     */
    protected function registerInboundRequest(): void
    {
        $this->app->singleton(MicroApiRequest::class);
        $this->app->alias(MicroApiRequest::class, 'micro-api.request');

        $this->app->bind(ResourceInterface::class, function () {
            return micro_api()->getResourceRepository();
        });

        $this->app->bind(ContainerInterface::class, function () {
            return micro_api()->getContainer();
        });
    }

    /**
     * Bind an alias for the route registrar.
     */
    protected function registerRouteRegistrar(): void
    {
        $this->app->alias(ResourceRegistrar::class, 'micro-api.registrar');
    }

    /**
     * Bind the API repository as a singleton.
     */
    protected function bindApiRepository(): void
    {
        $this->app->singleton(Repository::class);
    }
}
