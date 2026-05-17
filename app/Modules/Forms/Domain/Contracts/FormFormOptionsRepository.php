<?php

namespace App\Modules\Forms\Domain\Contracts;

interface FormFormOptionsRepository
{
    public function getTypes(): array;
}
