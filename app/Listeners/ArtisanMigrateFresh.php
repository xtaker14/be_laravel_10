<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

class ArtisanMigrateFresh
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommandStarting $event)
    {
        // "php artisan migrate:fresh"
        // if ($event->command == "migrate:fresh" && App::environment("development"))

        if ($event->command == "migrate:fresh") 
        {
            $connections = config('database.refreshable');

            if (is_array($connections)) {
                foreach ($connections as $connection) {
                    Artisan::call('db:wipe', [
                        '--database' => $connection,
                        '--force' => true,
                    ]);
                }
            }
        }
    }
}