<?php

namespace Iocaste\Microservice\Api\Container\Concerns;

use Iocaste\Microservice\Api\Exceptions\RuntimeException;

/**
 * Trait HasJobs
 */
trait HasJobs
{
    /**
     * @inheritDoc
     */
    public function getJobNamespaceByResourceName($resourceName, $action): string
    {
        if (!$this->resolver->isResourceName($resourceName)) {
            throw new RuntimeException(
                'Cannot get job namespace because ' . $resourceName . ' is not a valid resource name.'
            );
        }

        return $this->resolver->getJobByResourceName($resourceName, $action);
    }
}
