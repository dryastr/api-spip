<?php

declare(strict_types=1);

namespace App\Traits;

use Str;

trait SlugHeaderRequest
{
    /**
     * convert variable to slug header request
     *
     * @param array $headers
     *
     * @return array
     */
    public function slugHeaderRequest(?array $headers = null): array
    {
        $result = [];
        foreach ($headers as $key => $value) {
            $slugHeader = Str::slug($key);
            $result["x-{$slugHeader}"] = $value ? $value : '0';
        }

        return $result;
    }
}
