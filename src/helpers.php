<?php

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
