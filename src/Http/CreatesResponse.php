<?php

namespace Iocaste\Microservice\Api\Http;

/**
 * Trait CreatesResponse
 */
trait CreatesResponse
{
    /**
     * @return mixed
     */
    protected function respond()
    {
        return response()->microApi($this->getVersion());
    }

    /**
     * @return string|null
     */
    protected function getVersion(): ?string
    {
        return property_exists($this, 'version') ? $this->version : null;
    }
}
