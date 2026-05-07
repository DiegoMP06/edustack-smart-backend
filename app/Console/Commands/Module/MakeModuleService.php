<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

#[Signature('make-module:service
    {module   : Module name (e.g. Forms)}
    {name     : Service name (e.g. ConditionalLogicEvaluator or ConditionalLogicEvaluatorService)}
    {--actions=* : Actions to inject (e.g. --actions=EvaluateCondition)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a service class inside a module')]
class MakeModuleService extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:service'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Service');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $actions = collect($this->option('actions'))
            ->map(fn ($action) => Str::studly((string) $action))
            ->filter()
            ->unique()
            ->values();

        $this->setup($module, Str::replaceEnd('Service', '', $name));

        $servicePath = $directory === ''
            ? "Modules/{$module}/Services/{$name}.php"
            : "Modules/{$module}/Services/{$directory}/{$name}.php";

        $this->write(
            stub: 'service',
            path: $servicePath,
            label: Str::replaceEnd('Service', '', $name),
            extra: [
                '{{ imports }}' => $this->buildImports($module, $actions),
                '{{ constructor }}' => $this->buildConstructor($actions),
            ],
        );

        $this->summary("Service <fg=cyan>{$name}</> generated.");

        if ($actions->isNotEmpty()) {
            $this->line('   Injected actions:');
            $actions->each(fn ($action) => $this->line("   <fg=cyan>·</> {$action}Action"));
        }
    }

    private function buildImports(string $module, Collection $actions): string
    {
        if ($actions->isEmpty()) {
            return '';
        }

        return $actions
            ->map(fn ($action) => "use App\\Modules\\{$module}\\Actions\\{$action}Action;")
            ->join("\n");
    }

    private function buildConstructor(Collection $actions): string
    {
        if ($actions->isEmpty()) {
            return '';
        }

        $params = $actions
            ->map(fn ($action) => "        private {$action}Action \$".Str::camel($action).'Action,')
            ->join("\n");

        return "    public function __construct(\n{$params}\n    ) {}";
    }
}
