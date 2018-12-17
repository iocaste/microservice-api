<?php

namespace Iocaste\Microservice\Api\Api;

/**
 * Class Api
 */
class Url
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * Create a URL from an array.
     *
     * @param array $url
     * @return Url
     */
    public static function fromArray(array $url): self
    {
        return new self(
            $url['host'] ?? '',
            $url['namespace'] ?? '',
            $url['name'] ?? ''
        );
    }

    /**
     * Url constructor.
     *
     * @param string $host
     * @param string $namespace
     * @param string $name
     */
    public function __construct(string $host, string $namespace, string $name)
    {
        $this->host = rtrim($host, '/');
        $this->namespace = $namespace ? '/' . ltrim($namespace, '/') : '';
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return rtrim($this->host . $this->namespace, '/');
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
