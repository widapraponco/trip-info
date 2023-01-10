<?php

declare(strict_types=1);

namespace Domain\SaranaPrasarana\Actions;

use App\Models\SaranaPrasarana;

class CreateSaranaPrasaranaAction
{
    public function execute(array $attributes): SaranaPrasarana
    {
        return SaranaPrasarana::create($attributes);
    }
}