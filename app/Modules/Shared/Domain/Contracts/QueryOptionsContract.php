<?php

namespace App\Modules\Shared\Domain\Contracts;

/**
 * Contrato para las opciones de query del index de un recurso.
 * Implementado en Infrastructure/Queries/Options/
 * Elimina el uso de strings de clase en buildPaginator.
 */
interface QueryOptionsContract
{
    public function filters(): array;

    public function includes(): array;

    public function sorts(): array;
}
