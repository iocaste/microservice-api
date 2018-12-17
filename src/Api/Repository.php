<?php

namespace Iocaste\Microservice\Api\Api;

use Illuminate\Contracts\Config\Repository as Config;
use Iocaste\Microservice\Api\Exceptions\RuntimeException;
use Iocaste\Microservice\Api\Factories\Factory;

/**
 * Class Repository
 */
class Repository
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Repository constructor.
     *
     * @param Factory $factory
     * @param Config $config
     */
    public function __construct(Factory $factory, Config $config)
    {
        $this->factory = $factory;
        $this->config = $config;
    }

    /**
     * @param $version
     * @param null $host
     *
     * @return Api
     */
    public function createApi($version, $host = null): Api
    {
        $config = $this->getConfig($version);
        $config = $this->transform($config, $host);

        $api = new Api(
            $this->factory,
            $version,
            Url::fromArray($config['url']),
            $config['use-eloquent']
        );

        return $api;
    }

    /**
     * @param $version
     *
     * @return array
     */
    protected function getConfig($version): array
    {
        $config = (array) $this->config->get($this->getConfigKey($version));

        if (empty($config)) {
            throw new RuntimeException('Micro api config "' . $version . '" does not exist.');
        }

        return $config;
    }

    /**
     * @param $version
     * @param null $path
     *
     * @return string
     */
    protected function getConfigKey($version, $path = null): string
    {
        $key = 'micro-api-' . $version;

        return $path ? $key . $path : $key;
    }

    /**
     * @param array $config
     * @param null $host
     *
     * @return array
     */
    protected function transform(array $config, $host = null): array
    {
        $appNamespace = rtrim(app()->getNamespace(), '\\');

        if (! array_get($config, 'namespaces.features')) {
            $config['namespaces']['features'] = $appNamespace . '\\Features';
        }

        if (! array_get($config, 'namespaces.domains')) {
            $config['namespaces']['domains'] = $appNamespace . '\\Domains';
        }

        $config['url'] = $this->transformUrl($config['url'], $host);

        return $config;
    }

    /**
     * @param array $url
     * @param null $host
     *
     * @return array
     */
    protected function transformUrl(array $url, $host = null): array
    {
        $prependHost = false !== array_get($url, 'host');

        if ($host) {
            $url['host'] = $host;
        } elseif (!isset($url['host'])) {
            // $url['host'] = $this->config->get('app.url');
            $url['host'] = env('APP_URL');
        }

        return [
            'host' => $prependHost ? $url['host'] : '',
            'namespace' => array_get($url, 'namespace'),
            'name' => array_get($url, 'name'),
        ];
    }
}
