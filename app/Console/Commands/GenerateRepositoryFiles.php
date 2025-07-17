<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class GenerateRepositoryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository-files {name : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description(LivewireComponent, Interface, Repository, Request)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('name');

        // Generate Interface
        $this->generateInterface($modelName);

        // Generate Repository
        $this->generateRepository($modelName);

        // Generate Livewire Component
        $this->generateLivewireComponent($modelName);

        // Generate Request
        $this->generateRequest($modelName);

        // Generate Livewire Blade File
        $this->livewireBlade($modelName);

        // Generate Model File
        $this->model($modelName);

        // Generate Migration File
        $this->generateMigration($modelName);

        // Generate Seeder File
        $this->generateSeeder($modelName);

        $this->info("Repository Pattern files for $modelName generated successfully!");
    }

    protected function generateInterface($modelName)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$modelName}RepositoryInterface.php");

        if (!File::exists(dirname($interfacePath))) {
            File::makeDirectory(dirname($interfacePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/interface.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($interfacePath, $stub);
    }

    protected function generateRepository($modelName)
    {
        $repositoryPath = app_path("Repositories/Files/{$modelName}Repository.php");

        if (!File::exists(dirname($repositoryPath))) {
            File::makeDirectory(dirname($repositoryPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/repository.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($repositoryPath, $stub);
    }

    protected function generateLivewireComponent($modelName)
    {
        $livewireComponentPath = app_path("Livewire/{$modelName}s.php");

        if (!File::exists(dirname($livewireComponentPath))) {
            File::makeDirectory(dirname($livewireComponentPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/livewireComponent.stub');
        $stub = str_replace(['{{ModelName}}','{{modelName}}'], [$modelName,Str::camel($modelName)], $stub);

        File::put($livewireComponentPath, $stub);
    }

    protected function generateRequest($modelName)
    {
        $requestPath = app_path("Http/Requests/{$modelName}Request.php");

        if (!File::exists(dirname($requestPath))) {
            File::makeDirectory(dirname($requestPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/request.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($requestPath, $stub);
    }
    // livewireBlade
    public function livewireBlade($modelName){
         $livewireBladePath = resource_path("views/livewire/{$modelName}s.blade.php");

        if (!File::exists(dirname($livewireBladePath))) {
            File::makeDirectory(dirname($livewireBladePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/livewireBlade.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($livewireBladePath, $stub);
    }

    // Model
    public function model($modelName){
        $modelPath = app_path("Models/{$modelName}.php");

        if (!File::exists(dirname($modelPath))) {
            File::makeDirectory(dirname($modelPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/model.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($modelPath, $stub);
    }

    // make migrateion
    public function generateMigration($modelName)
    {
        // Convert model name to proper table name (lowercase, plural)
        $tableName = Str::plural(Str::snake($modelName));

        $migrationName = "create_{$tableName}_table";
        $migrationPath = database_path("migrations/".date('Y_m_d_His')."_".$migrationName.".php");

        // Get stub content
        $stub = File::get(__DIR__.'/stubs/migration.stub');

        // Replace placeholders
        $stub = str_replace('{{tableName}}', $tableName, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub); // Keep model name for model reference if needed

        File::put($migrationPath, $stub);

        return $migrationPath;
    }
    public function generateSeeder($modelName)
    {
        $seederName = "{$modelName}Seeder";
        $seederPath = database_path("seeders/{$seederName}.php");

        $stub = File::get(__DIR__.'/stubs/seeder.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', strtolower($modelName), $stub);

        File::put($seederPath, $stub);

        return $seederPath;
    }
}
