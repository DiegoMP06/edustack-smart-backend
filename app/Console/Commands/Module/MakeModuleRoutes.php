<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:routes
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate module routes file and register it in bootstrap/app.php')]
class MakeModuleRoutes extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:routes'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $written = $this->write(
            stub: 'routes',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.php",
            label: "routes/{$this->modelVariable}.php",
        );

        if ($written) {
            $this->registerRouteInBootstrap("app/Modules/{$this->module}/routes/{$this->modelVariable}.php");
        }

        $this->summary("Routes for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }
}
