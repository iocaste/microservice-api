<?php

namespace Iocaste\Microservice\Api\Http\Requests;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

use Iocaste\Microservice\Api\Contracts\Auth\AuthorizerInterface;
use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Neomerx\JsonApi\Exceptions\JsonApiException;

/**
 * Class ValidatedRequest
 */
abstract class ValidatedRequest implements ValidatesWhenResolved
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var MicroApiRequest
     */
    protected $microApiRequest;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ValidatedRequest constructor.
     *
     * @param Request $request
     * @param MicroApiRequest $microApiRequest
     * @param ContainerInterface $container
     */
    public function __construct(Request $request, MicroApiRequest $microApiRequest, ContainerInterface $container)
    {
        $this->request = $request;
        $this->microApiRequest = $microApiRequest;
        $this->container = $container;
    }

    /**
     * Authorize the request.
     *
     * @return void
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    abstract protected function authorize(): void;

    /**
     * Validate the query parameters.
     *
     * @return void
     * @throws JsonApiException
     */
    abstract protected function validateQuery(): void;

    /**
     * Validate the JSON API document.
     *
     * @return void
     * @throws JsonApiException
     */
    protected function validateDocument(): void
    {
        // no-op
    }

    /**
     * @inheritdoc
     */
    public function validateResolved(): void
    {
        $this->authorize();
        $this->validateQuery();
    }

    /**
     * @todo was getType
     *
     * @return string
     */
    public function getResourceNamespace(): string
    {
        return $this->microApiRequest->getResourceNamespace();
    }

    /**
     * Get the resource type that the request is for.
     *
     * @return string|null
     */
    public function getResourceName(): ?string
    {
        return $this->microApiRequest->getResourceName($this->request);
    }

    /**
     * @return AuthorizerInterface|null
     */
    protected function getAuthorizer(): ?AuthorizerInterface
    {
        // @todo Implement authorization
        return null;

        return $this->container->getAuthorizerByResourceName($this->getResourceType());
    }
}
