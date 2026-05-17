<?php

namespace App\Modules\Blog\Providers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Domain\Contracts\PostFormOptionsRepository;
use App\Modules\Blog\Domain\Contracts\PostReadRepository;
use App\Modules\Blog\Domain\Contracts\PostViewCounter;
use App\Modules\Blog\Domain\Contracts\PostWriteRepository;
use App\Modules\Blog\Domain\Policies\PostPolicy;
use App\Modules\Blog\Infrastructure\Analytics\CachePostViewCounter;
use App\Modules\Blog\Infrastructure\Repositories\EloquentPostFormOptionsRepository;
use App\Modules\Blog\Infrastructure\Repositories\EloquentPostReadRepository;
use App\Modules\Blog\Infrastructure\Repositories\EloquentPostWriteRepository;
use App\Modules\Media\Providers\MediaProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BlogProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerContracts();
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerRoutes();
        $this->registerMediaModels();
    }

    private function registerContracts(): void
    {
        $this->app->bind(PostReadRepository::class, EloquentPostReadRepository::class);
        $this->app->bind(PostWriteRepository::class, EloquentPostWriteRepository::class);
        $this->app->bind(PostViewCounter::class, CachePostViewCounter::class);
        $this->app->bind(PostFormOptionsRepository::class, EloquentPostFormOptionsRepository::class);
        $this->app->singleton(PostDataMapper::class);
    }

    private function registerPolicies(): void
    {
        Gate::policy(Post::class, PostPolicy::class);
    }

    /**
     * @fix Carga los 3 archivos de rutas desde el Provider.
     * Antes, web.php usaba file_exists() + require para cargar los otros,
     * lo cual es frágil y mezcla responsabilidades.
     */
    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(base_path('app/Modules/Blog/routes/web.php'));
        $this->loadRoutesFrom(base_path('app/Modules/Blog/routes/web.features.php'));
        $this->loadRoutesFrom(base_path('app/Modules/Blog/routes/api.php'));
    }

    /**
     * Registra los modelos del Blog en MediaProvider para
     * que el broadcasting de conversiones funcione sin acoplamiento.
     */
    private function registerMediaModels(): void
    {
        MediaProvider::registerModel(Post::class, 'post');
    }
}
