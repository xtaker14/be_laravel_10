<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;

class DeliveryRecordCollected extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:delivery-record-collected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check delivery record when status ready change to collected';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routingRepository = App::make('App\Interfaces\RoutingRepositoryInterface');
        $check = $routingRepository->checkReadyCollected();

        $this->info("Total Delivery Record Updated: {$check}");
    }
}
