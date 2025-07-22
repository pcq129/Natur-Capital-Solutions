<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;


class generateService extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {Service Name}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serviceName = $this->argument('Service Name');
        if ($serviceName) {
            $serviceName = ucfirst($serviceName);
            $filePath = app_path('Services\\' . $serviceName . '.php');
            // dd($serviceName, $filePath);

            (new Filesystem)->ensureDirectoryExists(dirname($filePath));

            $stub = file_get_contents(app_path('Console/Commands/stubs/service.stub'));
            $stub = str_replace('{{ serviceName }}', $serviceName, $stub);
            $stub = str_replace('{{ namespace }}', 'App\Services', $stub);

            file_put_contents($filePath, $stub);
            $this->info("Service {$serviceName} created successfully. Path: {$filePath}");
        } else {
            $this->error('Service name is required.');
        }
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'Service Name' => "What'd you like to name the service?",
        ];
    }
}
