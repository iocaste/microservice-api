<?php

namespace Iocaste\Microservice\Api\Routing;

use Laravel\Lumen\Routing\Router as LumenRouter;
use Illuminate\Support\Fluent;

class Router
{
    /**
     * @var LumenRouter
     */
    protected $router;

    /**
     * @var ...
     */
    protected $api;

    /**
     * @var Fluent
     */
    protected $options;

    /**
     * Router constructor.
     *
     * @param LumenRouter $router
     * @param $api
     * @param array $options
     */
    public function __construct(LumenRouter $router, $api, array $options)
    {
        $this->router = $router;
        $this->api = $api;
        $this->options = new Fluent($options);
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return void
     */
    public function resource($name, array $options = []): void
    {
        $options = $this->getOptions($options);

        // dd(1);
        // dd($this->createResourceGroup($name, $options));

        $this->createResourceGroup($name, $options)->add($this->router);
    }

    /**
     * @param $name
     * @param array $options
     *
     * @return ResourceGroup
     */
    protected function createResourceGroup($name, array $options): ResourceGroup
    {
        $resolver = null;

        return new ResourceGroup($name, $resolver, new Fluent($options));
    }

    /**
     * Returns merged options: current resource + default options
     *
     * @param array $resourceOptions
     *
     * @return array
     */
    protected function getOptions(array $resourceOptions): array
    {
        return array_merge($this->getResourceDefaults(), $resourceOptions);
    }

    /**
     * Returns array of default resource options
     *
     * @return array
     */
    protected function getResourceDefaults(): array
    {
        return [
            'default-authorizer' => $this->options->get('authorizer'),
            'prefix' => '/api/v1', // @todo .SET.prefix $this->api->getUrl()->getNamespace()
            'id' => $this->options->get('id'),
        ];
    }
}
