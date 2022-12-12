<?php

declare(strict_types=1);

namespace Domain\Destinasi\Actions;

use App\Models\Auth\Destinasi\Destinasi;

class CreateDestinasiAction
{
    public function execute(array $attributes): Destinasi
    {
        $attributes['password'] = app('hash')->make($attributes['password']);

        return Destinasi::create($attributes);
    }
}
