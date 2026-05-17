<?php

namespace App\Modules\Blog\Domain\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * Contrato para obtener las opciones de los formularios del Blog.
 * Evita que GetPostFormOptionsAction llame directamente a Eloquent
 * rompiendo el patrón de repositorios.
 */
interface PostFormOptionsRepository
{
    public function getTypes(): Collection;

    public function getCategories(): Collection;
}
