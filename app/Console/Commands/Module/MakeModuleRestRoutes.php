<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:rest-routes
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate REST routes file and link it in module routes')]
class MakeModuleRestRoutes extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:rest-routes'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $written = $this->write(
            stub: 'routes.rest',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.rest.php",
            label: "routes/{$this->modelVariable}.rest.php",
        );

        if ($written) {
            $this->registerRestRouteInModuleFile();
        }

        $this->summary("REST routes for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }
}
