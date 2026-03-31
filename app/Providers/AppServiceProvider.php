<?php

namespace App\Providers;

use App\Events\MediaProcessed;
use App\Models\Blog\Post;
use App\Models\Events\Event as EventModel;
use App\Models\Projects\Project;
use Carbon\CarbonImmutable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );

        JsonResource::withoutWrapping();

        Event::listen(ConversionHasBeenCompletedEvent::class, function ($event) {
            $media = $event->media;

            if ($media->model_type === Post::class) {
                broadcast(new MediaProcessed($media->model_id, 'post'));
            }

            if ($media->model_type === Project::class) {
                broadcast(new MediaProcessed($media->model_id, 'project'));
            }

            if ($media->model_type === EventModel::class) {
                broadcast(new MediaProcessed($media->model_id, 'event'));
            }
        });
    }
}
