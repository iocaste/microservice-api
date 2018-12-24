<?php

namespace Iocaste\Microservice\Api\Contracts;

use Iocaste\Microservice\Api\Contracts\Auth\AuthorizerInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface as BaseContainerInterface;

/**
 * Interface ContainerInterface
 */
interface ContainerInterface extends BaseContainerInterface
{
    /**
     * Get a resource authorizer by JSON API type.
     *
     * @param $resourceName
     * @return AuthorizerInterface|null
     *      the authorizer, if there is one.
     */
    public function getAuthorizerByResourceName($resourceName): ?AuthorizerInterface;

    /**
     * @param $authorizerName
     *
     * @return AuthorizerInterface|null
     *      the authorizer, if there is one.
     */
    public function getAuthorizerByName($authorizerName): ?AuthorizerInterface;

    /**
     * @param $resourceName
     * @param $action
     *
     * @return mixed
     */
    public function getJobNamespaceByResourceName($resourceName, $action);
}
