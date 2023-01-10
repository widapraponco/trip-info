<?php

declare(strict_types=1);

namespace Domain\SaranaPrasarana\Actions;

use App\Models\SaranaPrasarana;

class FindSaranaPrasaranaByKeyAction
{
    public function execute(string $routeKey, bool $throw404 = false): SaranaPrasarana
    {
        $query =  SaranaPrasarana::where((new SaranaPrasarana())->getRouteKeyName(), $routeKey);

        if ($throw404) {
            return $query->firstOrFail();
        }

        return $query->first();
    }
}