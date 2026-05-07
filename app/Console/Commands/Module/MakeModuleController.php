<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:controller
    {module : Module name (e.g. Blog)}
    {name   : Controller name (e.g. Post)}
    {--model= : Related model name (e.g. Post)}
    {--parent= : Parent model name for nested resources (e.g. Project)}
    {--plain : Generate an empty plain controller}
    {--modelless : Generate a controller without model/request imports}
    {--api : Generate API resource controller}
    {--api-resource : Generate API resource controller (index/store/show/update/destroy)}
    {--resource : Generate full resource controller}
    {--invokable : Generate invokable controller}
    {--singleton : Generate singleton resource controller}
    {--nested-resource : Generate nested resource controller}
    {--crud : Generate full CRUD controller}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a controller inside a module')]
class MakeModuleController extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:controller'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Controller');
        $controllerName = $segments['className'];
        $directory = $segments['directory'];
        $controllerBaseName = Str::replaceEnd('Controller', '', $controllerName);
        $model = Str::studly((string) ($this->option('model') ?: $controllerBaseName));
        $parentModel = Str::studly((string) ($this->option('parent') ?: 'Parent'));
        $parentVariable = Str::camel($parentModel);

        $this->setup($module, $model);

        $stub = $this->resolveStub();

        $controllerPath = $directory === ''
            ? "Modules/{$module}/Http/Controllers/{$controllerName}.php"
            : "Modules/{$module}/Http/Controllers/{$directory}/{$controllerName}.php";

        $this->write(
            stub: $stub,
            path: $controllerPath,
            label: $controllerBaseName,
            extra: [
                '{{ parentModel }}' => $parentModel,
                '{{ parentVariable }}' => $parentVariable,
            ],
        );

        $this->summary("Controller <fg=cyan>{$controllerName}</> generated.");
    }

    private function resolveStub(): string
    {
        $resourceOptions = array_filter([
            'plain' => (bool) $this->option('plain'),
            'modelless' => (bool) $this->option('modelless'),
            'crud' => (bool) $this->option('crud'),
            'invokable' => (bool) $this->option('invokable'),
            'singleton' => (bool) $this->option('singleton'),
            'nested-resource' => (bool) $this->option('nested-resource'),
            'api-resource' => (bool) $this->option('api-resource'),
            'api' => (bool) $this->option('api'),
        ]);

        if (count($resourceOptions) > 1) {
            $selected = array_key_first($resourceOptions);
            $this->warn("  WARN   Multiple controller styles were provided, using --{$selected}.");
        }

        if ($this->option('plain')) {
            return 'controller.plain';
        }

        if ($this->option('modelless')) {
            return 'controller.modelless';
        }

        if ($this->option('crud')) {
            return 'controller.crud';
        }

        if ($this->option('invokable')) {
            return 'controller.invokable';
        }

        if ($this->option('singleton')) {
            return 'controller.singleton';
        }

        if ($this->option('nested-resource')) {
            return 'controller.nested';
        }

        if ($this->option('api-resource')) {
            return 'controller.api-resource';
        }

        if ($this->option('api')) {
            return 'controller.api';
        }

        if ($this->option('resource')) {
            return 'controller';
        }

        return 'controller';
    }
}
