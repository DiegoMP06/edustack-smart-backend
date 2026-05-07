<?php

namespace App\Console\Commands\Module;

use App\Concerns\Commands\GeneratesModuleFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make-module:feature-routes
    {module : Module name (e.g. Blog)}
    {model  : Model name (e.g. Post)}
    {--content : Include content edit/update routes}
    {--status : Include status toggle route}
    {--media : Include media store/destroy routes}
    {--force : Overwrite existing files}
    {--dry-run : Preview changes without writing files}')]
#[Description('Generate route snippets for common module feature controllers')]
class MakeModuleFeatureRoutes extends Command
{
    use GeneratesModuleFiles;

    protected $aliases = ['mm:feature-routes'];

    public function handle(): void
    {
        $this->setup($this->argument('module'), $this->argument('model'));

        $withContent = (bool) $this->option('content');
        $withStatus = (bool) $this->option('status');
        $withMedia = (bool) $this->option('media');

        if (! $withContent && ! $withStatus && ! $withMedia) {
            $withContent = true;
            $withStatus = true;
            $withMedia = true;
        }

        $written = $this->write(
            stub: 'routes.features',
            path: "Modules/{$this->module}/routes/{$this->modelVariable}.features.php",
            label: "routes/{$this->modelVariable}.features.php",
            extra: [
                '{{ contentRoutes }}' => $withContent ? $this->contentRoutes() : '',
                '{{ statusRoutes }}' => $withStatus ? $this->statusRoute() : '',
                '{{ mediaRoutes }}' => $withMedia ? $this->mediaRoutes() : '',
            ],
        );

        if ($written) {
            $this->registerFeatureRouteInModuleFile();
        }

        $this->summary("Feature routes for <fg=cyan>{$this->module} / {$this->model}</> generated.");
    }

    private function contentRoutes(): string
    {
        return "Route::get('{$this->routePrefix}/{{$this->modelVariable}}/content/edit', [{$this->model}ContentController::class, 'edit'])->name('{$this->routeName}.content.edit');\n"
            ."Route::patch('{$this->routePrefix}/{{$this->modelVariable}}/content', [{$this->model}ContentController::class, 'update'])->name('{$this->routeName}.content.update');";
    }

    private function statusRoute(): string
    {
        return "Route::patch('{$this->routePrefix}/{{$this->modelVariable}}/status', {$this->model}StatusController::class)->name('{$this->routeName}.status');";
    }

    private function mediaRoutes(): string
    {
        return "Route::post('{$this->routePrefix}/{{$this->modelVariable}}/medias', [{$this->model}GalleryController::class, 'store'])->name('{$this->routeName}.medias.store');\n"
            ."Route::delete('{$this->routePrefix}/{{$this->modelVariable}}/medias/{media}', [{$this->model}GalleryController::class, 'destroy'])->name('{$this->routeName}.medias.destroy');";
    }
}
