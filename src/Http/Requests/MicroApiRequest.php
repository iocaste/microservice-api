<?php

namespace Iocaste\Microservice\Api\Http\Requests;

use Illuminate\Http\Request;
use Iocaste\Microservice\Api\Routing\ResourceRegistrar;
use Iocaste\Microservice\Api\Routing\RouteParameter;

/**
 * Class MicroApiRequest
 */
class MicroApiRequest
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string|null
     */
    private $resourceId;

    /**
     * @var null
     */
    private $parameters;

    /**
     * MicroApiRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the domain record full namespace that is subject of the request.
     *
     * @return string
     */
    public function getResourceNamespace(): string
    {
        dd('1');

        return 'App\Data\Models\Comment\Comment';
    }

    /**
     * @param mixed $request
     * @return string|null
     */
    public function getResourceName($request): ?string
    {
        return RouteParameter::get($request, ResourceRegistrar::PARAM_RESOURCE_NAME);
    }

    /**
     * Get resource identifier
     * @return string|null
     */
    public function getResourceId(): ?string
    {
        if ($this->resourceId === null) {
            $this->resourceId = $this->request->route(ResourceRegistrar::PARAM_RESOURCE_ID) ?: false;
        }

        return $this->resourceId ?: null;
    }

    /**
     * @return null
     */
    public function getParameters()
    {
        if (! $this->parameters) {
            // return $this->parameters = $this->parseParameters();
            return null;
        }

        return $this->parameters;
    }
}
