<?php

declare(strict_types=1);

namespace Domain\Destinasi\Actions;

use App\Models\Destinasi;

class CreateDestinasiAction
{
    public function execute(array $attributes): Destinasi
    {
        return Destinasi::create($attributes);
    }
}
