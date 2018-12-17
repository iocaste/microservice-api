<?php

namespace {

    use Iocaste\Microservice\Api\Api\Api;
    use Iocaste\Microservice\Api\Http\Requests\MicroApiRequest;

    if (! function_exists('to_boolean')) {
        /**
         * @param $value
         *
         * @return boolean
         */
        function to_boolean($value)
        {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
    }

    if (! function_exists('micro_api')) {

        /**
         * @param string|null $version
         *
         * @return Api
         */
        function micro_api($version = null)
        {
            if ($version) {
                return app('micro-api')->getApi($version);
            }

            return app('micro-api')->getRequestApiOrDefault();
        }
    }

    if (! function_exists('micro_api_request')) {
        /**
         * Get the inbound JSON API request.
         *
         * @return MicroApiRequest|null
         */
        function micro_api_request()
        {
            return app('micro-api')->getRequest();
        }
    }
}
