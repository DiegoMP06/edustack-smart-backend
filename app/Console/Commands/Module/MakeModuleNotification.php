<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:notification
    {module : Module name (e.g. Orders)}
    {name   : Notification name (e.g. OrderShipped or OrderShippedNotification)}
    {--model= : Related model (e.g. Order)}
    {--channels=* : Channels (mail, database, sms, slack)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a notification inside a module')]
class MakeModuleNotification extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:notification'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));
        $segments = $this->resolveClassSegments((string) $this->argument('name'), 'Notification');
        $name = $segments['className'];
        $directory = $segments['directory'];
        $model = Str::studly($this->option('model') ?? $module);
        $channels = collect($this->option('channels'))
            ->map(fn (string $channel) => Str::of($channel)->trim()->lower()->toString())
            ->filter()
            ->unique()
            ->values();

        $channelList = $channels->isNotEmpty()
            ? '['.$channels->map(fn (string $channel) => "'{$channel}'")->join(', ').']'
            : "['mail']";

        $this->setup($module, $model);

        $notificationPath = $directory === ''
            ? "Modules/{$module}/Notifications/{$name}.php"
            : "Modules/{$module}/Notifications/{$directory}/{$name}.php";

        $this->write(
            stub: 'notification',
            path: $notificationPath,
            label: $name,
            extra: [
                '{{ channels }}' => $channelList,
            ],
        );

        $this->summary("Notification <fg=cyan>{$name}</> generated.");

        if ($channels->isNotEmpty()) {
            $this->newLine();
            $this->line('  <fg=yellow>Configured channels:</>');
            foreach ($channels as $channel) {
                $this->line("  <fg=cyan>·</> {$channel}");
            }
        }
    }
}
