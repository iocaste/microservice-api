<?php

namespace Iocaste\Microservice\Api\Contracts;

use Neomerx\JsonApi\Contracts\Schema\ContainerInterface as BaseContainerInterface;

/**
 * Interface ContainerInterface
 */
interface ContainerInterface extends BaseContainerInterface
{
    /**
     * @param $resourceName
     * @param $action
     *
     * @return mixed
     */
    public function getJobByResourceName($resourceName, $action);
}
