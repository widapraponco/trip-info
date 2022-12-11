<?php

declare(strict_types=1);

namespace Domain\Image\Actions;

use App\Models\Image;

class FindImageByRouteKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): Image
    {
        $query =  Image::where((new Image())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}
