<?php

namespace Iocaste\Microservice\Api\Container\Concerns;

use Iocaste\Microservice\Api\Exceptions\RuntimeException;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;

/**
 * Trait HasSchemas
 */
trait HasSchemas
{
    /**
     * @var array
     */
    protected $createdSchemas = [];

    /**
     * @param string $resourceName
     * @return bool
     */
    protected function hasCreatedSchema($resourceName): bool
    {
        return isset($this->createdSchemas[$resourceName]);
    }

    /**
     * @param $resourceName
     *
     * @return SchemaInterface
     */
    protected function getCreatedSchema($resourceName): SchemaInterface
    {
        return $this->createdSchemas[$resourceName];
    }

    /**
     * @param string $resourceName
     * @param SchemaInterface $schema
     * @return void
     */
    protected function setCreatedSchema($resourceName, SchemaInterface $schema): void
    {
        $this->createdSchemas[$resourceName] = $schema;
    }

    /**
     * @param mixed $resourceObject
     *
     * @return bool
     */
    public function hasSchema($resourceObject): bool
    {
        return is_object($resourceObject) === true && $this->getResourceName($resourceObject) !== null;
    }

    /**
     * @inheritDoc
     */
    public function getSchema($resourceObject): ?SchemaInterface
    {
        return $this->getSchemaByType(get_class($resourceObject));
    }

    /**
     * @inheritDoc
     */
    public function getSchemaByType(string $class): SchemaInterface
    {
        $resourceName = $this->getResourceName($class);

        return $this->getSchemaByResourceName($resourceName);
    }

    /**
     * @param string $resourceName
     *
     * @return SchemaInterface
     */
    public function getSchemaByResourceName(string $resourceName): SchemaInterface
    {
        if ($this->hasCreatedSchema($resourceName)) {
            return $this->getCreatedSchema($resourceName);
        }

        if (!$this->resolver->isResourceName($resourceName)) {
            throw new RuntimeException(
                'Cannot create a schema because ' . $resourceName . ' is not a valid resource name.'
            );
        }

        // Example: 'App\Domains\Comment\Schema'
        $class = $this->resolver->getSchemaByResourceName($resourceName);

        $schema = $this->createSchemaFromClass($class);
        $this->setCreatedSchema($resourceName, $schema);

        return $schema;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaByResourceType(string $resourceName): SchemaInterface
    {
        return $this->getSchemaByResourceName($resourceName);
    }

    /**
     * @param string $class
     * @return SchemaInterface
     */
    protected function createSchemaFromClass($class): SchemaInterface
    {
        $schema = $this->create($class);

        if (! $schema instanceof SchemaInterface) {
            throw new RuntimeException('Class [' . $class . '] is not a schema provider.');
        }

        return $schema;
    }
}
