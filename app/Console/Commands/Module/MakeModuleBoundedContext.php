<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('make-module:crud-all
    {module : Module name (e.g. Blog)}
    {model : Model name (e.g. Post)}
    {--minimal : Generate only model + dto + actions + service + controller}
    {--no-model : Skip model generation}
    {--no-requests : Skip store/update requests}
    {--no-resources : Skip API resources}
    {--no-queries : Skip query builder class}
    {--api-queryable : Use ApiQueryable-based query filter stub}
    {--media-resource : Use MediaResource-based resource stub}
    {--no-routes : Skip module routes file}
    {--no-policy : Skip policy}
    {--no-content : Skip content controller/request}
    {--no-status : Skip invokable status controller}
    {--no-media : Skip gallery media controller}
    {--no-features-routes : Skip feature routes snippet}
    {--no-rest-routes : Skip rest routes snippet}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate a full module bounded context with optional feature scaffolding')]
class MakeModuleBoundedContext extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:crud-all'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $isMinimal = (bool) $this->option('minimal');

        if (! $this->option('no-model')) {
            $this->generateModel();
        }

        $this->generateDto();
        $this->generateActions();
        $this->generateService();
        $this->generateController();

        if ($isMinimal) {
            $this->summary("Bounded context <fg=cyan>{$this->module} / {$this->model}</> generated (minimal).");

            return;
        }

        if (! $this->option('no-requests')) {
            $this->generateRequests();
        }

        if (! $this->option('no-resources')) {
            $this->generateResources();
        }

        if (! $this->option('no-queries')) {
            $this->generateQueryFilter();
        }

        if (! $this->option('no-routes')) {
            $this->generateRoutes();
        }

        if (! $this->option('no-policy')) {
            $this->generatePolicy();
        }

        if (! $this->option('no-content')) {
            $this->generateContentController();
            $this->generateContentRequest();
        }

        if (! $this->option('no-status')) {
            $this->generateStatusController();
        }

        if (! $this->option('no-media')) {
            $this->generateMediaController();
        }

        if (! $this->option('no-features-routes')) {
            $this->generateFeatureRoutes();
        }

        if (! $this->option('no-rest-routes')) {
            $this->generateRestRoutes();
        }

        $this->summary("Bounded context <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }

    private function generateModel(): void
    {
        $this->write(
            stub: 'model',
            path: "Models/{$this->module}/{$this->model}.php",
            label: $this->model,
        );
    }

    private function generateDto(): void
    {
        $this->write(
            stub: 'dto',
            path: "Modules/{$this->module}/DTOs/{$this->model}Data.php",
            label: $this->model,
        );
    }

    private function generateActions(): void
    {
        foreach (['create', 'update', 'delete', 'get'] as $type) {
            $name = Str::studly($type).$this->model.'Action';

            $this->write(
                stub: "action.{$type}",
                path: "Modules/{$this->module}/Actions/{$name}.php",
                label: $name,
            );
        }
    }

    private function generateService(): void
    {
        $this->write(
            stub: 'service.crud',
            path: "Modules/{$this->module}/Services/{$this->model}Service.php",
            label: $this->model,
        );
    }

    private function generateController(): void
    {
        $this->write(
            stub: 'controller',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}Controller.php",
            label: $this->model,
            extra: [
                '{{ label }}' => $this->model,
            ],
        );
    }

    private function generateRequests(): void
    {
        foreach (['store', 'update'] as $type) {
            $name = Str::studly($type).$this->model.'Request';

            $this->write(
                stub: "request.{$type}",
                path: "Modules/{$this->module}/Http/Requests/{$name}.php",
                label: $name,
            );
        }
    }

    private function generateResources(): void
    {
        $resourceStub = $this->option('media-resource') ? 'resource.media' : 'resource';

        $this->write(
            stub: $resourceStub,
            path: "Modules/{$this->module}/Http/Resources/{$this->model}Resource.php",
            label: $this->model,
        );

        $this->write(
            stub: 'collection',
            path: "Modules/{$this->module}/Http/Resources/{$this->model}Collection.php",
            label: $this->model,
        );
    }

    private function generateQueryFilter(): void
    {
        $queryStub = $this->option('api-queryable') ? 'query-filter.api-queryable' : 'query-filter';

        $this->write(
            stub: $queryStub,
            path: "Modules/{$this->module}/Queries/{$this->model}Query.php",
            label: $this->model,
        );
    }

    private function generateRoutes(): void
    {
        $written = $this->write(
            stub: 'routes',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.php",
            label: "routes/{$this->modelVariable}.php",
        );

        if ($written) {
            $this->registerRoutes();
        }
    }

    private function generatePolicy(): void
    {
        $this->write(
            stub: 'policy',
            path: "Modules/{$this->module}/Policies/{$this->model}Policy.php",
            label: $this->model,
        );
    }

    private function registerRoutes(): void
    {
        $this->registerRouteInBootstrap("app/Modules/{$this->module}/routes/{$this->modelVariable}.php");
    }

    private function generateContentController(): void
    {
        $this->write(
            stub: 'dto.content',
            path: "Modules/{$this->module}/DTOs/{$this->model}ContentData.php",
            label: "{$this->model}ContentData",
        );

        $this->write(
            stub: 'action.content.update',
            path: "Modules/{$this->module}/Actions/Update{$this->model}ContentAction.php",
            label: "Update{$this->model}ContentAction",
        );

        $this->write(
            stub: 'service.content',
            path: "Modules/{$this->module}/Services/{$this->model}ContentService.php",
            label: "{$this->model}ContentService",
        );

        $this->write(
            stub: 'controller.content',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}ContentController.php",
            label: "{$this->model}ContentController",
            extra: [
                '{{ inertiaPage }}' => "{$this->moduleKebab}/{$this->modelLower}-content",
            ],
        );
    }

    private function generateContentRequest(): void
    {
        $this->write(
            stub: 'request.content.update',
            path: "Modules/{$this->module}/Http/Requests/{$this->updateContentRequest}.php",
            label: $this->updateContentRequest,
        );
    }

    private function generateStatusController(): void
    {
        $this->write(
            stub: 'dto.status',
            path: "Modules/{$this->module}/DTOs/{$this->model}StatusData.php",
            label: "{$this->model}StatusData",
            extra: [
                '{{ statusField }}' => 'is_published',
            ],
        );

        $this->write(
            stub: 'action.status.toggle',
            path: "Modules/{$this->module}/Actions/Toggle{$this->model}StatusAction.php",
            label: "Toggle{$this->model}StatusAction",
            extra: [
                '{{ statusField }}' => 'is_published',
                '{{ statusTimestampField }}' => 'published_at',
            ],
        );

        $this->write(
            stub: 'service.status',
            path: "Modules/{$this->module}/Services/{$this->model}StatusService.php",
            label: "{$this->model}StatusService",
        );

        $this->write(
            stub: 'controller.status',
            path: "Modules/{$this->module}/Http/Controllers/{$this->model}StatusController.php",
            label: "{$this->model}StatusController",
            extra: [
                '{{ statusField }}' => 'is_published',
                '{{ statusTimestampField }}' => 'published_at',
            ],
        );
    }

    private function generateMediaController(): void
    {
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
                '{{ mediaCollection }}' => 'gallery',
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
                '{{ mediaCollection }}' => 'gallery',
                '{{ mediaStoreRequest }}' => 'StoreModelMediaRequest',
            ],
        );
    }

    private function generateFeatureRoutes(): void
    {
        $written = $this->write(
            stub: 'routes.features',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.features.php",
            label: "routes/{$this->modelVariable}.features.php",
            extra: [
                '{{ contentRoutes }}' => "Route::get('{$this->routePrefix}/{{$this->modelVariable}}/content/edit', [{$this->model}ContentController::class, 'edit'])->name('{$this->routeName}.content.edit');\nRoute::patch('{$this->routePrefix}/{{$this->modelVariable}}/content', [{$this->model}ContentController::class, 'update'])->name('{$this->routeName}.content.update');",
                '{{ statusRoutes }}' => "Route::patch('{$this->routePrefix}/{{$this->modelVariable}}/status', {$this->model}StatusController::class)->name('{$this->routeName}.status');",
                '{{ mediaRoutes }}' => "Route::post('{$this->routePrefix}/{{$this->modelVariable}}/medias', [{$this->model}GalleryController::class, 'store'])->name('{$this->routeName}.medias.store');\nRoute::delete('{$this->routePrefix}/{{$this->modelVariable}}/medias/{media}', [{$this->model}GalleryController::class, 'destroy'])->name('{$this->routeName}.medias.destroy');",
            ],
        );

        if ($written) {
            $this->registerFeatureRouteInModuleFile();
        }
    }

    private function generateRestRoutes(): void
    {
        $written = $this->write(
            stub: 'routes.rest',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.rest.php",
            label: "routes/{$this->modelVariable}.rest.php",
        );

        if ($written) {
            $this->registerRestRouteInModuleFile();
        }
    }
}
