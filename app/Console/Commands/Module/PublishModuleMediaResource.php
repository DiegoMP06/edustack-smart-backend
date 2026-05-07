<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:publish-media-resource
    {module : Module name (e.g. Blog)}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Publish MediaResource utility and media mapper helper into a module')]
class PublishModuleMediaResource extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:publish-media-resource'];

    public function handle(): void
    {
        $module = Str::studly($this->argument('module'));

        $this->setup($module, 'Model');

        $this->write(
            stub: 'publish.media-resource',
            path: "Modules/{$module}/Http/Resources/MediaResource.php",
            label: 'MediaResource',
        );

        $this->write(
            stub: 'publish.media-mapper',
            path: "Modules/{$module}/Http/Resources/Concerns/MapsMedia.php",
            label: 'MapsMedia',
        );

        $this->summary("Media resource utilities for <fg=cyan>{$module}</> published.");
    }
}
