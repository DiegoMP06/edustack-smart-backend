<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:media
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--collection=gallery : Media collection name}
    {--request=StoreModelMediaRequest : Request class for store action}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a media gallery controller for a module model')]
class MakeModuleMedia extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:media'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $collection = (string) $this->option('collection');
        $requestClass = (string) $this->option('request');

        $this->write(
            stub: 'dto.media.store',
            path: "Modules/{$this->module}/DTOs/{$this->model}MediaData.php",
            label: "{$this->model}MediaData",
        );

        $this->write(
            stub: 'dto.media.destroy',
            path: "Modules/{$this->module}/DTOs/{$this->model}MediaDeletionData.php",
            label: "{$this->model}MediaDeletionData",
        );

        $this->write(
            stub: 'action.media.store',
            path: "Modules/{$this->module}/Actions/Store{$this->model}MediaAction.php",
            label: "Store{$this->model}MediaAction",
            extra: [
                '{{ mediaCollection }}' => $collection,
            ],
        );

        $this->write(
            stub: 'action.media.destroy',
            path: "Modules/{$this->module}/Actions/Delete{$this->model}MediaAction.php",
            label: "Delete{$this->model}MediaAction",
        );

        $this->write(
            stub: 'service.media',
            path: "Modules/{$this->module}/Services/{$this->model}MediaService.php",
            label: "{$this->model}MediaService",
        );

        $this->write(
            stub: 'controller.media',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}GalleryController.php",
            label: "{$this->model}GalleryController",
            extra: [
                '{{ mediaCollection }}' => $collection,
                '{{ mediaStoreRequest }}' => $requestClass,
            ],
        );

        $this->summary("Media controller for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }
}
