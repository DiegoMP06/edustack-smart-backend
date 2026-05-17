<?php

namespace App\Modules\Forms\Providers;

use App\Models\Forms\Form;
use App\Modules\Forms\Application\Support\FormDataMapper;
use App\Modules\Forms\Domain\Contracts\FormFormOptionsRepository;
use App\Modules\Forms\Domain\Contracts\FormReadRepository;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;
use App\Modules\Forms\Domain\Policies\FormPolicy;
use App\Modules\Forms\Infrastructure\Repositories\EloquentFormFormOptionsRepository;
use App\Modules\Forms\Infrastructure\Repositories\EloquentFormReadRepository;
use App\Modules\Forms\Infrastructure\Repositories\EloquentFormWriteRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FormsProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerContracts();
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerRoutes();
    }

    private function registerContracts(): void
    {
        $this->app->bind(FormReadRepository::class, EloquentFormReadRepository::class);
        $this->app->bind(FormWriteRepository::class, EloquentFormWriteRepository::class);
        $this->app->bind(FormFormOptionsRepository::class, EloquentFormFormOptionsRepository::class);
        $this->app->singleton(FormDataMapper::class);
    }

    private function registerPolicies(): void
    {
        Gate::policy(Form::class, FormPolicy::class);
    }

    private function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('app/Modules/Forms/routes/form.php'));
    }
}
