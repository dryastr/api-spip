<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Upload File
     *
     * @param $path
     * @param $fileContent
     * @param $ext
     * @param $filename
     * @param $type
     * @param $userId
     *
     * @return bool|array
     */
    public static function upload($fileContent, $ext, $path = null, $filename = null, $type = null, $userId = null): bool|array
    {
        ini_set('memory_limit', '-1');

        if (! $filename) {
            $filename = Str::uuid()->toString() . '_' . now()->timestamp;
        }

        $filename .= ".{$ext}";

        if ($userId) {
            $filename = "{$userId}_{$filename}";
        }

        if ($type) {
            $filename = "{$type}/{$filename}";
        }

        if (Storage::put("{$path}/{$filename}", $fileContent)) {
            $file_size = Storage::size("{$path}/{$filename}");

            return [
                'path' => $path,
                'filename' => "{$path}/{$filename}",
                'ext' => $ext,
                'file_size' => $file_size,
            ];
        }
        return false;
    }

    /**
     * Delete Image
     *
     * @param $path
     *
     * @return bool
     */
    public static function delete($path): bool
    {
        return Storage::delete($path);
    }
}
