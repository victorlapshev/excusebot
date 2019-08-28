<?php

namespace App\Providers;

use App\Events\TelegramUpdatesEvent;
use App\Listeners\TelegramUpdatesListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TelegramUpdatesEvent::class => [
            TelegramUpdatesListener::class
        ],
    ];
}
