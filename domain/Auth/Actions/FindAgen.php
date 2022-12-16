<?php

declare(strict_types=1);

namespace Domain\AgenTravel\Actions;

use App\Models\AgenTravel;

class FindUserByRouteKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): AgenTravel
    {
        $query =  AgenTravel::where((new AgenTravel())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}