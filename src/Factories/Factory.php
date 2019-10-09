<?php

namespace Iocaste\Microservice\Api\Factories;

use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Iocaste\Microservice\Api\Container\Container;
use Iocaste\Microservice\Api\Contracts\ContainerInterface;
use Iocaste\Microservice\Api\Contracts\Factory\FactoryInterface;
use Iocaste\Microservice\Api\Contracts\Resolver\ResolverInterface;
use Iocaste\Microservice\Api\Encoders\Encoder;
use Iocaste\Microservice\Api\Exceptions\RuntimeException;
use Iocaste\Microservice\Api\Http\Responses\MicroResponse;
use Iocaste\Microservice\Api\Resolvers\ResolverFactory;
use Iocaste\Microservice\Api\Resource\ResourceRepository;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Factories\Factory as BaseFactory;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface as SchemaContainerInterface;
use Neomerx\JsonApi\Encoder\EncoderOptions;

/**
 * Class Factory
 */
class Factory extends BaseFactory implements FactoryInterface
{
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
        parent::__construct();

        $this->container = $container;
    }

    /**
     * @param $version
     * @param array $config
     *
     * @return ResolverInterface|mixed
     */
    public function createResolver($version, array $config)
    {
        $factoryName = $config['resolver'] ?? ResolverFactory::class;

        $factory = $this->container->make($factoryName);

        if ($factory instanceof ResolverInterface) {
            return $factory;
        }

        if (! is_callable($factory)) {
            throw new RuntimeException('Factory ' . $factoryName . ' cannot be invoked.');
        }

        $resolver = $factory($version, $config);

        if (! $resolver instanceof ResolverInterface) {
            throw new RuntimeException('Factory ' . $factoryName . ' did not create a resolver instance.');
        }

        return $resolver;
    }

    /**
     * @inheritdoc
     */
    public function createApiContainer(ResolverInterface $resolver): Container
    {
        return new Container($this->container, $resolver);
    }

    public function createEncoder(
        SchemaContainerInterface $container,
        EncoderOptions $encoderOptions = null
    ): EncoderInterface {
        $encoder = new Encoder($this, $container, $encoderOptions);
        $encoder->setLogger($this->logger);

        return $encoder;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ResourceRepository
     */
    public function createResourceRepository(ContainerInterface $container): ResourceRepository
    {
        return new ResourceRepository($container);
    }

    /**
     * @param $schemaRepository
     * @param null $parameters
     * @param null $urlPrefix
     *
     * @return MicroResponse
     */
    public function createResponse(
        $schemaRepository,
        $parameters = null,
        $urlPrefix = null
    ): MicroResponse {
        return new MicroResponse($this, $schemaRepository, $parameters, $urlPrefix);
    }
}
