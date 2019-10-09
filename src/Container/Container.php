<?php

namespace Iocaste\Microservice\Api\Container;

use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Iocaste\Microservice\Api\Contracts\Resolver\ResolverInterface;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    use Concerns\HasSchemas,
        Concerns\HasFeatures,
        Concerns\HasJobs,
        Concerns\HasAuthorizers;

    /**
     * @var IlluminateContainer
     */
    protected $container;

    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * Container constructor.
     *
     * @param IlluminateContainer $container
     * @param ResolverInterface $resolver
     */
    public function __construct(IlluminateContainer $container, ResolverInterface $resolver)
    {
        $this->container = $container;
        $this->resolver = $resolver;
    }

    /**
     * Get the JSON API resource name for the provided PHP name.
     *
     * @param $class
     *
     * @return string|null
     */
    protected function getResourceName($class): ?string
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (! $resourceName = $this->resolver->getResourceName($class)) {
            return null;
            // throw new RuntimeException('No MicroApi resource name registered for PHP class ' . $class);
        }

        return $resourceName;
    }

    /**
     * @inheritDoc
     */
    protected function create($class)
    {
        if (class_exists($class) || $this->container->bound($class)) {
            return $this->container->make($class);
        }

        return null;
    }
}
