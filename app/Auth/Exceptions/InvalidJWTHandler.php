<?php

declare(strict_types=1);

namespace App\Auth\Exceptions;

final class InvalidJWTHandler extends ApplicationException
{
    /**
     * Internal representation of errors data
     */
    private string $status = 'error';

    private string $errors = 'exception.invalid_access_token';
}
