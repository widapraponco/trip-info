<?php

declare(strict_types=1);

namespace Domain\Image\Actions;

use App\Models\Image;

class CreateImageAction
{
    public function execute(array $attributes): Image
    {
        $attributes['pic'] = app('hash')->make($attributes['pic']);

        return Image::create($attributes);
    }
}
