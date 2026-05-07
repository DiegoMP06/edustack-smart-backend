<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:model
    {module         : Module name (e.g. Classroom)}
    {model          : Model name (e.g. CourseProgress)}
    {--soft-deletes : Add SoftDeletes to the model}
    {--media        : Add Spatie MediaLibrary HasMedia trait}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a model with #[Fillable] inside a module')]
class MakeModuleModel extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:model'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $this->write(
            stub: $this->resolveStub(),
            path: "Models/{$this->module}/{$this->model}.php",
            label: $this->model,
        );

        $this->summary("Model <fg=cyan>{$this->model}</> generated.");
    }

    private function resolveStub(): string
    {
        $hasSoftDeletes = $this->option('soft-deletes');
        $hasMedia = $this->option('media');

        if ($hasSoftDeletes && $hasMedia) {
            return 'model.full';
        }

        if ($hasSoftDeletes) {
            return 'model.soft';
        }

        if ($hasMedia) {
            return 'model.media';
        }

        return 'model';
    }
}
