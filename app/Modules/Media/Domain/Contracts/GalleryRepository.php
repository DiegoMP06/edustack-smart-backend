<?php

namespace App\Modules\Media\Domain\Contracts;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Contrato para operaciones de galería sobre cualquier modelo
 * que implemente HasMedia de Spatie MediaLibrary con disco S3.
 *
 * Centraliza la lógica de addMediaFromDisk que antes se
 * duplicaba en cada WriteRepository de cada módulo.
 */
interface GalleryRepository
{
    /**
     * Mueve archivos de temp/ en S3 a la colección del modelo.
     *
     * @param  HasMedia  $model  Modelo destino (Post, Event, Project...)
     * @param  string  $collection  Nombre de la colección Spatie ('gallery', 'cover'...)
     * @param  array  $s3Keys  Paths temporales: ['temp/uuid.jpg', ...]
     */
    public function addFromS3(HasMedia $model, string $collection, array $s3Keys): void;

    /**
     * Elimina un media item de la colección del modelo.
     * Valida que el media pertenece al modelo antes de eliminar.
     *
     * @throws NotFoundHttpException
     */
    public function deleteFromCollection(HasMedia $model, Media $media): void;
}
