<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Custom JSON-RPC Response
 */
trait RpcResponseTransform
{
    /**
     * Default Response Transform
     *
     * @param mixed $data
     * @param mixed $message
     * @param string $status
     * @param int $code
     * @param array $additionals
     *
     * @return array|object
     */
    public function response($data = null, ?string $message = null, int | string $status = '1', int $code = 200, array $additionals = []): array | object | null
    {
        if (is_null($message)) {
            $message = [
                'id' => __('Berhasil', [], 'id'),
                'en' => __('Berhasil', [], 'en'),
            ];
        } elseif (is_string($message)) {
            $message = [
                'id' => __($message, [], 'id'),
                'en' => __($message, [], 'en'),
            ];
        }

        $response['code'] = $code;
        $response['message'] = $message;
        $response['data'] = $data;

        return array_merge($response, $additionals);
    }
}
