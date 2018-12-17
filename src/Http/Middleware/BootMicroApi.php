<?php

namespace Iocaste\Microservice\Api\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

/**
 * Class BootMicroApi
 */
class BootMicroApi
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Start JSON API support.
     *
     * This middleware:
     * - Loads the configuration for the named API that this request is being routed to.
     * - Registers the API in the service container.
     * - Triggers client/server content negotiation as per the JSON API spec.
     *
     * @param Request $request
     * @param Closure $next
     * @param $version
     *      the API version, as per your JSON API configuration.
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $version)
    {
        return $next($request);
    }
}
