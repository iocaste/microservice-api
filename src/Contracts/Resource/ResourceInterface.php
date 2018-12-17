<?php

namespace Iocaste\Microservice\Api\Contracts\Resource;

/**
 * Interface ResourceInterface
 */
interface ResourceInterface
{
    /**
     * Get list of records by supplied parameters
     *
     * @param $resourceName
     *
     * @return mixed
     */
    public function getList($resourceName);

    /**
     * Get single record by supplied parameters
     *
     * @param $resourceName
     * @param $resourceId
     *
     * @return mixed
     */
    public function getSingle($resourceName, $resourceId);

    /**
     * Create a domain record using data from the supplied resource object.
     *
     * @param $resourceName
     * @param $document
     *
     * @return mixed
     */
    public function store($resourceName, $document);

    /**
     * Update a domain record with data from the supplied resource object.
     *
     * @param $resourceName
     * @param $record
     * @param $document
     *
     * @return mixed
     */
    public function update($resourceName, $record, $document);

    /**
     * Delete a domain record.
     *
     * @param $resourceName
     * @param $record
     *
     * @return mixed
     */
    public function destroy($resourceName, $record);

    /**
     * Get job for resource type and action
     *
     * @param $resourceName
     * @param $action
     *
     * @return mixed
     */
    public function getJobNamespace($resourceName, $action);
}
