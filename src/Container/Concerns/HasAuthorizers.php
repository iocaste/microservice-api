<?php

namespace Iocaste\Microservice\Api\Container\Concerns;

use Iocaste\Microservice\Api\Contracts\Auth\AuthorizerInterface;
use Iocaste\Microservice\Api\Exceptions\RuntimeException;

/**
 * Trait HasAuthorizers
 */
trait HasAuthorizers
{
    /**
     * @var array
     */
    private $createdAuthorizers = [];

    /**
     * @param string $resourceName
     * @return bool
     */
    protected function hasCreatedAuthorizer($resourceName): bool
    {
        return array_key_exists($resourceName, $this->createdAuthorizers);
    }

    /**
     * @param $resourceName
     *
     * @return AuthorizerInterface|null
     */
    protected function getCreatedAuthorizer($resourceName): ?AuthorizerInterface
    {
        return $this->createdAuthorizers[$resourceName];
    }

    /**
     * @param string $resourceType
     * @param AuthorizerInterface|null $authorizer
     * @return void
     */
    protected function setCreatedAuthorizer($resourceType, AuthorizerInterface $authorizer = null): void
    {
        $this->createdAuthorizers[$resourceType] = $authorizer;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorizerByResourceName($resourceName): ?AuthorizerInterface
    {
        if ($this->hasCreatedAuthorizer($resourceName)) {
            return $this->getCreatedAuthorizer($resourceName);
        }

        if (!$this->resolver->isResourceName($resourceName)) {
            $this->setCreatedAuthorizer($resourceName, null);
            return null;
        }

        $class = $this->resolver->getAuthorizerByResourceName($resourceName);

        $authorizer = $this->createAuthorizerFromClass($class);
        $this->setCreatedAuthorizer($resourceName, $authorizer);

        return $authorizer;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorizerByName($authorizerName): ?AuthorizerInterface
    {
        dd($authorizerName);

        if (! $class = $this->resolver->getAuthorizerByName($authorizerName)) {
            throw new RuntimeException('Authorizer [' . $authorizerName . '] is not recognised.');
        }

        $authorizer = $this->create($class);

        if (! $authorizer instanceof AuthorizerInterface) {
            throw new RuntimeException('Class [' . $class . '] is not an authorizer.');
        }

        return $authorizer;
    }

    /**
     * @param $className
     * @return AuthorizerInterface|null
     */
    protected function createAuthorizerFromClass($className): ?AuthorizerInterface
    {
        $authorizer = $this->create($className);

        if ($authorizer !== null && ! $authorizer instanceof AuthorizerInterface) {
            throw new RuntimeException('Class [' . $className . '] is not a resource authorizer.');
        }

        return $authorizer;
    }
}
