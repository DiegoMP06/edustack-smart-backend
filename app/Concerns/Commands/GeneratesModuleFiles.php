<?php

namespace App\Concerns\Commands;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GeneratesModuleFiles
{
    // Module
    protected string $moduleName;
    protected string $moduleLower;
    protected string $moduleKebab;
    protected string $moduleNamespace;
    protected string $modulePath;

    // Model
    protected ?string $modelName = null;
    protected ?string $modelVariable = null;
    protected ?string $modelPlural = null;
    protected ?string $modelLower = null;
    protected ?string $modelNamespace = null;
    protected ?string $modelPath = null;
    protected ?string $modelRouteName = null;
    protected ?string $modelRoutePrefix = null;

    // Generated
    protected array $generated = [];

    protected function toString(): string
    {
        return "
            moduleName: {$this->moduleName}
            modulePath: {$this->modulePath}
            moduleLower: {$this->moduleLower}
            moduleKebab: {$this->moduleKebab}
            moduleNamespace: {$this->moduleNamespace}
            modelName: {$this->modelName}
            modelVariable: {$this->modelVariable}
            modelPlural: {$this->modelPlural}
            modelLower: {$this->modelLower}
            modelNamespace: {$this->modelNamespace}
            modelPath: {$this->modelPath}
            modelRouteName: {$this->modelRouteName}
            modelRoutePrefix: {$this->modelRoutePrefix}
        ";
    }

    private function getRoutePrefix(string $path, string $className): string
    {
        $fullPath = "{$path}/{$className}";

        $route = collect(explode('/', $fullPath))
            ->map(fn($segment) => Str::kebab($segment))
            ->implode('/');

        $route = Str::plural($route);

        return $route;
    }

    private function getRouteName(string $path, string $className): string
    {
        $fullPath = $this->getRoutePrefix($path, $className);
        $routeName = collect(explode('/', $fullPath))
            ->implode('.');
        return $routeName;
    }

    private function normalizePath(string $path): string
    {
        $normalized = str_replace('\\', '/', trim($path));
        $normalized = trim($normalized, '/');
        $normalized = preg_replace('#/+#', '/', $normalized) ?? '';

        return $normalized;
    }

    /**
     * Summary of dividePath
     * @param string $path
     * @return array{className: string, fullPath: string, path: string}}
     */
    private function dividePath(string $path): array
    {
        $normalized = $this->normalizePath($path);

        $segments = array_values(
            array_filter(
                explode('/', $normalized),
                fn(string $segment): bool => $segment !== ''
            )
        );

        if ($segments === []) {
            return [
                'className' => $normalized,
                'fullPath' => $normalized,
                'path' => '',
            ];
        }

        $className = Str::studly(array_pop($segments));
        $newPath = (string) collect($segments)
            ->map(fn(string $segment): string => Str::studly($segment))
            ->implode('/');

        return [
            'className' => $className,
            'fullPath' => $normalized,
            'path' => (string) $newPath,
        ];
    }

    private function normalizeNamespace(string $namespace): string
    {
        return 'App\\' . str_replace('/', '\\', $namespace);
    }

    private function generateNamespace(string $path, ?string $folders = ''): string
    {
        $folders = $folders ? $folders : 'Modules';
        $directory = dirname("{$folders}/{$path}");

        return $this->normalizeNamespace($directory);
    }

    private function generateModuleAtt(string $module): void
    {
        $divide = $this->dividePath($module);
        $this->moduleName = Str::studly($divide['className']);
        $this->moduleLower = Str::lower($this->moduleName);
        $this->moduleKebab = Str::kebab($this->moduleName);
        $this->modulePath = "Modules/{$divide['fullPath']}";
        $this->moduleNamespace = $this->generateNamespace($this->moduleName, $this->modulePath);
    }

    private function generateModelAtt(string $model): void
    {
        $divide = $this->dividePath($model);
        $this->modelName = Str::studly($divide['className']);
        $this->modelVariable = Str::camel($this->modelName);
        $this->modelPlural = Str::camel(Str::plural($this->modelName));
        $this->modelLower = Str::lower($this->modelName);
        $this->modelPath = "Models/{$divide['fullPath']}";
        $this->modelRouteName = $this->getRouteName($divide['path'], $divide['className']);
        $this->modelRoutePrefix = $this->getRoutePrefix($divide['path'], $divide['className']);
        $this->modelNamespace = $this->generateNamespace($this->modelName, $this->modelPath);
    }

    protected function setup(string $module, ?string $model = null): void
    {
        $this->generateModuleAtt($module);


        if ($model) {
            $this->generateModelAtt($model);
        }
    }

    private function getFileLabel(string $label, string $labelPrefix): string
    {
        $studlyLabel = Str::studly($label);
        return Str::finish($studlyLabel, $labelPrefix);
    }

    /**
     * Summary of getFilePath
     * @param string $label
     * @param string $path
     * @param string $labelPrefix
     * @return array{className: string, fullPath: string, path: string}}
     */
    private function getFilePath(string $label, string $path, string $labelPrefix): array
    {
        $className = $this->getFileLabel($label, $labelPrefix);
        $relativePath = "{$this->modulePath}/{$path}/{$className}";
        $relativePath = $this->normalizePath($relativePath);

        return $this->dividePath($relativePath);
    }

    private function replaceFileAtt(string $contents, array $extra = []): string
    {
        $replacements = array_merge([
            '{{ module }}' => $this->moduleName,
            '{{ modulePath }}' => $this->modulePath,
            '{{ moduleNamespace }}' => $this->moduleNamespace,
            '{{ moduleLower }}' => $this->moduleLower,
            '{{ moduleKebab }}' => $this->moduleKebab,
            '{{ model }}' => $this->modelName ?? '',
            '{{ modelVariable }}' => $this->modelVariable ?? '',
            '{{ modelPlural }}' => $this->modelPlural ?? '',
            '{{ modelLower }}' => $this->modelLower ?? '',
            '{{ modelRouteName }}' => $this->modelRouteName ?? '',
            '{{ modelNamespace }}' => $this->modelNamespace ?? '',
            '{{ modelPath }}' => $this->modelPath ?? '',
            '{{ modelRoutePrefix }}' => $this->modelRoutePrefix ?? '',
            '{{ user }}' => User::class,
        ], $extra);

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $contents,
        );
    }

    protected function writeFile(string $stub, string $path, string $label, string $labelPrefix, array $extra = []): bool
    {
        $stubPath = base_path($this->normalizePath("stubs/{$stub}.stub"));
        $fileStructure = $this->getFilePath($label, $path, $labelPrefix);

        $fullPath = app_path($fileStructure['fullPath'] . '.php');
        $fileLabel = $fileStructure['className'];

        $fileNamespace = $this->normalizeNamespace($fileStructure['path']);
        $filePath = $this->normalizePath("app/{$fileStructure['fullPath']}.php");

        $force = $this->hasOption('force') && (bool) $this->option('force');
        $dryRun = $this->hasOption('dry-run') && (bool) $this->option('dry-run');

        $this->line("\n");

        if (!File::exists($stubPath)) {
            $this->error("\tStub not found: stubs/{$stub}.stub");
            return false;
        }

        if (File::exists($fullPath) && !$force) {
            $this->warn("\tSKIP   {$filePath}");
            $this->generated[] = [$fileLabel, '<fg=yellow>already exists</>'];
            return false;
        }

        if ($dryRun) {
            $action = File::exists($fullPath) ? 'OVERWRITE' : 'CREATE';
            $this->line("\t<fg=cyan>{$action}</> {$filePath} <fg=yellow>(dry-run)</>");
            $this->generated[] = [$fileLabel, "{$filePath} <fg=yellow>(dry-run)</>"];
            return true;
        }

        $wasExisting = File::exists($fullPath);

        File::ensureDirectoryExists(dirname($fullPath));

        $stubContents = $this->replaceFileAtt(File::get($stubPath), array_merge([
            '{{ class }}' => $fileLabel,
            '{{ namespace }}' => $fileNamespace,
        ], $extra));

        File::put($fullPath, $stubContents);

        $action = $wasExisting ? 'OVERWRITE' : 'CREATE';
        $this->line("\t<fg=green>{$action}</> {$filePath}");
        $this->generated[] = [$fileLabel, "{$filePath}"];

        return true;
    }

    protected function summary(string $title): void
    {
        $this->newLine();
        $this->info("✅   {$title}");
        $this->newLine();
        $this->table(['Class', 'Path'], $this->generated);
    }

    protected function validateField(string $fieldName): string|null
    {
        $field = $this->option($fieldName);

        if (!$field) {
            $field = $this->ask("¿Podría repetir el campo {$fieldName}?");
        }

        if (!$field) {
            $this->error("❌ Operación cancelada. El campo {$fieldName} es obligatorio.");
            return null;
        }

        return $field;
    }

    protected function setupWithModelNamespace(string $module, string $model, string $modelNamespace): void
    {
        $this->setup($module, $model);
        $this->modelNamespace = $modelNamespace;
    }


    protected function registerRouteInBootstrap(string $routePath): void
    {
        $appPath = base_path('bootstrap/app.php');
        $marker = '// [module-routes]';
        $requireLine = "            if (file_exists(base_path('{$routePath}'))) { require base_path('{$routePath}'); }";

        if (!File::exists($appPath)) {
            $this->warn('  SKIP   bootstrap/app.php not found');

            return;
        }

        $contents = File::get($appPath);

        if (str_contains($contents, $routePath)) {
            $this->warn('\tSKIP   Route already registered in bootstrap/app.php');

            return;
        }

        if (str_contains($contents, $marker)) {
            File::put($appPath, str_replace(
                $marker,
                $marker . "\n" . $requireLine,
                $contents,
            ));

            $this->line('  <fg=green>INJECT</> Route registered in bootstrap/app.php');

            return;
        }

        $routingAnchor = "health: '/up',";

        if (str_contains($contents, $routingAnchor)) {
            $replacement = "health: '/up',\n"
                . "        then: function (): void {\n"
                . "            // [module-routes]\n"
                . $requireLine . "\n"
                . '        },';

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

        if (!File::exists($moduleRoutePath)) {
            $this->warn('  SKIP   Module base route file does not exist for linking');

            return;
        }

        $contents = File::get($moduleRoutePath);

        if (str_contains($contents, $routePath)) {
            $this->warn('  SKIP   Route file already linked in base file');

            return;
        }

        File::put($moduleRoutePath, rtrim($contents) . "\n\n{$requireLine}\n");
        $this->line('  <fg=green>INJECT</> Route file linked in module base routes');
    }
}
