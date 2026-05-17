<?php

namespace App\Modules\Forms\Infrastructure\Repositories;

use App\Models\Forms\FormType;
use App\Modules\Forms\Domain\Contracts\FormFormOptionsRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentFormFormOptionsRepository implements FormFormOptionsRepository
{
    public function getTypes(): Collection
    {
        return FormType::orderBy('order')->get();
    }
}
