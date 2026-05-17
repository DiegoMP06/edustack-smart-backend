<?php

namespace App\Modules\Projects\Providers;

use App\Models\Projects\Project;
use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Domain\Contracts\ProjectFormOptionsRepository;
use App\Modules\Projects\Domain\Contracts\ProjectReadRepository;
use App\Modules\Projects\Domain\Contracts\ProjectWriteRepository;
use App\Modules\Projects\Domain\Policies\ProjectPolicy;
use App\Modules\Projects\Infrastructure\Repositories\EloquentProjectFormOptionsRepository;
use App\Modules\Projects\Infrastructure\Repositories\EloquentProjectReadRepository;
use App\Modules\Projects\Infrastructure\Repositories\EloquentProjectWriteRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectsProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerContracts();
        $this->registerMappers();
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerRoutes();
        $this->registerMediaModels();
    }

    protected function registerContracts(): void
    {
        $this->app->bind(ProjectReadRepository::class, EloquentProjectReadRepository::class);
        $this->app->bind(ProjectWriteRepository::class, EloquentProjectWriteRepository::class);
        $this->app->bind(ProjectFormOptionsRepository::class, EloquentProjectFormOptionsRepository::class);
    }

    protected function registerMappers(): void
    {
        $this->app->singleton(ProjectDataMapper::class, fn () => new ProjectDataMapper);
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('projects')
            ->name('projects.')
            ->group(base_path('app/Modules/Projects/routes/project.php'));

        Route::middleware(['web', 'auth'])
            ->prefix('projects')
            ->name('projects.')
            ->group(base_path('app/Modules/Projects/routes/project.features.php'));

        Route::middleware(['api'])
            ->prefix('api/projects')
            ->name('api.projects.')
            ->group(base_path('app/Modules/Projects/routes/project.rest.php'));
    }

    protected function registerMediaModels(): void
    {
        Media::resolveRelationUsing('project', function (Media $media) {
            return $media->morphTo('model', 'model_type', 'model_id')
                ->where('model_type', Project::class);
        });
    }
}
