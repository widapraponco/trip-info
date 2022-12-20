<?php

declare(strict_types=1);

namespace Domain\Event;

use App\Models\Event;

class FindEventByRouteKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): Event
    {
        $query =  Event::where((new Event())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}
