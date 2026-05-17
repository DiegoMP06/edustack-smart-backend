<?php

namespace App\Modules\Projects\Domain\Contracts;

interface ProjectFormOptionsRepository
{
    public function getCategories(): array;

    public function getStatuses(): array;
}
