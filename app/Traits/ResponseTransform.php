<?php

namespace App\Traits;

use App\Exceptions\ServiceErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom Response
 */
trait ResponseTransform
{
    /**
     * response
     *
     * @param mixed $data
     * @param mixed $message
     * @param string $status
     * @param int $code
     * @param array $headers
     * @param array $additionals
     *
     * @return JsonResponse
     */
    public function response($data = null, ?string $message = null, int|string $status = 'success', int $code = 200, array $headers = [], array $additionals = []): JsonResponse
    {
        if (is_null($message)) {
            $message = __('Berhasil');
        } elseif (is_string($message)) {
            $message = __($message);
        }

        $response['status'] = $status === 'success' ? 'success' : 'error';
        if (in_array($code, [400, 401])) {
            $response['message'] = $message;
        } else {
            if ($status !== 'success') {
                $code = $code;
                $response['message'] = $code === 422 ? 'Unprocessable Entity' : $data;
                if (is_array($data) || is_object($data)) {
                    $response['errors'] = $data;
                } else {
                    $response['errors'] = [
                        'error' => [
                            $data,
                        ],
                    ];
                }
            } else {
                $response['message'] = $message;
                if ($data !== false || is_null($data)) {
                    $response['data'] = $data;
                }
            }
        }

        if (! empty($additionals)) {
            $response = array_merge($response, $additionals);
        }

        return response()->json($response, $code, $headers);
    }

    /**
     * Undocumented function
     *
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function responseError($message, $code = 422, $data = null, $headers = [], $trace = null): JsonResponse
    {
        if (is_null($message)) {
            $message = __('response.message.error');
        } elseif (is_string($message)) {
            $message = __($message);
        }

        $response['status'] = 'error';
        $response['message'] = $message;

        if ($data === null) {
            $data = $message;
        }

        if (config('app.debug') && $trace !== null) {
            $response['trace'] = $trace;
        }

        if (! $code) {
            $code = 422;
        }

        if ($code === 422) {
            if (is_array($data) || is_object($data)) {
                $response['errors'] = $data;
            } else {
                $response['errors'] = [
                    'error' => [
                        $data,
                    ],
                ];
            }
        }

        return response()->json($response, $code, $headers);
    }

    /**
     * @param Exception|ServiceErrorException $ex
     * @param null $data
     *
     * @return JsonResponse
     */
    public function responseException(Exception|ServiceErrorException $ex, $data = null): JsonResponse
    {
        $status_code = $ex->getCode();

        if (! array_key_exists($status_code, Response::$statusTexts)) {
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $this->responseError(
            message: $ex->getMessage(),
            code: $status_code,
            data: $data,
            trace: config('app.debug') ? $ex->getTrace() : null
        );
    }

    /**
     * @param $resource
     * @param LengthAwarePaginator $data
     * @param string|null $message
     * @param int|string $status
     * @param int $code
     * @param array $headers
     * @param array $additionals
     *
     * @return JsonResponse
     */
    public function responsePaginate(
        $resource,
        $data,
        string $resourceFunction = 'collection',
        array $customParams = [],
        ?string $message = null,
        int|string $status = 'success',
        int $code = 200,
        array $headers = [],
        array $additionals = []
    ): JsonResponse {
        return $this->response(
            data: $resource::$resourceFunction($data->getCollection(), ...$customParams)
                ->additional(
                    collect($data->toArray())->except('data')->toArray()
                )->response()->getData(true),
            message: $message,
            status: $status,
            code: $code,
            headers: $headers,
            additionals: $additionals
        );
    }
}
