<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:listener
    {module : Module name (e.g. Orders)}
    {name   : Listener name (e.g. SendOrderConfirmation or SendOrderConfirmationListener)}
    {--event= : Event to listen to (e.g. OrderCreated or OrderCreatedEvent)}
    {--queue : Run in background queue}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate an event listener inside a module')]
class MakeModuleListener extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:listener'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Listener');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $event = Str::studly(Str::replaceEnd('Event', '', (string) ($this->option('event') ?? '')));
        $isQueued = $this->option('queue');

        $this->setup($module, $event ?: $module);

        $stub = $isQueued ? 'listener.queue' : 'listener';

        $listenerPath = $directory === ''
            ? "Modules/{$module}/Listeners/{$name}.php"
            : "Modules/{$module}/Listeners/{$directory}/{$name}.php";

        $this->write(
            stub: $stub,
            path: $listenerPath,
            label: $name,
            extra: [
                '{{ label }}' => $name,
            ],
        );

        $this->summary("Listener <fg=cyan>{$name}</> generated.");

        if ($event !== '') {
            $this->newLine();
            $this->line('  <fg=yellow>Remember to register in EventServiceProvider:</>');
            $this->line("  <fg=cyan>{$event}Event::class => [{$name}::class],</>");
        }
    }
}
