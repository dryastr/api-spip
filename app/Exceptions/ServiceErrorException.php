<?php

namespace App\Exceptions;

use Exception;

class ServiceErrorException extends Exception
{
    private ?int $http_status_code;

    public function __construct($message, $http_status_code = 400)
    {
        $this->message = $message;
        $this->http_status_code = $http_status_code;
        parent::__construct($message, $http_status_code);
    }

    public function getStatusCode()
    {
        return $this->http_status_code;
    }
}
