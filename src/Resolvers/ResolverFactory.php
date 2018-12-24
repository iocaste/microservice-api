<?php

namespace Iocaste\Microservice\Api\Resolvers;

/**
 * Class ResolverFactory
 */
class ResolverFactory
{
    public function __invoke($version, array $config)
    {
        return new NamespaceResolver(
            $config['namespaces'],
            $config['resources']
        );
    }
}
