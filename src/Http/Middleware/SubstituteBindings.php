<?php

namespace Iocaste\Microservice\Api\Http\Middleware;

use Closure;
use Iocaste\Microservice\Api\Contracts\Resource\ResourceInterface;
use Iocaste\Microservice\Api\Http\Requests\MicroApiRequest;
use Iocaste\Microservice\Api\Routing\ResourceRegistrar;
use Iocaste\Microservice\Api\Routing\RouteParameter;
use Iocaste\Microservice\Api\Exceptions\NotFoundException;

/**
 * Class BootMicroApi
 *
 * @see https://laracasts.com/discuss/channels/lumen/lumen-route-binding-or-binding-repo-to-implementation-based-on-route-param
 */
class SubstituteBindings
{
    /**
     * @var MicroApiRequest
     */
    protected $microApiRequest;

    /**
     * @var ResourceInterface
     */
    protected $resourceRepository;

    /**
     * SubstituteBindings constructor.
     *
     * @param MicroApiRequest $microApiRequest
     * @param ResourceInterface $resourceRepository
     */
    public function __construct(MicroApiRequest $microApiRequest, ResourceInterface $resourceRepository)
    {
        $this->microApiRequest = $microApiRequest;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Binds models to routes
     *
     * @param $request
     * @param Closure $next
     *
     * @throws NotFoundException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->microApiRequest->getResourceId()) {
            $this->bindResource($request);
        }

        return $next($request);
    }

    /**
     * @param $request
     *
     * @throws NotFoundException
     *
     * @return void
     */
    protected function bindResource($request): void
    {
        $name = $this->microApiRequest->getResourceName($request);
        $id = $this->microApiRequest->getResourceId();
        $record = $this->resourceRepository->getSingle($name, $id);

        if (! $record) {
            throw new NotFoundException();
        }

        RouteParameter::set($request, ResourceRegistrar::PARAM_RESOURCE_ID, $record);
    }
}
