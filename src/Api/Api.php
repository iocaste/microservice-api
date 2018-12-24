<?php

namespace Iocaste\Microservice\Api\Api;

use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Factories\Factory;
use Iocaste\Microservice\Api\Http\Responses\MicroResponse;
use Iocaste\Microservice\Api\Resource\ResourceRepository;

use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

// use Neomerx\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;

/**
 * Class Api
 */
class Api
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var
     */
    protected $resolver;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var bool
     */
    protected $useEloquent;

    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @var ResourceRepository|null
     */
    protected $resourceRepository;

    /**
     * Api constructor.
     *
     * @param Factory $factory
     * @param $resolver
     * @param $version
     * @param Url $url
     * @param bool $useEloquent
     */
    public function __construct(Factory $factory, $resolver, $version, Url $url, $useEloquent = true)
    {
        $this->factory = $factory;
        $this->resolver = $resolver;
        $this->version = $version;
        $this->url = $url;
        $this->useEloquent = $useEloquent;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->resourceRepository = null;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isEloquent(): bool
    {
        return $this->useEloquent;
    }

    /**
     * @return \Iocaste\Microservice\Api\Container\Container|ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        if (! $this->container) {
            $this->container = $this->factory->createApiContainer($this->resolver);
        }

        return $this->container;
    }

    /**
     * @return ResourceRepository|null
     */
    public function getResourceRepository(): ?ResourceRepository
    {
        if (! $this->resourceRepository) {
            $this->resourceRepository = $this->factory->createResourceRepository(
                $this->getContainer()
            );
        }

        return $this->resourceRepository;
    }

    /**
     * Create a responses helper for this API.
     *
     * @param EncodingParametersInterface|null $parameters
     * @return MicroResponse
     */
    public function response($parameters = null): MicroResponse
    {
        return $this->factory->createResponse(
            $this->getContainer(),
            $parameters,
            (string) $this->getUrl()
        );
    }
}
