<?php

namespace Iocaste\Microservice\Api\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class MicroApi
 */
class MicroApi extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'micro-api';
    }
}
