<?php

namespace App\Traits;

use App\Observers\ActivityLogObserver;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::observe(ActivityLogObserver::class);
    }
}   