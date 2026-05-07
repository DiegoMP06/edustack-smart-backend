<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:query
    {module : Module name (e.g. Blog)}
    {name   : Query name (e.g. Post)}
    {--model= : Related model name (e.g. Post)}
    {--api-queryable : Generate query using App\\Concerns\\ApiQueryable}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a query filter with Spatie Query Builder inside a module')]
class MakeModuleQuery extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:query'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Query');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $queryBaseName = Str::replaceEnd('Query', '', $name);
        $model = Str::studly((string) ($this->option('model') ?: $queryBaseName));

        $this->setup($module, $model);

        $queryPath = $directory === ''
            ? "Modules/{$module}/Queries/{$name}.php"
            : "Modules/{$module}/Queries/{$directory}/{$name}.php";

        $queryStub = $this->option('api-queryable') ? 'query-filter.api-queryable' : 'query-filter';

        $this->write(
            stub: $queryStub,
            path: $queryPath,
            label: $queryBaseName,
        );

        $this->summary("Query <fg=cyan>{$name}</> generated.");
    }
}
