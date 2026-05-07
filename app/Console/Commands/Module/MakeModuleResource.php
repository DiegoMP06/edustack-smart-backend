<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:resource
    {module : Module name (e.g. Blog)}
    {name   : Resource name (e.g. Post or PostResource)}
    {--collection : Also generate a collection class}
    {--media : Generate resource with media mapping helper based on MediaResource}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a JSON resource or collection inside a module')]
class MakeModuleResource extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:resource'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Resource');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $resourceBaseName = Str::replaceEnd('Resource', '', $name);

        $this->setup($module, $resourceBaseName);

        $resourcePath = $directory === ''
            ? "Modules/{$module}/Http/Resources/{$name}.php"
            : "Modules/{$module}/Http/Resources/{$directory}/{$name}.php";

        $resourceStub = $this->option('media') ? 'resource.media' : 'resource';

        $this->write(
            stub: $resourceStub,
            path: $resourcePath,
            label: $resourceBaseName,
        );

        if ($this->option('collection')) {
            $collectionPath = $directory === ''
                ? "Modules/{$module}/Http/Resources/{$resourceBaseName}Collection.php"
                : "Modules/{$module}/Http/Resources/{$directory}/{$resourceBaseName}Collection.php";

            $this->write(
                stub: 'collection',
                path: $collectionPath,
                label: $resourceBaseName,
            );
        }

        $this->summary("Resource <fg=cyan>{$name}</> generated.");
    }
}
