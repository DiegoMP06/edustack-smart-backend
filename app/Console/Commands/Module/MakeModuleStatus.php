<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:status
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--field=is_published : Boolean status field to toggle}
    {--timestamp=published_at : Optional datetime field to set/clear}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate an invokable status controller for a module model')]
class MakeModuleStatus extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:status'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $statusField = (string) $this->option('field');
        $timestampField = (string) $this->option('timestamp');

        $this->write(
            stub: 'dto.status',
            path: "Modules/{$this->module}/DTOs/{$this->model}StatusData.php",
            label: "{$this->model}StatusData",
            extra: [
                '{{ statusField }}' => $statusField,
            ],
        );

        $this->write(
            stub: 'action.status.toggle',
            path: "Modules/{$this->module}/Actions/Toggle{$this->model}StatusAction.php",
            label: "Toggle{$this->model}StatusAction",
            extra: [
                '{{ statusField }}' => $statusField,
                '{{ statusTimestampField }}' => $timestampField,
            ],
        );

        $this->write(
            stub: 'service.status',
            path: "Modules/{$this->module}/Services/{$this->model}StatusService.php",
            label: "{$this->model}StatusService",
        );

        $this->write(
            stub: 'controller.status',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}StatusController.php",
            label: "{$this->model}StatusController",
            extra: [
                '{{ statusField }}' => $statusField,
                '{{ statusTimestampField }}' => $timestampField,
            ],
        );

        $this->summary("Status controller for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }
}
