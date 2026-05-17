<?php

namespace App\Modules\Media\Providers;

use App\Events\MediaProcessed;
use App\Modules\Media\Domain\Contracts\GalleryRepository;
use App\Modules\Media\Infrastructure\Repositories\SpatieS3GalleryRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent;

class MediaProvider extends ServiceProvider
{
    /**
     * Mapa de modelo → tipo de broadcast.
     * Cada módulo registra sus modelos via MediaProvider::registerModel().
     * MediaProvider no necesita importar ninguna clase de otros módulos.
     *
     * @var array<class-string, string>
     */
    protected static array $broadcastMap = [];

    /**
     * Registra un modelo para recibir broadcast cuando sus conversiones terminen.
     *
     * Llamar desde el boot() del Provider de cada módulo:
     *   MediaProvider::registerModel(Post::class, 'post');
     *   MediaProvider::registerModel(Event::class, 'event');
     *
     * @param  class-string  $modelClass  FQCN del modelo Eloquent
     * @param  string  $type  Identificador para el frontend ('post', 'event'...)
     */
    public static function registerModel(string $modelClass, string $type): void
    {
        static::$broadcastMap[$modelClass] = $type;
    }

    public function register(): void
    {
        $this->app->bind(GalleryRepository::class, SpatieS3GalleryRepository::class);
    }

    public function boot(): void
    {
        Event::listen(ConversionHasBeenCompletedEvent::class, function ($event) {
            $media = $event->media;
            $type = static::$broadcastMap[$media->model_type] ?? null;

            if ($type !== null) {
                broadcast(new MediaProcessed($media->model_id, $type));
            }
        });
    }
}
