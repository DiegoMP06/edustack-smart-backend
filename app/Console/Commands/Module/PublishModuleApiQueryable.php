<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:publish-api-queryable
    {module : Module name (e.g. Blog)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Publish ApiQueryable utility and dependencies into a module')]
class PublishModuleApiQueryable extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:publish-api-queryable'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));

        $this->setup($module, 'Model');

        $this->write(
            stub: 'publish.api-queryable',
            path: "Modules/{$module}/Concerns/ApiQueryable.php",
            label: 'ApiQueryable',
        );

        $this->write(
            stub: 'publish.global-scout-filter',
            path: "Modules/{$module}/Queries/Filters/GlobalScoutFilter.php",
            label: 'GlobalScoutFilter',
        );

        $this->summary("Api query utilities for <fg=cyan>{$module}</> published.");
    }
}
