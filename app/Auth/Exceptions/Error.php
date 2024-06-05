<?php

declare(strict_types=1);

namespace App\Auth\Exceptions;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

final class Error implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * Create a new errors data.
     *
     * @param  string   $status
     * @param  mixed $errors
     *
     * @return void
     */
    public function __construct(
        private string $status = 'error',
        private mixed $errors = 'Invalid Access Token'
    ) {
    }

    /**
     * Set property to array format.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->errors,
        ];
    }

    /**
     * Get all property of errors data.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert object of errors info to JSON format
     *
     * @param int|float  $options
     *
     * @return mixed
     */
    public function toJson($options = 0.0)
    {
        $jsonEncoded = json_encode($this->jsonSerialize(), $options);
        throw_unless($jsonEncoded, \JsonException::class);

        return $jsonEncoded;
    }
}
