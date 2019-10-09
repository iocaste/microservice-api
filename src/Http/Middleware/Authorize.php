<?php

namespace Iocaste\Microservice\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Iocaste\Microservice\Api\Contracts\Auth\AuthorizerInterface;
use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Http\Requests\MicroApiRequest;

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
     * @var MicroApiRequest
     */
    protected $microApiRequest;

    /**
     * Authorize constructor.
     *
     * @param ContainerInterface $container
     * @param MicroApiRequest $microApiRequest
     */
    public function __construct(ContainerInterface $container, MicroApiRequest $microApiRequest)
    {
        $this->container = $container;
        $this->microApiRequest = $microApiRequest;
    }

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
        $this->authorizeRequest(
            $this->container->getAuthorizerByName($authorizer),
            $this->microApiRequest,
            $request
        );

        return $next($request);
    }

    /**
     * @param AuthorizerInterface $authorizer
     * @param MicroApiRequest $microApiRequest
     * @param $request
     *
     * @return void
     */
    protected function authorizeRequest(
        AuthorizerInterface $authorizer,
        MicroApiRequest $microApiRequest,
        Request$request
    ): void {
        //

        dd('test!');
    }
}
