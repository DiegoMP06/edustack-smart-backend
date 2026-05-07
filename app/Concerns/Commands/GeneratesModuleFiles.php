<?php

namespace App\Concerns\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GeneratesModuleFiles
{
    protected string $module;

    protected string $model;

    protected string $modelVariable;

    protected string $modelPlural;

    protected string $modelLower;

    protected string $routeName;

    protected string $routePrefix;

    protected string $moduleLower;

    protected string $moduleKebab;

    protected string $storeRequest;

    protected string $updateRequest;

    protected string $updateContentRequest;

    protected string $modelNamespace;

    protected array $generated = [];

    protected function setup(string $module, string $model): void
    {
        $this->module = Str::studly($module);
        $this->model = Str::studly($model);
        $this->modelVariable = Str::camel($this->model);
        $this->modelPlural = Str::camel(Str::plural($this->model));
        $this->modelLower = Str::lower($this->model);
        $this->routeName = Str::kebab(Str::plural($this->model));
        $this->routePrefix = Str::kebab(Str::plural($this->model));
        $this->moduleLower = Str::lower($this->module);
        $this->moduleKebab = Str::kebab($this->module);
        $this->storeRequest = "Store{$this->model}Request";
        $this->updateRequest = "Update{$this->model}Request";
        $this->updateContentRequest = "Update{$this->model}ContentRequest";
        $this->modelNamespace = "App\\Models\\{$this->module}\\{$this->model}";
    }

    protected function setupWithModelNamespace(string $module, string $model, string $modelNamespace): void
    {
        $this->setup($module, $model);
        $this->modelNamespace = $modelNamespace;
    }

    /**
     * @return array{directory: string, className: string}
     */
    protected function resolveClassSegments(string $name, string $suffix = ''): array
    {
        $normalized = str_replace('\\', '/', trim($name));
        $normalized = trim($normalized, '/');
        $normalized = preg_replace('#/+#', '/', $normalized) ?? '';

        $segments = array_values(array_filter(explode('/', $normalized), fn (string $segment): bool => $segment !== ''));

        if ($segments === []) {
            return ['directory' => '', 'className' => $suffix === '' ? 'Generated' : "Generated{$suffix}"];
        }

        $className = Str::studly(array_pop($segments));

        if ($suffix !== '') {
            $className = Str::replaceEnd($suffix, '', $className).$suffix;
        }

        $directory = collect($segments)
            ->map(fn (string $segment): string => Str::studly($segment))
            ->implode('/');

        return [
            'directory' => $directory,
            'className' => $className,
        ];
    }

    protected function write(string $stub, string $path, string $label, array $extra = []): bool
    {
        $fullPath = app_path($path);
        $stubPath = base_path("stubs/module/{$stub}.stub");
        $resolvedLabel = $this->resolveGeneratedLabel($label, $path);
        $force = $this->hasOption('force') && (bool) $this->option('force');
        $dryRun = $this->hasOption('dry-run') && (bool) $this->option('dry-run');

        if (! File::exists($stubPath)) {
            $this->error("  Stub not found: stubs/module/{$stub}.stub");

            return false;
        }

        if (File::exists($fullPath) && ! $force) {
            $this->warn("  SKIP   {$path}");
            $this->generated[] = [$resolvedLabel, '<fg=yellow>already exists</>'];

            return false;
        }

        if ($dryRun) {
            $action = File::exists($fullPath) ? 'OVERWRITE' : 'CREATE';
            $this->line("  <fg=cyan>{$action}</> {$path} <fg=yellow>(dry-run)</>");
            $this->generated[] = [$resolvedLabel, "app/{$path} <fg=yellow>(dry-run)</>"];

            return true;
        }

        $wasExisting = File::exists($fullPath);

        File::ensureDirectoryExists(dirname($fullPath));
        $stubContents = $this->replace(File::get($stubPath), array_merge([
            '{{ label }}' => $label,
            '{{ name }}' => $label,
        ], $extra));

        File::put($fullPath, $this->syncNamespaceWithPath($stubContents, $path));

        $action = $wasExisting ? 'OVERWRITE' : 'CREATE';
        $this->line("  <fg=green>{$action}</> {$path}");
        $this->generated[] = [$resolvedLabel, "app/{$path}"];

        return true;
    }

    protected function resolveGeneratedLabel(string $label, string $path): string
    {
        if ($label !== $this->model) {
            return $label;
        }

        $filename = pathinfo($path, PATHINFO_FILENAME);

        if ($filename === '') {
            return $label;
        }

        return Str::studly($filename);
    }

    protected function replace(string $contents, array $extra = []): string
    {
        $replacements = array_merge([
            '{{ module }}' => $this->module,
            '{{ model }}' => $this->model,
            '{{ name }}' => $this->model,
            '{{ label }}' => $this->model,
            '{{ modelVariable }}' => $this->modelVariable,
            '{{ modelPlural }}' => $this->modelPlural,
            '{{ modelLower }}' => $this->modelLower,
            '{{ routeName }}' => $this->routeName,
            '{{ routePrefix }}' => $this->routePrefix,
            '{{ moduleLower }}' => $this->moduleLower,
            '{{ moduleKebab }}' => $this->moduleKebab,
            '{{ storeRequest }}' => $this->storeRequest,
            '{{ updateRequest }}' => $this->updateRequest,
            '{{ updateContentRequest }}' => $this->updateContentRequest,
            '{{ user }}' => 'User',
            '{{ modelNamespace }}' => $this->modelNamespace,
        ], $extra);

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $contents,
        );
    }

    protected function syncNamespaceWithPath(string $contents, string $path): string
    {
        $directory = dirname($path);

        if ($directory === '.' || str_contains($directory, '/routes')) {
            return $contents;
        }

        if (! str_contains($contents, 'namespace ')) {
            return $contents;
        }

        $namespace = 'App\\'.str_replace('/', '\\', $directory);

        return (string) preg_replace('/^namespace\s+[^;]+;/m', "namespace {$namespace};", $contents, 1);
    }

    protected function summary(string $title): void
    {
        $this->newLine();
        $this->info("✅  {$title}");
        $this->newLine();
        $this->table(['Class', 'Path'], $this->generated);
    }

    protected function registerRouteInBootstrap(string $routePath): void
    {
        $appPath = base_path('bootstrap/app.php');
        $marker = '// [module-routes]';
        $requireLine = "            if (file_exists(base_path('{$routePath}'))) { require base_path('{$routePath}'); }";

        if (! File::exists($appPath)) {
            $this->warn('  SKIP   bootstrap/app.php not found');

            return;
        }

        $contents = File::get($appPath);

        if (str_contains($contents, $routePath)) {
            $this->warn('  SKIP   Route already registered in bootstrap/app.php');

            return;
        }

        if (str_contains($contents, $marker)) {
            File::put($appPath, str_replace(
                $marker,
                $marker."\n".$requireLine,
                $contents,
            ));

            $this->line('  <fg=green>INJECT</> Route registered in bootstrap/app.php');

            return;
        }

        $routingAnchor = "health: '/up',";

        if (str_contains($contents, $routingAnchor)) {
            $replacement = "health: '/up',\n"
                ."        then: function (): void {\n"
                ."            // [module-routes]\n"
                .$requireLine."\n"
                .'        },';

            File::put($appPath, str_replace($routingAnchor, $replacement, $contents));

            $this->line('  <fg=green>INJECT</> Route registered in bootstrap/app.php');

            return;
        }

        $this->newLine();
        $this->warn('  MANUAL Could not register route automatically in bootstrap/app.php.');
    }

    protected function registerFeatureRouteInModuleFile(): void
    {
        $this->registerRouteInModuleFile("app/Modules/{$this->module}/routes/{$this->modelVariable}.features.php");
    }

    protected function registerRestRouteInModuleFile(): void
    {
        $this->registerRouteInModuleFile("app/Modules/{$this->module}/routes/{$this->modelVariable}.rest.php");
    }

    protected function registerRouteInModuleFile(string $routePath): void
    {
        $moduleRoutePath = app_path("Modules/{$this->module}/routes/{$this->modelVariable}.php");
        $requireLine = "if (file_exists(base_path('{$routePath}'))) { require base_path('{$routePath}'); }";

        if (! File::exists($moduleRoutePath)) {
            $this->warn('  SKIP   Module base route file does not exist for linking');

            return;
        }

        $contents = File::get($moduleRoutePath);

        if (str_contains($contents, $routePath)) {
            $this->warn('  SKIP   Route file already linked in base file');

            return;
        }

        File::put($moduleRoutePath, rtrim($contents)."\n\n{$requireLine}\n");
        $this->line('  <fg=green>INJECT</> Route file linked in module base routes');
    }
}
