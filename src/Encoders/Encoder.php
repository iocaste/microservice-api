<?php

namespace Iocaste\Microservice\Api\Encoders;

use Iocaste\Microservice\Api\Contracts\Encoder\SerializerInterface;
use Iocaste\Microservice\Api\Factories\Factory;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Encoder\Encoder as BaseEncoder;
use Neomerx\JsonApi\Encoder\Serialize\ArraySerializerTrait;

/**
 * Class Encoder
 */
class Encoder extends BaseEncoder implements SerializerInterface
{
    use ArraySerializerTrait;

    /**
     * @return Factory
     */
    protected static function createFactory(): FactoryInterface
    {
        return app(Factory::class);
    }
}
