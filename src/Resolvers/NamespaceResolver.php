<?php

namespace Iocaste\Microservice\Api\Resolvers;

use Iocaste\Microservice\Api\Exceptions\RuntimeException;

/**
 * Class NamespaceResolver
 */
class NamespaceResolver extends AbstractResolver
{
    protected const ALLOWED_UNITS = [
        'Feature',
        'Job',
        'Authorizer',
        'Schema',
        'Validator',
    ];

    /**
     * Maps router action to job prefix
     */
    protected const JOB_PREFIXES = [
        'index' => 'Get',
        'show' => 'Get',
        'store' => 'Store',
        'update' => 'Update',
        'destroy' => 'Destroy',
    ];

    /**
     * Map router action to feature prefix
     */
    protected const FEATURE_PREFIXES = [
        'index' => 'GetsListOf',
        'show' => 'GetsSingle',
        'store' => 'Stores',
        'update' => 'Updates',
        'destroy' => 'Destroys',
    ];

    /**
     * Maps router action to declension
     */
    protected const ACTION_RESOURCE_FORMS = [
        'index' => 'plural',
        'show' => 'singular',
        'store' => 'singular',
        'update' => 'singular',
        'destroy' => 'singular',
    ];

    /**
     * @var array
     */
    protected $namespaces;

    /**
     * NamespaceResolver constructor.
     *
     * @param array $namespaces
     * @param array $resources
     */
    public function __construct(array $namespaces, array $resources)
    {
        parent::__construct($resources);

        $this->namespaces = $namespaces;
    }

    /**
     * @todo Old: getAuthorizerByName
     *
     * @inheritdoc
     */
    public function getAuthorizerByName($authorizerName): string
    {

    }

    /**
     * @param $belongsTo
     *
     * @return string
     */
    protected function getRootNamespace($belongsTo): string
    {
        return $this->namespaces[$belongsTo];
    }

    /**
     * @inheritDoc
     */
    protected function resolve($unit, $resourceName, $action = null): string
    {
        if (! in_array($unit, self::ALLOWED_UNITS, true)) {
            throw new RuntimeException('No support for [' . $unit . '] type');
        }

        $name = studly_case(str_singular($resourceName));

        return $this->toNamespace($unit, $name, $action);
    }

    /**
     * @param $unit
     * @param $name
     * @param null $action
     *
     * @return string
     */
    protected function toNamespace($unit, $name, $action = null): string
    {
        if (in_array($unit, ['Authorizer', 'Validator', 'Schema'])) {
            $rootNamespace = $this->getRootNamespace('domains');

            return "{$rootNamespace}\\{$name}\\{$unit}";
        }

        if ($unit === 'Job') {
            $rootNamespace = $this->getRootNamespace('domains');
            $jobName = $this->getJobName($name, $action);

            return "{$rootNamespace}\\{$name}\\Jobs\\{$jobName}";
        }

        if ($unit === 'Feature') {
            $rootNamespace = $this->getRootNamespace('features');
            $featureName = $this->getFeatureName($name, $action);

            return "{$rootNamespace}\\{$name}\\{$featureName}";
        }

        return null;
    }

    /**
     * @param $modelName
     * @param $action
     *
     * @return string
     */
    protected function getJobName($modelName, $action): string
    {
        $name = self::JOB_PREFIXES[$action];
        $noun = $this->getDeclension($modelName, $action);

        return $name . ucfirst($noun);
    }

    /**
     * @param $modelName
     * @param $action
     *
     * @return string
     */
    protected function getFeatureName($modelName, $action): string
    {
        $name = self::FEATURE_PREFIXES[$action];
        $noun = $this->getDeclension($modelName, $action);

        return $name . ucfirst($noun);
    }

    /**
     * @param $modelName
     * @param $action
     *
     * @return string
     */
    protected function getDeclension($modelName, $action): string
    {
        $form = self::ACTION_RESOURCE_FORMS[$action];

        if ($form === 'singular') {
            return str_singular($modelName);
        }

        return str_plural($modelName);
    }
}
