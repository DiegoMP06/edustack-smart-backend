<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:dto
    {module : Module name (e.g. Blog)}
    {name   : DTO name (e.g. CreatePostData or CreatePost)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a readonly DTO (Data Transfer Object) inside a module')]
class MakeModuleDTO extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:dto'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Data');
        $dtoClassName = $segments['className'];
        $directory = $segments['directory'];
        $dtoName = Str::replaceEnd('Data', '', $dtoClassName);

        $this->setup($module, $dtoName);

        $dtoPath = $directory === ''
            ? "Modules/{$module}/DTOs/{$dtoClassName}.php"
            : "Modules/{$module}/DTOs/{$directory}/{$dtoClassName}.php";

        $this->write(
            stub: 'dto',
            path: $dtoPath,
            label: $dtoName,
            extra: [
                '{{ label }}' => $dtoName,
            ],
        );

        $this->summary("DTO <fg=cyan>{$dtoClassName}</> generated.");
    }
}
