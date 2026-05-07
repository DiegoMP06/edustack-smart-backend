<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:policy
    {module : Module name (e.g. Classroom)}
    {model  : Model name (e.g. Course)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a policy with Spatie Permission roles inside a module')]
class MakeModulePolicy extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:policy'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $this->write(
            stub: 'policy',
            path: "Modules/{$this->module}/Policies/{$this->model}Policy.php",
            label: $this->model,
        );

        $this->summary("Policy <fg=cyan>{$this->model}Policy</> generated.");

        $this->newLine();
        $this->line('  <fg=yellow>Remember to register the policy in AuthServiceProvider:</>');
        $this->line("  <fg=cyan>\\App\\Models\\{$this->model}::class => \\App\\Modules\\{$this->module}\\Policies\\{$this->model}Policy::class,</>");
    }
}
