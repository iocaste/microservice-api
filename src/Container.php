<?php

namespace Iocaste\Microservice\Api;

use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    /**
     * Maps router action to job prefix
     */
    protected const JOB_NAMES = [
        'index' => 'Get',
        'show' => 'Get',
        'store' => 'Store',
        'update' => 'Update',
        'destroy' => 'Destroy',
    ];

    /**
     * Maps router action to declension
     */
    protected const JOB_RESOURCE_FORMS = [
        'index' => 'plural',
        'show' => 'singular',
        'store' => 'singular',
        'update' => 'singular',
        'destroy' => 'singular',
    ];

    /**
     * @var IlluminateContainer
     */
    protected $container;

    /**
     * Factory constructor.
     *
     * @param IlluminateContainer $container
     */
    public function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function hasSchema($resourceObject): bool
    {
        return true;
        // return is_object($resourceObject) === true &&
        //    $this->hasProviderMapping($this->getResourceType($resourceObject)) === true;
    }

    /**
     * @inheritDoc
     */
    public function getSchema($resourceObject): SchemaInterface
    {
        return $this->getSchemaByType(get_class($resourceObject));
    }

    /**
     * @inheritDoc
     */
    public function getSchemaByType(string $type): SchemaInterface
    {
        $resourceType = $this->getResourceType($type);

        return $this->getSchemaByResourceType($resourceType);
    }

    /**
     * @inheritDoc
     */
    public function getSchemaByResourceType(string $resourceType): SchemaInterface
    {
        if ($this->hasCreatedSchema($resourceType)) {
            return $this->getCreatedSchema($resourceType);
        }

        if (!$this->resolver->isResourceType($resourceType)) {
            throw new RuntimeException("Cannot create a schema because $resourceType is not a valid resource type.");
        }

        $className = $this->resolver->getSchemaByResourceType($resourceType);
        $schema = $this->createSchemaFromClassName($className);
        $this->setCreatedSchema($resourceType, $schema);

        return $schema;
    }


    // -----------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------


    /**
     * @param $resourceName
     * @param $action
     *
     * @return string
     */
    public function getJobByResourceName($resourceName, $action): string
    {
        $resource = ucfirst(str_singular($resourceName));
        $job = $this->getJobName($resourceName, $action);

        // @todo Get namespace from config

        return "App\Domains\\{$resource}\\Jobs\\{$job}";
    }

    /**
     * @param $resourceName
     * @param $action
     *
     * @return string
     */
    protected function getJobName($resourceName, $action): string
    {
        $name = self::JOB_NAMES[$action];
        $noun = ucfirst($this->getDeclension($resourceName, $action));

        return $name . ucfirst($noun);
    }

    /**
     * @param $resourceName
     * @param $action
     *
     * @return string
     */
    protected function getDeclension($resourceName, $action): string
    {
        $form = self::JOB_RESOURCE_FORMS[$action];

        if ($form === 'singular') {
            return str_singular($resourceName);
        }

        return str_plural($resourceName);
    }
}
