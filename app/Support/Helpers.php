<?php

use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\Security;

if (! function_exists('security')) {
    function security(): Security
    {
        return new Security();
    }
}

if (! function_exists('file_helper')) {
    function file_helper(): FileHelper
    {
        return new FileHelper();
    }
}

if (! function_exists('image_helper')) {
    function image_helper(): ImageHelper
    {
        return new ImageHelper();
    }
}

/**
 * Provide access to optional objects (enhancement from laravel package).
 *
 * @param mixed|null $value
 * @param  callable|null  $callback
 *
 * @return mixed
 */
if (! function_exists('unforced')) {
    function unforced($value = null, ?callable $callback = null)
    {
        if (is_null($callback)) {
            return new Unforced($value);
        }
        if (! is_null($value)) {
            return $callback($value);
        }
    }
}
