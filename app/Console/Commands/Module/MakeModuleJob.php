<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:job
    {module  : Module name (e.g. Classroom)}
    {name    : Job name (e.g. ProcessCourseUpload or ProcessCourseUploadJob)}
    {--model= : Model received by the job (e.g. Course)}
    {--chain : Add support for batchable job chaining}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a ShouldQueue job inside a module')]
class MakeModuleJob extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:job'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Job');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $model = Str::studly($this->option('model') ?? $module);

        $this->setup($module, $model);

        $jobPath = $directory === ''
            ? "Modules/{$module}/Jobs/{$name}.php"
            : "Modules/{$module}/Jobs/{$directory}/{$name}.php";

        $this->write(
            stub: $this->option('chain') ? 'job.chain' : 'job',
            path: $jobPath,
            label: Str::replaceEnd('Job', '', $name),
            extra: [
                '{{ name }}' => Str::replaceEnd('Job', '', $name),
            ],
        );

        $this->summary("Job <fg=cyan>{$name}</> generated.");

        $this->newLine();
        $this->line('  <fg=yellow>To dispatch:</>');
        $baseJobName = Str::replaceEnd('Job', '', $name);
        $this->line("  <fg=cyan>{$baseJobName}Job::dispatch(\${$this->modelVariable});</>");
        $this->line("  <fg=cyan>{$baseJobName}Job::dispatch(\${$this->modelVariable})->delay(now()->addMinutes(5));</>");
    }
}
