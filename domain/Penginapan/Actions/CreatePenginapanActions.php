<?php

declare(strict_types=1);

namespace Domain\Penginapan\Actions;

use App\Models\Penginapan;

class CreatePenginapanAction
{
    public function execute(array $attributes): Penginapan
    {
        return Penginapan::create($attributes);
    }
}