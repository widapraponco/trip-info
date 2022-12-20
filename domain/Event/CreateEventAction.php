<?php

declare(strict_types=1);

namespace Domain\Event;

use App\Models\Event;

class CreateEventAction
{
    public function execute(array $attributes): Event
    {
        return Event::create($attributes);
    }
}
