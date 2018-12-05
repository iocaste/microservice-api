<?php

namespace Iocaste\Microservice\Api;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Iocaste\Microservice\Api\Routing\ResourceRegistrar;
use Iocaste\Microservice\Api\Services\MicroApiService;
use Laravel\Lumen\Routing\Router as LumenRouter;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @param LumenRouter $router
     *
     * @return void
     */
    public function boot(LumenRouter $router): void
    {
        // Подгружает миддлвер
        $this->bootMiddleware($router);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->bindService();
        $this->bindRouteRegistrar();
    }

    /**
     * Register package middleware.
     *
     * @param LumenRouter $router
     *
     * @return void
     */
    protected function bootMiddleware(LumenRouter $router): void
    {
        // Core micro api middleware class
        // $router->aliasMiddleware('micro-api', BootMicroApi::class);

        // Binds resource ids or uuids to Models
        // $router->aliasMiddleware('micro-api.bindings', SubstituteBindings::class);

        // General authorization middleware
        // $router->aliasMiddleware('micro-api.auth', Authorize::class);
    }

    /**
     * Bind the JSON API service as a singleton.
     */
    protected function bindService(): void
    {
        $this->app->singleton(MicroApiService::class);
        $this->app->alias(MicroApiService::class, 'micro-api');
        $this->app->alias(MicroApiService::class, 'micro-api.service');
    }

    /**
     * Bind an alias for the route registrar.
     */
    protected function bindRouteRegistrar(): void
    {
        $this->app->alias(ResourceRegistrar::class, 'micro-api.registrar');
    }
}
