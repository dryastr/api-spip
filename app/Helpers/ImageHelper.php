<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ImageHelper
{
    /**
     * Upload Image
     *
     * @param $file
     * @param $name
     * @param $path
     * @param $type
     * @param $width
     * @param $height
     * @param int $compress
     *
     * @return string
     */
    public static function upload($file, $name = null, $path = null, $type = null, $width = null, $height = null, int $compress = 100): string
    {
        ini_set('memory_limit', '-1');
        $img = Image::make($file);
        $ext = Arr::last(explode('/', $img->mime()));
        $filename = '.' . $ext;
        if ($name) {
            $filename = $name . $filename;
        } else {
            $filename = Str::random(40) . $filename;
        }
        if ($path) {
            $filename = $path . '/' . $filename;
        } else {
            $filename = date('Y/m/d') . '/' . $filename;
        }
        if ($type) {
            $filename = $type . '/' . $filename;
        }
        if (! is_null($width) || ! is_null($height)) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if ($compress === 100) {
            if (Storage::put($filename, $img->encode())) {
                return $filename;
            }
        } else {
            if (Storage::put($filename, $img->encode('jpg', $compress))) {
                return $filename;
            }
        }
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
