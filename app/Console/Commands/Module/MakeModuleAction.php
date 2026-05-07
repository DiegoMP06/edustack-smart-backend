<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:action
    {module : Module name (e.g. Classroom)}
    {name   : Action name (e.g. EnrollStudent or EnrollStudentAction)}
    {--model= : Model used as input/output (e.g. Enrollment)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate an action class inside a module')]
class MakeModuleAction extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:action'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Action');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $model = Str::studly($this->option('model') ?? $module);

        $this->setup($module, $model);

        $actionPath = $directory === ''
            ? "Modules/{$module}/Actions/{$name}.php"
            : "Modules/{$module}/Actions/{$directory}/{$name}.php";

        $this->write(
            stub: 'action',
            path: $actionPath,
            label: Str::replaceEnd('Action', '', $name),
        );

        $this->summary("Action <fg=cyan>{$name}</> generated.");
    }
}
