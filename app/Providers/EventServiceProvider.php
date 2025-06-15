<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\RedirectBasedOnRole;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            RedirectBasedOnRole::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
