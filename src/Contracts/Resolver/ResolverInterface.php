<?php

namespace Iocaste\Microservice\Api\Contracts\Resolver;

/**
 * Interface ResolverInterface
 */
interface ResolverInterface
{
    /**
     * Does the supplied JSON API resource type exist?
     *
     * @param $resourceName
     * @return bool
     */
    public function isResourceName($resourceName): bool;

    /**
     * Get the JSON API resource type for the supplied domain record namespace.
     *
     * @param string $class
     * @return string|null
     */
    public function getResourceName($class): ?string;

    /**
     * @param $resourceName
     *
     * @return string
     */
    public function getSchemaByResourceName($resourceName): string;

    /**
     * @param $resourceName
     * @param $action
     *
     * @return string
     */
    public function getJobByResourceName($resourceName, $action): string;
}
