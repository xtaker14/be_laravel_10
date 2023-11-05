<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RetryFailedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retry-failed-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $failedJobIds = \DB::table('failed_jobs')->pluck('id');
        foreach ($failedJobIds as $jobId) {
            Artisan::call('queue:retry', ['id' => $jobId]);
        }
        $this->info('All failed jobs have been retried.');
    }
}
