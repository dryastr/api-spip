<?php

namespace App\Auth;

use App\Helpers\AuthHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use JsonSerializable;

class Auth implements Arrayable, Jsonable, JsonSerializable
{
    protected $allowedParsing = ['boolean', 'integer', 'double', 'string'];

    protected array $cast = [
        'email' => 'string',
        'name' => 'string',
        'user_id' => 'integer',
        'expires_at' => 'integer',
    ];
    private ?Request $request;

    /**
     * Constructor
     *
     * @param  bool $is_debug
     * @param  string|null $guard
     *
     * @return void
     */
    public function __construct(bool $is_debug = false, string|null $guard = null)
    {
        $this->request = request();
        $this->init($is_debug, $guard);
    }

    public function __call($property, $args)
    {
        $property = str()->snake($property);
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }

    /**
     * Getting header request from kong
     *
     * @param  bool $is_debug
     * @param  string|null $guard
     *
     * @return void
     */
    public function init(string|null $guard = null)
    {
        if (config('app.env') !== 'testing') {
            $authHelper = app(AuthHelper::class);
            $token = request()->bearerToken() ?? request()->input('token');
            $data = $authHelper->getByToken($token ?? '');

            if ($data) {
                foreach ($data as $header => $headerAuth) {
                    $defaultType = gettype(config("auth.acting-as.{$header}"));
                    $this->$header = $this->dynamicParsing($header, $headerAuth, $defaultType);
                }
            }
        }

        foreach (\config('auth.forward_headers_request', []) as $header) {
            $header = str()->snake($header);
            if (! str()->startsWith($header, 'x_')) {
                $header_kong = str()->slug("x-{$header}");
            }

            $acting_value = null;
            if (config('auth.enable-acting')) {
                $acting_value = config("auth.acting-as.{$header}");
            }

            $defaultType = gettype(config("auth.acting-as.{$header}"));
            $header_kong_value = $this->request->header($header_kong, $acting_value);

            $this->$header ??= $this->dynamicParsing($header, $header_kong_value, $defaultType);
        }
    }

    /**
     * Check Validate Header
     *
     * @param string|null $guard
     * @param bool $skip_expires
     *
     * @return bool
     */
    public function check(string|null $guard = null, bool $skip_expires = false): bool
    {
        $is_valid_guard = true;

        $is_not_expired = true;
        if (! $skip_expires) {
            $is_not_expired = ! $this->isExpired();
        }

        return $is_not_expired && $is_valid_guard;
    }

    /**
     * Check Expired Token
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiresAt() < time();
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function id(): int|null
    {
        return $this->userId();
    }

    /**
     * Set property to array format.
     *
     * @return array
     */
    public function toArray(): array
    {
        $dynamic_property = [];
        foreach (\config('auth.forward_headers_request', []) as $header) {
            $header = str()->snake($header);
            $method = str()->camel($header);
            $dynamic_property[$header] = $this->$method();
        }

        return [
            'id' => $this->id(),
            'email' => $this->email(),
            ...$dynamic_property,
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

    /**
     * Parsing string to boolean
     *
     * @param string|null $value
     *
     * @return bool
     */
    protected static function parseBoolean(string|null $value): bool
    {
        $boolval = (is_string($value) ? filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $value);

        return $boolval === null ? false : $boolval;
    }

    /**
     * Parsing string to integer
     *
     * @param string|null $value
     *
     * @return int
     */
    protected static function parseInteger(string|null $value): int
    {
        return intval($value);
    }

    /**
     * Parsing string to double/float
     *
     * @param string|null $value
     *
     * @return float
     */
    protected static function parseDouble(string|null $value): float
    {
        return doubleval($value);
    }

    /**
     * Parsing to string
     *
     * @param string|null $value
     *
     * @return string
     */
    protected static function parseString(string|null $value): string
    {
        return (string) $value;
    }

    /**
     * Dynamic Parsing Value to allowed parsing type
     *
     * @param string $key key of casting list
     * @param mixed  $value value to parsing
     * @param string $type parsing type
     *
     * @return mixed
     */
    protected function dynamicParsing(string $key, $value, string $type = 'string'): mixed
    {
        if (in_array($type, $this->allowedParsing)) {
            $castType = ucfirst([
                $key => $type,
                ...$this->cast,
            ][$key]);

            return $this->{"parse{$castType}"}($value);
        }

        return $value;
    }
}
