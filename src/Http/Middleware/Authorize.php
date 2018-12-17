<?php

namespace Iocaste\Microservice\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

/**
 * Class Authorize
 */
class Authorize
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Handle the request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $authorizer
     * @throws AuthorizationException
     * @throws AuthenticationException
     * @return mixed
     */
    public function handle($request, Closure $next, $authorizer)
    {
//        $this->authorizeRequest(
//            $this->container->getAuthorizerByName($authorizer),
//            $this->jsonApiRequest,
//            $request
//        );

        return $next($request);
    }
}
