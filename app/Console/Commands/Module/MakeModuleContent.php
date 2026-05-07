<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:content
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--request : Generate Update<Model>ContentRequest}
    {--inertia= : Inertia page path (e.g. blog/post-content)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a content controller for a module model')]
class MakeModuleContent extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:content'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $inertiaPage = $this->option('inertia') ?: "{$this->moduleKebab}/{$this->modelLower}-content";

        $this->write(
            stub: 'dto.content',
            path: "Modules/{$this->module}/DTOs/{$this->model}ContentData.php",
            label: "{$this->model}ContentData",
        );

        $this->write(
            stub: 'action.content.update',
            path: "Modules/{$this->module}/Actions/Update{$this->model}ContentAction.php",
            label: "Update{$this->model}ContentAction",
        );

        $this->write(
            stub: 'service.content',
            path: "Modules/{$this->module}/Services/{$this->model}ContentService.php",
            label: "{$this->model}ContentService",
        );

        $this->write(
            stub: 'controller.content',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}ContentController.php",
            label: "{$this->model}ContentController",
            extra: [
                '{{ inertiaPage }}' => $inertiaPage,
            ],
        );

        if ($this->option('request')) {
            $this->write(
                stub: 'request.content.update',
                path: "Modules/{$this->module}/Http/Requests/{$this->updateContentRequest}.php",
                label: $this->updateContentRequest,
            );
        }

        $this->summary("Content scaffolding for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }
}
