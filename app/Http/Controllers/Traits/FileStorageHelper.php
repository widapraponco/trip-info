<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Filesystem\FilesystemException;
use Illuminate\Support\Facades\Storage;

trait FileStorageHelper {

    public function reconstructionFile(array &$attributes = [], string $path)
    {
        $attributes['name'] = $attributes['file']->getClientOriginalName();
        $attributes['type'] = $attributes['file']->getClientMimeType();
        $attributes['size'] = $attributes['file']->getSize();
        $attributes['path'] = $path;
    }
}