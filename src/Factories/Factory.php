<?php

namespace Iocaste\Microservice\Api\Factories;

use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Iocaste\Microservice\Api\Container;
use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Contracts\Factory\FactoryInterface;
use Iocaste\Microservice\Api\Http\Responses\MicroResponse;
use Iocaste\Microservice\Api\Resource\ResourceRepository;
use Neomerx\JsonApi\Factories\Factory as BaseFactory;

/**
 * Class Factory
 */
class Factory extends BaseFactory implements FactoryInterface
{
    /**
     * @var IlluminateContainer
     */
    protected $container;

    /**
     * Factory constructor.
     *
     * @param IlluminateContainer $container
     */
    public function __construct(IlluminateContainer $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function createApiContainer(): Container
    {
        return new Container($this->container);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ResourceRepository
     */
    public function createResourceRepository(ContainerInterface $container): ResourceRepository
    {
        return new ResourceRepository($container);
    }

    public function createResponse(
        $schemaRepository,
        $errorRepository = null,
        $codecRepository = null,
        $parameters = null,
        $urlPrefix = null
    ) {
        return new MicroResponse($this, $schemaRepository, $errorRepository, $codecRepository, $parameters, $urlPrefix);
    }
}
