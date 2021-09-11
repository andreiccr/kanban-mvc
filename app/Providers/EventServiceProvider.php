<?php

namespace App\Providers;

use App\Events\CardDeletedEvent;
use App\Events\CardReorderedEvent;
use App\Listeners\ReorderCardsInListtListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CardReorderedEvent::class => [
            ReorderCardsInListtListener::class,
        ],
        CardDeletedEvent::class => [
            ReorderCardsInListtListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
