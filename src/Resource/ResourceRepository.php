<?php

namespace Iocaste\Microservice\Api\Resource;

use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Contracts\Resource\ResourceInterface;

/**
 * Class ResourceRepository
 */
class ResourceRepository implements ResourceInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ResourceRepository constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getList($resourceName)
    {
        return $this->dispatchJob($resourceName, 'index');
    }

    /**
     * @inheritDoc
     */
    public function getSingle($resourceName, $resourceId)
    {
        return $this->dispatchJob($resourceName, 'show', [
            $resourceId,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function store($resourceName, $document)
    {
        return $this->dispatchJob($resourceName, 'store', [
            $document,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function update($resourceName, $record, $document)
    {
        return $this->dispatchJob($resourceName, 'update', [
            $record,
            $document,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function destroy($resourceName, $record)
    {
        return $this->dispatchJob($resourceName, 'destroy', [
            $record,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getJobNamespace($resourceName, $action)
    {
        return $this->container->getJobNamespaceByResourceName($resourceName, $action);
    }

    /**
     * @param $resourceName
     * @param $action
     * @param $parameters
     *
     * @return mixed
     */
    protected function dispatchJob($resourceName, $action, $parameters = [])
    {
        $job = $this->getJobNamespace($resourceName, $action);

        return dispatch_now(new $job(...$parameters));
    }
}
