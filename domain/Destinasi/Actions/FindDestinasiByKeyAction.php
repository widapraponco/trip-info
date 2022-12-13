<?php

declare(strict_types=1);

namespace Domain\Destinasi\Actions;

use App\Models\Auth\Destinasi\Destinasi;

class FindDestinasiByRouteKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): Destinasi
    {
        $query =  Destinasi::where((new Destinasi())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}
