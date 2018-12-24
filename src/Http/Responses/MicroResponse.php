<?php

namespace Iocaste\Microservice\Api\Http\Responses;

use Iocaste\Microservice\Api\Contracts\Factory\FactoryInterface;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\JsonApi\Http\BaseResponses;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Http\Headers\MediaType;

/**
 * Class MicroResponse
 */
class MicroResponse extends BaseResponses
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ContainerInterface
     */
    protected $schemas;

    /**
     * @var EncodingParametersInterface|null
     */
    protected $parameters;

    /**
     * @var string|null
     */
    protected $urlPrefix;

    public function __construct(
        FactoryInterface $factory,
        ContainerInterface $schemas,
        $parameters = null,
        $urlPrefix = null
    ) {
        $this->factory = $factory;
        $this->schemas = $schemas;
        $this->parameters = $parameters;
        $this->urlPrefix = $urlPrefix;
    }

    public static function create($version = null)
    {
        $api = micro_api($version);
        $request = micro_api_request();

        return $api->response($request ? $request->getParameters() : null);
    }

    /**
     * @inheritdoc
     */
    protected function getEncodingParameters(): ?EncodingParametersInterface
    {
        return $this->parameters;
    }

    /**
     * @inheritdoc
     */
    protected function getEncoder(): EncoderInterface
    {
        return $this->factory->createEncoder(
            $this->getSchemaContainer(),
            new EncoderOptions(0, $this->getUrlPrefix())
        );
    }

    /**
     * @inheritdoc
     */
    protected function getUrlPrefix(): ?string
    {
        return $this->urlPrefix;
    }

    /**
     * @inheritdoc
     */
    protected function getSchemaContainer(): ContainerInterface
    {
        return $this->schemas;
    }

    /**
     * @inheritdoc
     */
    protected function getMediaType(): MediaTypeInterface
    {
        return new MediaType(MediaType::JSON_API_TYPE, MediaType::JSON_API_SUB_TYPE);
    }

    /**
     * @inheritdoc
     */
    protected function createResponse(?string $content, int $statusCode, array $headers)
    {
        return response($content, $statusCode, $headers);
    }

    /**
     * @param $data
     * @param array $links
     * @param mixed $meta
     * @param int $statusCode
     * @param array $headers
     *
     * @return mixed
     */
    public function content($data, array $links = [], $meta = null, $statusCode = self::HTTP_OK, array $headers = [])
    {
        return $this->getContentResponse($data, $statusCode, $links, $meta, $headers);
    }
}
