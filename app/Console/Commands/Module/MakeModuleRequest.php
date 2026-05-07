<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:request
    {module : Module name (e.g. Blog)}
    {name   : Request name (e.g. StorePost or StorePostRequest)}
    {--model= : Model used for authorization (e.g. Post)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a form request inside a module')]
class MakeModuleRequest extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:request'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Request');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $requestBaseName = Str::replaceEnd('Request', '', $name);
        $derivedModel = (string) preg_replace('/^(Store|Update|Delete|Destroy|Create|Show|Index)/', '', $requestBaseName);
        $model = Str::studly($this->option('model') ?: $derivedModel ?: $module);

        $this->setup($module, $model);

        $requestPath = $directory === ''
            ? "Modules/{$module}/Http/Requests/{$name}.php"
            : "Modules/{$module}/Http/Requests/{$directory}/{$name}.php";

        $this->write(
            stub: 'request',
            path: $requestPath,
            label: $requestBaseName,
            extra: [
                '{{ name }}' => $requestBaseName,
            ],
        );

        $this->summary("Request <fg=cyan>{$name}</> generated.");
    }
}
