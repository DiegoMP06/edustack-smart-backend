<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:event
    {module : Module name (e.g. Orders)}
    {name   : Event name (e.g. OrderCreated or OrderCreatedEvent)}
    {--model= : Model that dispatches the event (e.g. Order)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate an event inside a module')]
class MakeModuleEvent extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:event'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Event');
        $eventName = $segments['className'];
        $directory = $segments['directory'];
        $model = Str::studly($this->option('model') ?? $module);

        $this->setup($module, $model);

        $eventPath = $directory === ''
            ? "Modules/{$module}/Events/{$eventName}.php"
            : "Modules/{$module}/Events/{$directory}/{$eventName}.php";

        $this->write(
            stub: 'event',
            path: $eventPath,
            label: $eventName,
            extra: [
                '{{ label }}' => $eventName,
            ],
        );

        $this->summary("Event <fg=cyan>{$eventName}</> generated.");

        $this->newLine();
        $this->line('  <fg=yellow>To dispatch the event:</>');
        $this->line("  <fg=cyan>{$eventName}::dispatch(\${$this->modelVariable});</>");
    }
}
