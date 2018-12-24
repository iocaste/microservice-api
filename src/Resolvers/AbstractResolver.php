<?php

namespace Iocaste\Microservice\Api\Resolvers;

use Iocaste\Microservice\Api\Contracts\Resolver\ResolverInterface;

/**
 * Class AbstractResolver
 */
abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $resources;

    /**
     * @var
     */
    protected $classes;

    /**
     * AbstractResolver constructor.
     *
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        $this->resources = $resources;
        $this->classes = $this->flip($resources);
    }

    /**
     * Convert the provided unit name and resource type into a fully qualified namespace.
     *
     * @param string $unit
     *      the JSON API unit name: Adapter, Authorizer, Schema, Validators
     * @param $resourceName
     *      the JSON API resource type.
     * @param $action
     * @return string
     */
    abstract protected function resolve($unit, $resourceName, $action = null): string;

    /**
     * @inheritDoc
     */
    public function isResourceName($resourceName): bool
    {
        return isset($this->resources[$resourceName]);
    }

    /**
     * @inheritdoc
     */
    public function getResourceName($class): ?string
    {
        if (!isset($this->classes[$class])) {
            return null;
        }

        return $this->classes[$class];
    }

    /**
     * @inheritdoc
     */
    public function getSchemaByResourceName($resourceName): string
    {
        return $this->resolve('Schema', $resourceName);
    }

    /**
     * @inheritdoc
     */
    public function getJobByResourceName($resourceName, $action): string
    {
        return $this->resolve('Job', $resourceName, $action);
    }

    /**
     * @todo Old: getAuthorizerByResourceType
     *
     * @inheritdoc
     */
    public function getAuthorizerByResourceName($resourceName): string
    {
        return $this->resolve('Authorizer', $resourceName);
    }

    /**
     * @param array $resources
     *
     * @return array
     */
    protected function flip(array $resources): array
    {
        return array_flip($resources);
    }
}
