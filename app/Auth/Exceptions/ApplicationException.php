<?php

declare(strict_types=1);

namespace App\Auth\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApplicationException extends Exception
{
    /**
     * Create a new application exception for errors response.
     *
     * @param  string   $status
     * @param  int   $error_code
     * @param  mixed $errors
     * @param  mixed $additional_info
     * @param  array $headers
     *
     * @return void
     */
    public function __construct(
        private string $status = 'error',
        private mixed $errors = 'exception.invalid_access_token',
        private mixed $additional_info = [],
        private array $headers = []
    ) {
    }

    /**
     * Get property status from class.
     *
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * Get property errors from class.
     *
     * @return mixed
     */
    public function errors(): mixed
    {
        return $this->errors;
    }

    /**
     * Render the error response data
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request): Response
    {
        $error = new Error(
            status:$this->status(),
            errors:$this->errors(),
        );

        return response(
            content:$error->toArray(),
            status:401,
            headers:$this->headers
        );
    }
}
