<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name : The name of the service}';

    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');

        $serviceStub = File::get(app_path('Console/Commands/stubs/service.stub'));
        $serviceClass = str_replace('{{name}}', $name, $serviceStub);

        $servicePath = app_path("Services/{$name}.php");

        if (File::exists($servicePath)) {
            $this->error("The service {$name} already exists!");
            return;
        }

        // Create the Services directory if it doesn't exist
        if (!File::isDirectory(app_path('Services'))) {
            File::makeDirectory(app_path('Services'));
        }

        File::put($servicePath, $serviceClass);

        $this->info("Service {$name} created successfully!");
    }
}
