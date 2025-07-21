<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Storage;

class generateService extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {serviceName}';

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
        $serviceName = $this->argument('serviceName');
        if($serviceName){
            $serviceName = ucfirst($serviceName);
            $filePath = '/Services/' . $serviceName . '.php';
            // dd($serviceName, $filePath);

            if (file_exists($filePath)) {
                $this->error("Service {$serviceName} already exists.");
                return;
            }

            $stub = file_get_contents(app_path('Console/Commands/stubs/service.stub'));
            $stub = str_replace('{{ serviceName }}', $serviceName, $stub);
            $stub = str_replace('{{namespace}}', 'App\Services', $stub);

            Storage::disk('local')->put($filePath, $stub);
            $this->info("Service {$serviceName} created successfully. Path: {$filePath}");
        } else {
            $this->error('Service name is required.');
        }
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'serviceName' => "What'd you like to name the service?",
        ];
    }
}
