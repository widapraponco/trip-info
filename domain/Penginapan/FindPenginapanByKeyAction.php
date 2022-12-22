<?php

declare(strict_types=1);

namespace Domain\Penginapan\Actions;

use App\Models\Penginapan;

class FindPenginapanByKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): Penginapan
    {
        $query =  Penginapan::where((new Penginapan())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}