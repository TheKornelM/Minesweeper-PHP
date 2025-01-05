<?php

namespace Utils;

/**
 * Class SetHeader
 *
 * Utility class for setting HTTP headers.
 */
class SetHeader
{
    /**
     * Sets the Content-Type header to application/json with UTF-8 encoding.
     *
     * @return void
     */
    public static function ToJson()
    {
        header('Content-Type: application/json; charset=utf-8');
    }
}