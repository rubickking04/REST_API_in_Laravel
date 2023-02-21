<?php

namespace App\Listeners;

use App\Events\DatabaseRefreshed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DatabaseRefresher implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DatabaseRefreshed  $event
     * @return void
     */
    public function handle(DatabaseRefreshed $event)
    {
        //
    }
}
