<?php

namespace App\Providers\Modules;

use App\Models\Events\Event;
use App\Modules\Events\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class EventProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Event::class, EventPolicy::class);
    }
}
